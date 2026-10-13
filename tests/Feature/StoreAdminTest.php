<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\User;
use App\Services\StoreWhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreAdminTest extends TestCase
{
    use RefreshDatabase;

    /** A real 1×1 PNG, so getimagesize() accepts it. */
    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::disk('public')->put('media/shoe-1.png', base64_decode(self::PNG));
        Storage::disk('public')->put('media/shoe-2.png', base64_decode(self::PNG));
        Storage::disk('public')->put('media/not-an-image.png', 'plain text pretending to be a png');
    }

    private function user(array $permissions): User
    {
        $role = Role::create(['name' => 'role-' . uniqid(), 'display_name' => 'Role']);
        foreach ($permissions as $name) {
            $role->permissions()->attach(Permission::firstOrCreate(['name' => $name], ['display_name' => $name]));
        }
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Runner Pro 2',
            'material' => 'Knit',
            'description' => 'Ringan.',
            'size_system' => 'EU',
            'status' => 'draft',
            'is_featured' => '0',
            'sort_order' => '0',
            'sizes' => ['42', '43', ''],
            'colors' => ['Hitam', 'Putih'],
            'variant_matrix' => '1',
            'available' => ['42|Hitam', '43|Hitam', '43|Putih'],
            'images' => [
                ['path' => 'media/shoe-1.png', 'alt' => 'Tampak samping'],
                ['path' => 'media/shoe-2.png', 'alt' => ''],
            ],
            'primary_image' => '1',
        ], $overrides);
    }

    public function test_guests_and_unauthorised_users_are_kept_out(): void
    {
        $this->get('/admin/store/products')->assertRedirect(route('admin.login'));
        $this->post('/admin/store/products', $this->payload())->assertRedirect(route('admin.login'));

        $leadsOnly = $this->user(['manage-leads']);
        $this->actingAs($leadsOnly)->get('/admin/store/products')->assertForbidden();
        $this->actingAs($leadsOnly)->post('/admin/store/products', $this->payload())->assertForbidden();
        $this->actingAs($leadsOnly)->get('/admin/store/categories')->assertForbidden();

        // The WhatsApp number is a setting: content editors cannot change it.
        $editor = $this->user(['manage-content']);
        $this->actingAs($editor)->get('/admin/store/products')->assertOk();
        $this->actingAs($editor)->get('/admin/store/settings')->assertForbidden();
        $this->actingAs($editor)->put('/admin/store/settings', ['store_whatsapp_number' => '628128968609'])->assertForbidden();

        $this->assertSame(0, StoreProduct::count());
    }

    public function test_admin_creates_a_product_with_sku_photos_and_variants(): void
    {
        $admin = $this->user(['manage-content']);

        $this->actingAs($admin)->get('/admin/store/products/create')->assertOk()->assertSee('Dibuat otomatis saat disimpan');

        $response = $this->actingAs($admin)->post('/admin/store/products', $this->payload(['price' => '500000']));

        $product = StoreProduct::with(['images', 'variants'])->sole();
        $response->assertRedirect(route('admin.store.products.edit', $product));

        $this->assertMatchesRegularExpression('/^ATS\d{8}00001$/', $product->sku);
        $this->assertSame('runner-pro-2', $product->slug);
        $this->assertSame('draft', $product->status);
        $this->assertFalse(Schema::hasColumn('store_products', 'price'));

        // Second photo was chosen as the main one.
        $this->assertSame('media/shoe-2.png', $product->images->first()->path);
        $this->assertTrue($product->images->first()->is_primary);
        $this->assertSame('Tampak samping', $product->images->firstWhere('path', 'media/shoe-1.png')->alt);

        // 2 sizes × 2 colours, availability exactly as ticked.
        $this->assertSame(4, $product->variants->count());
        $state = $product->variants->mapWithKeys(fn ($v) => [$v->size . '|' . $v->color => $v->is_available])->all();
        $this->assertSame(['42|Hitam' => true, '43|Hitam' => true, '42|Putih' => false, '43|Putih' => true], $state);

        // Draft stays private until published.
        $this->get('/store/runner-pro-2')->assertNotFound();
        $this->actingAs($admin)->put(route('admin.store.products.update', $product), $this->payload(['status' => 'published']))
            ->assertRedirect();
        $this->assertNotNull($product->fresh()->published_at);
        $this->get('/store/runner-pro-2')->assertOk();
    }

    public function test_only_uploaded_images_are_accepted(): void
    {
        $admin = $this->user(['manage-content']);

        foreach (['../.env', 'media/missing.png', 'media/not-an-image.png', 'images/og-image.jpg', 'media/shoe.svg'] as $path) {
            $this->actingAs($admin)
                ->post('/admin/store/products', $this->payload(['images' => [['path' => $path]]]))
                ->assertSessionHasErrors('images.0.path');
        }

        $this->assertSame(0, StoreProduct::count());
    }

    public function test_slug_stays_readable_and_unique(): void
    {
        $admin = $this->user(['manage-content']);

        $this->actingAs($admin)->post('/admin/store/products', $this->payload());
        $this->actingAs($admin)->post('/admin/store/products', $this->payload());

        $this->assertSame(['runner-pro-2', 'runner-pro-2-2'], StoreProduct::orderBy('id')->pluck('slug')->all());

        // A typed slug that is taken is an error, not silently renamed.
        $this->actingAs($admin)->post('/admin/store/products', $this->payload(['slug' => 'runner-pro-2']))
            ->assertSessionHasErrors('slug');
    }

    public function test_variants_without_the_table_keep_their_state(): void
    {
        $admin = $this->user(['manage-content']);
        $this->actingAs($admin)->post('/admin/store/products', $this->payload());
        $product = StoreProduct::sole();

        // JavaScript off: no table submitted. Existing combinations keep their
        // state; the new size starts in stock.
        $this->actingAs($admin)->put(route('admin.store.products.update', $product), $this->payload([
            'sizes' => ['42', '43', '44'], 'variant_matrix' => null, 'available' => null,
        ]));

        $state = $product->fresh()->variants->mapWithKeys(fn ($v) => [$v->size . '|' . $v->color => $v->is_available])->all();
        $this->assertFalse($state['42|Putih']);
        $this->assertTrue($state['44|Putih']);
        $this->assertCount(6, $state);
    }

    public function test_store_whatsapp_number_is_normalised_and_validated(): void
    {
        $admin = $this->user(['manage-settings']);

        $this->actingAs($admin)->get('/admin/store/settings')->assertOk()->assertSee('Belum ada nomor WhatsApp yang valid');

        $this->actingAs($admin)->put('/admin/store/settings', ['store_whatsapp_number' => '0812-8968-609'])->assertRedirect();
        $this->assertSame('628128968609', SiteSetting::get(StoreWhatsApp::SETTING_KEY));

        $this->actingAs($admin)->put('/admin/store/settings', ['store_whatsapp_number' => '12345'])
            ->assertSessionHasErrors('store_whatsapp_number');
        $this->actingAs($admin)->put('/admin/store/settings', ['store_whatsapp_number' => '6281234567890'])
            ->assertSessionHasErrors('store_whatsapp_number');
        $this->assertSame('628128968609', SiteSetting::get(StoreWhatsApp::SETTING_KEY));

        // Clearing it falls back to the business number.
        SiteSetting::set('whatsapp_number', '628111222333', 'text', 'whatsapp');
        $this->actingAs($admin)->put('/admin/store/settings', ['store_whatsapp_number' => '']);
        $this->assertSame('628111222333', StoreWhatsApp::number());
    }

    public function test_category_crud_and_delete_keeps_products(): void
    {
        $admin = $this->user(['manage-content']);

        $this->actingAs($admin)->post('/admin/store/categories', ['name' => 'Sepatu Lari'])->assertRedirect();
        $category = StoreCategory::sole();
        $this->assertSame('sepatu-lari', $category->slug);

        $this->actingAs($admin)->post('/admin/store/products', $this->payload(['store_category_id' => $category->id]));
        $this->actingAs($admin)->get('/admin/store/categories')->assertOk()->assertSee('Sepatu Lari');

        $this->actingAs($admin)->delete(route('admin.store.categories.destroy', $category))->assertRedirect();
        $this->assertNull(StoreProduct::sole()->store_category_id);
    }
}
