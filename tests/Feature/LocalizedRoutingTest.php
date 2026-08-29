<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Language lives in the URL, not the session.
 *
 * The session-based switcher gave English no address of its own, so Googlebot —
 * which carries no session — only ever saw Indonesian.
 */
class LocalizedRoutingTest extends TestCase
{
    use RefreshDatabase;

    public static function pages(): array
    {
        return [
            'home' => ['', ''],
            'about' => ['/about', '/en/about'],
            'services' => ['/services', '/en/services'],
            'solutions' => ['/solutions', '/en/solutions'],
            'portfolio' => ['/portfolio', '/en/portfolio'],
            'blog' => ['/blog', '/en/blog'],
            'faq' => ['/faq', '/en/faq'],
            'contact' => ['/contact', '/en/contact'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_both_languages_are_reachable(string $id, string $en): void
    {
        $this->get($id ?: '/')->assertOk()->assertSee('<html lang="id"', false);
        $this->get($en ?: '/en')->assertOk()->assertSee('<html lang="en"', false);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_each_page_declares_reciprocal_hreflang(string $id, string $en): void
    {
        $idUrl = url($id ?: '/');
        $enUrl = url($en ?: '/en');

        foreach ([$id ?: '/', $en ?: '/en'] as $path) {
            $response = $this->get($path);

            // Google ignores hreflang that does not point back from both sides.
            $response->assertSee('hreflang="id" href="' . $idUrl . '"', false);
            $response->assertSee('hreflang="en" href="' . $enUrl . '"', false);
            $response->assertSee('hreflang="x-default" href="' . $idUrl . '"', false);
        }
    }

    public function test_canonical_points_at_the_page_itself(): void
    {
        $this->get('/services')
            ->assertSee('<link rel="canonical" href="' . url('/services') . '"', false);

        $this->get('/en/services')
            ->assertSee('<link rel="canonical" href="' . url('/en/services') . '"', false);
    }

    public function test_og_locale_follows_the_page_language(): void
    {
        $this->get('/services')->assertSee('og:locale" content="id_ID"', false);
        $this->get('/en/services')->assertSee('og:locale" content="en_US"', false);
    }

    public function test_english_pages_keep_navigation_in_english(): void
    {
        $response = $this->get('/en/services');

        $response->assertSee('href="' . url('/en/portfolio') . '"', false);
        // The only Indonesian link allowed here is this page's own alternate.
        $response->assertDontSee('href="' . url('/portfolio') . '"', false);
    }

    public function test_english_titles_are_not_indonesian(): void
    {
        $this->get('/en/services')
            ->assertSee('Software Development, SaaS &amp; AI Services', false)
            ->assertDontSee('<title>Layanan', false);
    }

    public function test_legacy_lang_switch_redirects(): void
    {
        $this->get('/lang/en')->assertRedirect(url('/en'));
        $this->get('/lang/id')->assertRedirect(url('/'));
    }

    public function test_id_prefix_is_not_a_second_address_for_indonesian(): void
    {
        // /id/services would be a duplicate of /services; it must not resolve.
        $this->get('/id/services')->assertNotFound();
    }

    public function test_sitemap_lists_both_languages_with_alternates(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee(url('/services'), false);
        $response->assertSee(url('/en/services'), false);
        $response->assertSee('xmlns:xhtml="http://www.w3.org/1999/xhtml"', false);
        $response->assertSee('<xhtml:link rel="alternate" hreflang="x-default"', false);
    }
}
