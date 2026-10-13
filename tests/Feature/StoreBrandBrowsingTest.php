<?php

namespace Tests\Feature;

use App\Models\StoreBrand;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Aldef Store browsing goes brand first: /store lists brands, a brand lists
 * its categories (or its shoes when it has none), a category its
 * subcategories (or its shoes).
 */
class StoreBrandBrowsingTest extends TestCase
{
    use RefreshDatabase;

    private StoreCategory $lari;
    private StoreCategory $trail;
    private StoreCategory $sneakers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lari = StoreCategory::create(['name' => 'Sepatu Lari', 'slug' => 'sepatu-lari']);
        $this->trail = StoreCategory::create(['name' => 'Trail', 'slug' => 'trail', 'parent_id' => $this->lari->id]);
        $this->sneakers = StoreCategory::create(['name' => 'Sneakers', 'slug' => 'sneakers']);
    }

    private function shoe(string $name, ?string $brand, ?StoreCategory $category = null, string $status = 'published'): StoreProduct
    {
        return StoreProduct::createWithSku([
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => $status,
            'published_at' => now(),
            'store_brand_id' => $brand ? StoreBrand::findOrCreateByName($brand)->id : null,
            'store_category_id' => $category?->id,
        ]);
    }

    public function test_store_lists_brands_and_shoes_without_a_brand(): void
    {
        $this->shoe('Pegasus Road', 'Nike', $this->lari);
        $this->shoe('Air Force', 'Nike', $this->sneakers);
        $this->shoe('Samba', 'Adidas');
        $this->shoe('Hidden Puma', 'Puma', null, 'draft');
        $this->shoe('Plain Loafer', null);

        $this->get('/store')->assertOk()
            ->assertSee('Pilih Brand')
            ->assertSee('href="' . route('store.brand', 'nike') . '"', false)
            ->assertSee('href="' . route('store.brand', 'adidas') . '"', false)
            ->assertSeeInOrder(['Adidas', '1 model', 'Nike', '2 model'])
            // A brand with only drafts has no page to open.
            ->assertDontSee('Puma')
            // Shoes are behind their brand, except those without one.
            ->assertDontSee('Pegasus Road')
            ->assertSee('Produk Lainnya')
            ->assertSee('Plain Loafer');
    }

    public function test_brand_shows_categories_then_subcategories_then_shoes(): void
    {
        $this->shoe('Pegasus Road', 'Nike', $this->lari);
        $this->shoe('Pegasus Trail', 'Nike', $this->trail);
        $this->shoe('Air Force', 'Nike', $this->sneakers);
        $this->shoe('Nike Mystery', 'Nike');
        $this->shoe('Adidas Trail', 'Adidas', $this->trail);

        // Brand: its categories as tiles; shoes without a category below them.
        $this->get('/store/brand/nike')->assertOk()
            ->assertSee('<title>Nike — Aldef Store | Aldef Tech</title>', false)
            ->assertSee('Pilih Kategori')
            ->assertSee('href="' . route('store.brand', ['nike', 'sepatu-lari']) . '"', false)
            ->assertSee('href="' . route('store.brand', ['nike', 'sneakers']) . '"', false)
            ->assertSeeInOrder(['Sepatu Lari', '2 model', 'Sneakers', '1 model', 'Produk Lainnya', 'Nike Mystery'])
            ->assertDontSee('Pegasus Road')
            ->assertDontSee('Adidas Trail');

        // Category with subcategories: tiles, and shoes filed on the category itself.
        $this->get('/store/brand/nike/sepatu-lari')->assertOk()
            ->assertSee('Pilih Subkategori')
            ->assertSee('href="' . route('store.brand', ['nike', 'sepatu-lari', 'trail']) . '"', false)
            ->assertSee('Lainnya di Sepatu Lari')
            ->assertSee('Pegasus Road')
            ->assertDontSee('Pegasus Trail')
            ->assertSee('<link rel="canonical" href="' . route('store.brand', ['nike', 'sepatu-lari']) . '">', false);

        // Subcategory: the shoes, of this brand only.
        $this->get('/store/brand/nike/sepatu-lari/trail')->assertOk()
            ->assertSee('Pegasus Trail')
            ->assertDontSee('Adidas Trail')
            ->assertSee('<title>Trail — Sepatu Lari — Nike — Aldef Store | Aldef Tech</title>', false)
            ->assertSeeInOrder(['Aldef Store', 'Nike', 'Sepatu Lari', 'Trail']);

        // Category without subcategories: straight to the shoes.
        $this->get('/store/brand/nike/sneakers')->assertOk()
            ->assertSee('Air Force')
            ->assertDontSee('Pilih Subkategori');
    }

    public function test_brand_without_categories_goes_straight_to_the_shoes(): void
    {
        $this->shoe('Samba', 'Adidas');
        $this->shoe('Gazelle', 'Adidas');

        $this->get('/store/brand/adidas')->assertOk()
            ->assertDontSee('Pilih Kategori')
            ->assertSee('2 model')
            ->assertSee('Samba')
            ->assertSee('Gazelle')
            ->assertSee('Lihat Detail');
    }

    public function test_pages_without_published_shoes_do_not_exist(): void
    {
        $this->shoe('Pegasus Road', 'Nike', $this->lari);
        $this->shoe('Hidden Puma', 'Puma', $this->lari, 'draft');
        StoreBrand::findOrCreateByName('Reebok');

        $this->get('/store/brand/unknown')->assertNotFound();
        $this->get('/store/brand/reebok')->assertNotFound();
        $this->get('/store/brand/puma')->assertNotFound();
        // Nike has no sneakers, no trail shoes, and trail sits under Sepatu Lari.
        $this->get('/store/brand/nike/sneakers')->assertNotFound();
        $this->get('/store/brand/nike/sepatu-lari/trail')->assertNotFound();
        $this->get('/store/brand/nike/trail')->assertNotFound();
        $this->get('/store/brand/nike/sneakers/trail')->assertNotFound();
    }

    public function test_product_page_and_sitemap_follow_the_brand_path(): void
    {
        $this->shoe('Pegasus Trail', 'Nike', $this->trail);
        $this->shoe('Plain Loafer', null, $this->sneakers);

        $this->get('/store/pegasus-trail')->assertOk()
            ->assertSeeInOrder(['Aldef Store', 'Nike', 'Pegasus Trail'])
            ->assertSee('href="' . route('store.brand', 'nike') . '"', false)
            ->assertSee('href="' . route('store.brand', ['nike', 'sepatu-lari']) . '"', false)
            ->assertSee('href="' . route('store.brand', ['nike', 'sepatu-lari', 'trail']) . '"', false)
            ->assertSee('"name":"Nike","item":"' . route('store.brand', 'nike') . '"', false);

        // Without a brand the category falls back to the catalogue filter.
        $this->get('/store/plain-loafer')->assertOk()
            ->assertSee('href="' . e(route('store', ['kategori' => 'sneakers'])) . '"', false);

        $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();
        foreach (['/store/brand/nike</loc>', '/store/brand/nike/sepatu-lari</loc>', '/store/brand/nike/sepatu-lari/trail</loc>'] as $loc) {
            $this->assertStringContainsString($loc, $sitemap);
        }
        $this->assertStringNotContainsString('/en/store', $sitemap);
    }
}
