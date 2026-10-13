<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Aldef Store: product videos (upload, gallery rules, popup player),
 * subcategories, and the "Sepatu" submenu in the admin sidebar.
 */
class StoreMediaAndCategoryTest extends TestCase
{
    use RefreshDatabase;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

    /** The ftyp box of an MP4: enough for finfo to call it video/mp4. */
    private const MP4 = "\x00\x00\x00\x18ftypmp42\x00\x00\x00\x00mp42isom\x00\x00\x00\x08free";

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::disk('public')->put('media/photo.png', base64_decode(self::PNG));
        foreach (['a', 'b', 'c', 'd'] as $n) {
            Storage::disk('public')->put("media/clip-{$n}.mp4", self::MP4);
        }
        Storage::disk('public')->put('media/fake.mp4', 'not a video at all');

        $role = Role::create(['name' => 'content', 'display_name' => 'Content']);
        $role->permissions()->attach(Permission::create(['name' => 'manage-content', 'display_name' => 'Manage Content']));
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($role);
    }

    private function payload(array $images, array $overrides = []): array
    {
        return array_merge([
            'name' => 'Runner Video',
            'status' => 'published',
            'size_system' => 'EU',
            'sizes' => ['42'],
            'images' => $images,
            'primary_image' => '0',
        ], $overrides);
    }

    public function test_video_upload_uses_its_own_formats_and_checks_the_content(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/uploads?kind=video', ['file' => UploadedFile::fake()->create('clip.mp4', 2048, 'video/mp4')])
            ->assertOk()->assertJsonStructure(['path', 'url']);

        // A text file renamed to .mp4 is refused.
        $this->actingAs($this->admin)
            ->post('/admin/uploads?kind=video', ['file' => UploadedFile::fake()->create('clip.mp4', 10, 'text/plain')])
            ->assertStatus(422);

        // Image uploads still refuse videos, and videos refuse photos.
        $this->actingAs($this->admin)
            ->post('/admin/uploads', ['file' => UploadedFile::fake()->create('clip.mp4', 10, 'video/mp4')])
            ->assertStatus(422);
        $this->actingAs($this->admin)
            ->post('/admin/uploads?kind=video', ['file' => UploadedFile::fake()->image('photo.jpg')])
            ->assertStatus(422);
    }

    public function test_product_keeps_videos_and_main_item_is_always_a_photo(): void
    {
        $this->actingAs($this->admin)->post('/admin/store/products', $this->payload([
            ['type' => 'video', 'path' => 'media/clip-a.mp4', 'alt' => 'Video 360'],
            ['type' => 'image', 'path' => 'media/photo.png'],
        ]))->assertRedirect();

        $product = StoreProduct::with('images')->sole();
        $this->assertSame(['image', 'video'], $product->images->pluck('type')->all());
        $this->assertSame('media/photo.png', $product->primaryImage()->path);
        $this->assertTrue($product->images->firstWhere('type', 'image')->is_primary);
        $this->assertFalse($product->images->firstWhere('type', 'video')->is_primary);
    }

    public function test_video_rules_are_enforced_on_save(): void
    {
        // Not a real video.
        $this->actingAs($this->admin)
            ->post('/admin/store/products', $this->payload([['type' => 'video', 'path' => 'media/fake.mp4']]))
            ->assertSessionHasErrors('images.0.path');

        // A photo cannot be passed off as a video and vice versa.
        $this->actingAs($this->admin)
            ->post('/admin/store/products', $this->payload([['type' => 'video', 'path' => 'media/photo.png']]))
            ->assertSessionHasErrors('images.0.path');

        $this->assertSame(0, StoreProduct::count());

        // No limit per product: photos and videos in any number and mix.
        $many = array_merge(
            array_map(fn ($n) => ['type' => 'video', 'path' => "media/clip-{$n}.mp4"], ['a', 'b', 'c', 'd']),
            array_map(fn ($i) => ['type' => 'image', 'path' => 'media/photo.png'], range(1, 116)),
        );
        $this->actingAs($this->admin)
            ->post('/admin/store/products', $this->payload($many))
            ->assertSessionHasNoErrors();
        $this->assertSame(120, StoreProduct::sole()->images()->count());

        // The form takes up to 50 files per pick, and says the product has no limit.
        $this->actingAs($this->admin)->get(route('admin.store.products.edit', StoreProduct::sole()))->assertOk()
            ->assertSee('data-max-batch="50"', false)
            ->assertSee('Jumlah foto/video per produk tidak dibatasi')
            ->assertDontSee('data-max-total', false);
    }

    public function test_product_page_shows_video_with_popup_player(): void
    {
        $this->actingAs($this->admin)->post('/admin/store/products', $this->payload([
            ['type' => 'image', 'path' => 'media/photo.png'],
            ['type' => 'video', 'path' => 'media/clip-a.mp4'],
        ]));

        $this->get('/store/runner-video')->assertOk()
            ->assertSee('openViewer(0)', false)
            ->assertSee('openViewer(1)', false)
            ->assertSee('Putar video', false)
            ->assertSee('<template x-if="viewer === 1">', false)
            ->assertSee('<source src="' . media_url('media/clip-a.mp4'), false)
            ->assertSee('type="video/mp4"', false)
            ->assertSee('role="dialog" aria-modal="true"', false)
            // Link previews use the photo, never the video.
            ->assertSee('<meta property="og:image" content="' . media_url('media/photo.png'), false);
    }

    public function test_subcategories_one_level_deep(): void
    {
        $this->actingAs($this->admin)->post('/admin/store/categories', ['name' => 'Sepatu Lari']);
        $parent = StoreCategory::sole();

        $this->actingAs($this->admin)->post('/admin/store/categories', ['name' => 'Trail', 'parent_id' => $parent->id])
            ->assertRedirect();
        $child = StoreCategory::where('slug', 'trail')->sole();
        $this->assertSame($parent->id, $child->parent_id);
        $this->assertSame('Sepatu Lari › Trail', $child->label());

        // No third level, no self-parenting, a parent with children stays a parent.
        $this->actingAs($this->admin)->post('/admin/store/categories', ['name' => 'Ultra', 'parent_id' => $child->id])
            ->assertSessionHasErrors('parent_id');
        $this->actingAs($this->admin)->put(route('admin.store.categories.update', $parent), ['name' => 'Sepatu Lari', 'parent_id' => $parent->id])
            ->assertSessionHasErrors('parent_id');
        $other = StoreCategory::create(['name' => 'Sneakers', 'slug' => 'sneakers']);
        $this->actingAs($this->admin)->put(route('admin.store.categories.update', $parent), ['name' => 'Sepatu Lari', 'parent_id' => $other->id])
            ->assertSessionHasErrors('parent_id');

        $this->actingAs($this->admin)->get('/admin/store/categories')->assertOk()
            ->assertSeeInOrder(['Sepatu Lari', 'Trail', 'Subkategori']);
        $this->actingAs($this->admin)->get('/admin/store/products/create')->assertOk()
            ->assertSee('— Trail', false);

        // Deleting the parent keeps the subcategory as a top-level category.
        $this->actingAs($this->admin)->delete(route('admin.store.categories.destroy', $parent));
        $this->assertNull($child->fresh()->parent_id);
    }

    public function test_catalogue_filter_on_a_parent_includes_its_subcategories(): void
    {
        $lari = StoreCategory::create(['name' => 'Sepatu Lari', 'slug' => 'sepatu-lari']);
        $trail = StoreCategory::create(['name' => 'Trail', 'slug' => 'trail', 'parent_id' => $lari->id]);
        $sneakers = StoreCategory::create(['name' => 'Sneakers', 'slug' => 'sneakers']);

        foreach ([['Road Runner', $lari], ['Mountain Goat', $trail], ['City Low', $sneakers]] as $i => [$name, $category]) {
            StoreProduct::createWithSku([
                'name' => $name, 'slug' => \Illuminate\Support\Str::slug($name), 'status' => 'published',
                'published_at' => now(), 'store_category_id' => $category->id,
            ]);
        }

        $this->get('/store?kategori=sepatu-lari')->assertOk()
            ->assertSee('Road Runner')->assertSee('Mountain Goat')->assertDontSee('City Low');
        $this->get('/store?kategori=trail')->assertOk()
            ->assertSee('Mountain Goat')->assertDontSee('Road Runner');
        $this->get('/store?kategori=trail')->assertOk()
            ->assertSeeInOrder(['>Sepatu Lari<', '>— Trail<', '>Sneakers<'], false);

        $this->get('/store/mountain-goat')->assertOk()->assertSeeInOrder(['Sepatu Lari', '›', 'Trail']);
    }

    public function test_sidebar_groups_shoe_menus_under_sepatu(): void
    {
        $product = StoreProduct::createWithSku(['name' => 'X', 'slug' => 'x', 'status' => 'draft']);

        // On the edit page the "Sepatu" group is open and "Produk Sepatu" lit.
        $this->actingAs($this->admin)->get(route('admin.store.products.edit', $product))->assertOk()
            ->assertSeeInOrder(['Aldef Store', 'menu-item active open', 'Sepatu', 'menu-sub', 'menu-item active', 'Produk Sepatu', 'Kategori Sepatu'], false);
    }
}
