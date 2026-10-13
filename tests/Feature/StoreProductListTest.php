<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The admin product list: bulk delete, and how many products a page shows.
 */
class StoreProductListTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'content', 'display_name' => 'Content']);
        $role->permissions()->attach(Permission::create(['name' => 'manage-content', 'display_name' => 'Manage Content']));
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($role);
    }

    private function products(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            StoreProduct::createWithSku(['name' => "Shoe {$i}", 'slug' => "shoe-{$i}", 'status' => 'published', 'published_at' => now()]);
        }
    }

    public function test_selected_products_can_be_deleted_together(): void
    {
        $this->products(3);
        [$a, $b, $c] = StoreProduct::orderBy('id')->get()->all();

        $this->actingAs($this->admin)->get('/admin/store/products')->assertOk()
            ->assertSee('value="delete"', false)
            ->assertSee('data-bulk-delete', false);

        $this->actingAs($this->admin)->from('/admin/store/products')
            ->post(route('admin.store.products.bulk'), ['action' => 'delete', 'ids' => [$a->id, $b->id]])
            ->assertRedirect('/admin/store/products')
            ->assertSessionHas('success', '2 produk dihapus.');

        // Soft deleted: gone from the list and the site, SKUs stay reserved.
        $this->assertSame([$c->id], StoreProduct::pluck('id')->all());
        $this->assertSame(3, StoreProduct::withTrashed()->count());
        $this->get('/store/' . $a->slug)->assertNotFound();

        $this->actingAs($this->admin)->post(route('admin.store.products.bulk'), ['action' => 'delete', 'ids' => []])
            ->assertSessionHasErrors('ids');
    }

    public function test_list_shows_20_per_page_by_default_with_a_choice_of_more(): void
    {
        $this->products(25);

        $page = $this->actingAs($this->admin)->get('/admin/store/products')->assertOk()
            ->assertSee('1–20 dari 25 produk')
            ->assertSee('<option value="20" selected>20</option>', false)
            ->assertSee('<option value="500"', false)
            ->assertSee('class="pagination', false)
            ->assertSee('per_page', false)
            ->getContent();
        $this->assertSame(20, substr_count($page, 'name="ids[]"'));

        $this->actingAs($this->admin)->get('/admin/store/products?page=2')->assertOk()->assertSee('21–25 dari 25 produk');

        $page = $this->actingAs($this->admin)->get('/admin/store/products?per_page=50')->assertOk()
            ->assertSee('1–25 dari 25 produk')
            ->assertSee('<option value="50" selected>50</option>', false)
            // The search form keeps the choice.
            ->assertSee('<input type="hidden" name="per_page" value="50">', false)
            ->getContent();
        $this->assertSame(25, substr_count($page, 'name="ids[]"'));

        // Anything else falls back to 20.
        $this->actingAs($this->admin)->get('/admin/store/products?per_page=7')->assertOk()->assertSee('1–20 dari 25 produk');

        // A page emptied by a delete sends the admin to the last page left.
        $this->actingAs($this->admin)->get('/admin/store/products?page=5')
            ->assertRedirect('/admin/store/products?page=2');
    }
}
