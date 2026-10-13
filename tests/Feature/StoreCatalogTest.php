<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use App\Models\StoreProductVariant;
use App\Services\StoreWhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Aldef Store, public side: a shoe catalogue without prices whose only
 * call to action is a WhatsApp enquiry.
 */
class StoreCatalogTest extends TestCase
{
    use RefreshDatabase;

    private function product(array $attributes = [], array $variants = [], int $images = 1): StoreProduct
    {
        // Tests name the brand as text; products link to a brand record.
        $attributes = array_merge(['brand' => 'Aldef'], $attributes);
        $brand = $attributes['brand'] ? \App\Models\StoreBrand::findOrCreateByName($attributes['brand']) : null;
        unset($attributes['brand']);

        $product = StoreProduct::createWithSku(array_merge([
            'name' => 'Runner Pro',
            'slug' => 'runner-pro',
            'store_brand_id' => $brand?->id,
            'status' => StoreProduct::STATUS_PUBLISHED,
            'published_at' => now(),
            'size_system' => 'EU',
        ], $attributes));

        foreach ($variants as $i => [$size, $color, $available]) {
            StoreProductVariant::create([
                'store_product_id' => $product->id, 'size' => $size, 'color' => $color,
                'is_available' => $available, 'sort_order' => $i,
            ]);
        }

        for ($i = 0; $i < $images; $i++) {
            StoreProductImage::create([
                'store_product_id' => $product->id, 'path' => "media/shoe-{$product->id}-{$i}.webp",
                'is_primary' => $i === 0, 'sort_order' => $i,
            ]);
        }

        return $product->fresh();
    }

    /**
     * The picker configuration the page hands to Alpine. @js() emits
     * JSON.parse('…'): a JavaScript string literal (its escapes are JSON
     * compatible) holding the JSON, so it is decoded twice.
     */
    private function pickerConfig(string $html): array
    {
        $this->assertSame(1, preg_match("/x-data=\"storeProduct\\(JSON\\.parse\\('(.*?)'\\)\\)\"/s", $html, $m));
        $json = json_decode('"' . $m[1] . '"', flags: JSON_THROW_ON_ERROR);

        return json_decode($json, true, flags: JSON_THROW_ON_ERROR);
    }

    public function test_catalogue_shows_published_products_without_any_price(): void
    {
        $this->product(['name' => 'Runner Pro', 'slug' => 'runner-pro'], [['42', '', true]]);
        $this->product(['name' => 'Secret Draft Shoe', 'slug' => 'secret-draft', 'status' => StoreProduct::STATUS_DRAFT]);

        $html = $this->get('/store')->assertOk()
            ->assertSee('Koleksi Sepatu Pilihan')
            ->assertSee('Temukan sepatu pilihan untuk aktivitas Anda. Pilih model dan ukuran, lalu hubungi kami melalui WhatsApp untuk informasi harga dan ketersediaan.')
            ->assertSee('Runner Pro')
            ->assertSee('Lihat Detail')
            ->assertSee('Ukuran EU 42')
            ->assertSee('loading="lazy"', false)
            ->assertDontSee('Secret Draft Shoe')
            ->getContent();

        $this->assertDoesNotMatchRegularExpression('/Rp\s?\d|Rp0|checkout|keranjang/i', $html);
    }

    public function test_a_draft_product_is_not_reachable_by_its_url(): void
    {
        $this->product(['name' => 'Hidden', 'slug' => 'hidden-shoe', 'status' => StoreProduct::STATUS_DRAFT]);

        $this->get('/store/hidden-shoe')->assertNotFound();
        $this->get('/store/does-not-exist')->assertNotFound();
    }

    public function test_search_category_and_size_filters(): void
    {
        $running = StoreCategory::create(['name' => 'Sepatu Lari', 'slug' => 'sepatu-lari']);
        $formal = StoreCategory::create(['name' => 'Formal', 'slug' => 'formal']);

        $this->product(['name' => 'Runner Pro', 'slug' => 'runner-pro', 'brand' => 'Aldef', 'store_category_id' => $running->id], [['42', '', true], ['43', '', false]]);
        $this->product(['name' => 'Oxford Classic', 'slug' => 'oxford-classic', 'brand' => 'Bata', 'store_category_id' => $formal->id], [['43', '', true]]);

        $this->get('/store?q=oxford')->assertOk()->assertSee('Oxford Classic')->assertDontSee('Runner Pro');
        $this->get('/store?q=aldef')->assertOk()->assertSee('Runner Pro')->assertDontSee('Oxford Classic');
        $this->get('/store?kategori=sepatu-lari')->assertOk()->assertSee('Runner Pro')->assertDontSee('Oxford Classic');

        // 43 of Runner Pro is out of stock, so only Oxford matches size 43.
        $this->get('/store?ukuran=43')->assertOk()->assertSee('Oxford Classic')->assertDontSee('Runner Pro');

        $this->get('/store?q=tidak-ada')->assertOk()->assertSee('Tidak ada sepatu yang cocok');

        // Filtered views stay out of the index and point at /store.
        $this->get('/store?q=oxford')->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertSee('<link rel="canonical" href="' . route('store') . '">', false);
    }

    public function test_pagination_and_out_of_range_pages(): void
    {
        for ($i = 1; $i <= 13; $i++) {
            $this->product(['name' => "Shoe {$i}", 'slug' => "shoe-{$i}"]);
        }

        $this->get('/store')->assertOk()->assertSee('page=2', false);
        $this->get('/store?page=2')->assertOk()
            ->assertSee('<link rel="canonical" href="' . route('store') . '?page=2">', false);
        $this->get('/store?page=3')->assertNotFound();
        $this->get('/store?page=1')->assertRedirect(route('store'))->assertStatus(301);
    }

    public function test_empty_catalogue_has_a_friendly_state(): void
    {
        $this->get('/store')->assertOk()->assertSee('Koleksi sedang kami siapkan');
    }

    public function test_detail_page_carries_product_variants_and_whatsapp_setup(): void
    {
        SiteSetting::set(StoreWhatsApp::SETTING_KEY, '628128968609', 'text', 'store');
        $category = StoreCategory::create(['name' => 'Sepatu Lari', 'slug' => 'sepatu-lari']);

        $product = $this->product([
            'name' => 'Runner "Pro" & Co',
            'slug' => 'runner-pro-co',
            'material' => 'Knit',
            'size_guide' => '42 EU = 26,5 cm',
            'description' => 'Ringan untuk lari harian.',
            'store_category_id' => $category->id,
        ], [['42', 'Hitam', true], ['42', 'Putih', false], ['43', 'Putih', true]], 3);

        $html = $this->get('/store/runner-pro-co')->assertOk()
            ->assertSee('Tanya Harga via WhatsApp')
            ->assertSee($product->sku)
            ->assertSee('Knit')
            ->assertSee('Sepatu Lari')
            ->assertSee('Panduan ukuran')
            ->assertSee('Ringan untuk lari harian.')
            ->assertSee('Ukuran')
            ->assertDontSee('wa-widget', false)
            ->getContent();

        $this->assertDoesNotMatchRegularExpression('/Rp\s?\d|Rp0/', $html);

        // The picker gets the exact combinations, out-of-stock ones included.
        $config = $this->pickerConfig($html);
        $this->assertSame('628128968609', $config['number']);
        $this->assertSame($product->sku, $config['sku']);
        $this->assertSame(route('store.show', 'runner-pro-co'), $config['url']);
        $this->assertSame(['42', '43'], $config['sizes']);
        $this->assertSame(['Hitam', 'Putih'], $config['colors']);
        $this->assertContains(['size' => '42', 'color' => 'Putih', 'available' => false], $config['variants']);
        $this->assertSame(3, $config['imageCount']);
    }

    public function test_missing_or_placeholder_number_never_becomes_a_live_link(): void
    {
        $this->product([], [['42', '', true]]);

        // The site-wide navbar keeps its own consultation link; what matters is
        // that the product's enquiry button has no number to build a link from.
        $html = $this->get('/store/runner-pro')->assertOk()
            ->assertSee('Nomor WhatsApp toko belum diatur')
            ->getContent();
        $this->assertNull($this->pickerConfig($html)['number']);

        SiteSetting::set('whatsapp_number', '6281234567890', 'text', 'whatsapp');
        $this->assertNull(StoreWhatsApp::number());

        SiteSetting::set('whatsapp_number', '0812-8968-609', 'text', 'whatsapp');
        $this->assertSame('628128968609', StoreWhatsApp::number());
    }

    public function test_seo_canonical_breadcrumb_and_sitemap(): void
    {
        $this->product(['name' => 'Runner Pro', 'slug' => 'runner-pro', 'meta_title' => 'Runner Pro — Sepatu Lari Ringan']);
        $this->product(['name' => 'Draft', 'slug' => 'draft-shoe', 'status' => StoreProduct::STATUS_DRAFT]);

        $this->get('/store/runner-pro')->assertOk()
            ->assertSee('<title>Runner Pro — Sepatu Lari Ringan</title>', false)
            ->assertSee('<link rel="canonical" href="' . route('store.show', 'runner-pro') . '">', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('alt="Runner Pro — Aldef"', false)
            ->assertDontSee('"@type":"Product"', false)
            ->assertDontSee('<link rel="alternate" hreflang="en"', false);

        $this->get('/store')->assertSee('<title>Aldef Store — Koleksi Sepatu Pilihan | Aldef Tech</title>', false);

        $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('/store</loc>', $sitemap);
        $this->assertStringContainsString('/store/runner-pro</loc>', $sitemap);
        $this->assertStringNotContainsString('draft-shoe', $sitemap);
        $this->assertStringNotContainsString('/en/store', $sitemap);
    }

    public function test_navigation_links_to_the_store(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // A red "Marketplace" button, not an item of the main menu rail.
        $this->assertMatchesRegularExpression('#<a href="' . preg_quote(route('store'), '#') . '"\s+class="hidden sm:inline-flex btn btn-sm btn-marketplace#', $html);
        $this->assertStringContainsString('btn-marketplace-dot', $html);
        $this->assertStringContainsString('>Marketplace</span>', $html);
        preg_match('#<div class="nav-rail".*?</div>#s', $html, $rail);
        $this->assertStringNotContainsString(route('store'), $rail[0]);

        // On the store itself the button shows it is the current page.
        $this->get('/store')->assertOk()->assertSee('btn-marketplace is-current', false);
    }

    public function test_sku_is_dated_sequential_and_never_reused(): void
    {
        Carbon::setTestNow('2026-10-12 09:00:00');

        $first = StoreProduct::createWithSku(['name' => 'A', 'slug' => 'a', 'status' => 'draft']);
        $second = StoreProduct::createWithSku(['name' => 'B', 'slug' => 'b', 'status' => 'draft']);

        $this->assertSame('ATS1210202600001', $first->sku);
        $this->assertSame('ATS1210202600002', $second->sku);

        $second->delete();
        Carbon::setTestNow('2026-10-13 09:00:00');
        $third = StoreProduct::createWithSku(['name' => 'C', 'slug' => 'c', 'status' => 'draft']);

        $this->assertSame('ATS1310202600003', $third->sku);
        Carbon::setTestNow();
    }
}
