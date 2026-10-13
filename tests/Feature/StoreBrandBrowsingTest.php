<?php

namespace Tests\Feature;

use App\Models\StoreBrand;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Aldef Store browsing goes brand first: /store lists brands, and a brand
 * opens straight onto its shoes.
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

    public function test_brand_opens_straight_onto_its_shoes(): void
    {
        $this->shoe('Pegasus Road', 'Nike', $this->lari);
        $this->shoe('Pegasus Trail', 'Nike', $this->trail);
        $this->shoe('Air Force', 'Nike', $this->sneakers);
        $this->shoe('Nike Mystery', 'Nike');
        $this->shoe('Adidas Trail', 'Adidas', $this->trail);

        // Every shoe of the brand, whatever its category; no category cards.
        $this->get('/store/brand/nike')->assertOk()
            ->assertSee('<title>Nike — Aldef Store | Aldef Tech</title>', false)
            ->assertSee('4 model')
            ->assertSee('Pegasus Road')->assertSee('Pegasus Trail')->assertSee('Air Force')->assertSee('Nike Mystery')
            ->assertSee('Lihat Detail')
            ->assertDontSee('Adidas Trail')
            ->assertDontSee('Pilih Kategori')
            ->assertDontSee('store-tile', false);

        // Category addresses (linked from product pages) narrow the list.
        $this->get('/store/brand/nike/sepatu-lari')->assertOk()
            ->assertSee('Pegasus Road')->assertSee('Pegasus Trail')
            ->assertDontSee('Air Force')
            ->assertSee('<link rel="canonical" href="' . route('store.brand', ['nike', 'sepatu-lari']) . '">', false);

        $this->get('/store/brand/nike/sepatu-lari/trail')->assertOk()
            ->assertSee('Pegasus Trail')
            ->assertDontSee('Pegasus Road')
            ->assertDontSee('Adidas Trail')
            ->assertSee('<title>Trail — Sepatu Lari — Nike — Aldef Store | Aldef Tech</title>', false)
            ->assertSeeInOrder(['Aldef Store', 'Nike', 'Sepatu Lari', 'Trail']);
    }

    public function test_brand_pages_are_paginated(): void
    {
        for ($i = 1; $i <= 13; $i++) {
            $this->shoe("Samba {$i}", 'Adidas', $this->sneakers);
        }

        $url = route('store.brand', 'adidas');
        $this->get('/store/brand/adidas')->assertOk()->assertSee('13 model')->assertSee('page=2', false);
        $this->get('/store/brand/adidas?page=2')->assertOk()
            ->assertSee('<link rel="canonical" href="' . $url . '?page=2">', false);
        $this->get('/store/brand/adidas?page=3')->assertNotFound();
        $this->get('/store/brand/adidas?page=1')->assertRedirect($url)->assertStatus(301);
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
