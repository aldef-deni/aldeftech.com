<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * The JSON-LD block is what Google matches against the Business Profile, so it
 * has to carry the same address and the same links the site actually shows.
 */
class SchemaOrgTest extends TestCase
{
    use RefreshDatabase;

    private function schema(): array
    {
        $html = $this->get('/')->getContent();

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $this->assertNotEmpty($m, 'JSON-LD tidak ditemukan di halaman');

        return json_decode(trim($m[1]), true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_address_comes_from_settings(): void
    {
        SiteSetting::set('address_street', 'Rumah Chiara 2, Jl. Curug Induk', 'text', 'general');
        SiteSetting::set('address_locality', 'Gunung Putri', 'text', 'general');
        SiteSetting::set('address_region', 'Jawa Barat', 'text', 'general');
        SiteSetting::set('postal_code', '16969', 'text', 'general');
        SiteSetting::clearCache();

        $address = $this->schema()['address'];

        $this->assertSame('PostalAddress', $address['@type']);
        $this->assertSame('Rumah Chiara 2, Jl. Curug Induk', $address['streetAddress']);
        $this->assertSame('Gunung Putri', $address['addressLocality']);
        $this->assertSame('Jawa Barat', $address['addressRegion']);
        $this->assertSame('16969', $address['postalCode']);
        $this->assertSame('ID', $address['addressCountry']);
    }

    public function test_blank_address_parts_are_omitted_not_emitted_empty(): void
    {
        SiteSetting::set('address_locality', 'Gunung Putri', 'text', 'general');
        SiteSetting::clearCache();

        $address = $this->schema()['address'];

        $this->assertSame('Gunung Putri', $address['addressLocality']);
        $this->assertArrayNotHasKey('postalCode', $address);
        $this->assertArrayNotHasKey('streetAddress', $address);
    }

    public function test_service_areas_become_area_served(): void
    {
        SiteSetting::set('service_areas', 'Bogor, Depok , Jakarta', 'text', 'general');
        SiteSetting::clearCache();

        $this->assertSame(['Bogor', 'Depok', 'Jakarta'], $this->schema()['areaServed']);
    }

    public function test_same_as_reads_the_social_media_screen(): void
    {
        // It used to read site-setting keys nothing ever wrote, so whatever an
        // editor saved on the Media Sosial screen never reached Google.
        SocialLink::create([
            'platform' => 'linkedin',
            'url' => 'https://linkedin.com/company/aldeftech',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        SocialLink::create([
            'platform' => 'instagram',
            'url' => 'https://instagram.com/aldeftech',
            'sort_order' => 2,
            'is_active' => false,
        ]);
        Cache::forget('schema.same_as');

        $sameAs = $this->schema()['sameAs'];

        $this->assertSame(['https://linkedin.com/company/aldeftech'], $sameAs);
    }
}
