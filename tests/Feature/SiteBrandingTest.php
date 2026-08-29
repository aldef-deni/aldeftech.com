<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The Logo and Favicon fields used to be saved and then ignored — the markup
 * hardcoded the bundled files. These lock in that what an editor uploads is
 * what visitors actually get.
 */
class SiteBrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_bundled_logo_is_used_when_nothing_is_uploaded(): void
    {
        $response = $this->get('/');

        $response->assertSee('images/logo.webp', false);
        // Intrinsic size is read off the file, not hardcoded.
        $response->assertSee('width="880" height="341"', false);
    }

    public function test_uploaded_logo_replaces_the_bundled_one(): void
    {
        SiteSetting::set('site_logo', 'images/logo-mark.webp', 'text', 'general');
        SiteSetting::clearCache();

        $response = $this->get('/');

        $response->assertSee('images/logo-mark.webp', false);
        // logo-mark is square, so the header must reserve a square, not 880x341.
        $response->assertSee('width="256" height="256"', false);
        $response->assertDontSee('width="880" height="341"', false);
    }

    public function test_bundled_favicon_set_is_kept_when_none_is_uploaded(): void
    {
        $this->get('/')
            ->assertSee('images/favicon-32.png', false)
            ->assertSee('images/apple-touch-icon.png', false);
    }

    public function test_uploaded_favicon_replaces_the_bundled_set(): void
    {
        SiteSetting::set('site_favicon', 'images/logo-mark.webp', 'text', 'general');
        SiteSetting::clearCache();

        $response = $this->get('/');

        $response->assertSee('rel="icon" href="' . asset('images/logo-mark.webp') . '"', false);
        $response->assertDontSee('images/favicon-32.png', false);
    }

    public function test_blank_favicon_setting_keeps_the_light_generated_set(): void
    {
        // The migration empties the favicon setting rather than repointing it,
        // so this fallback is what stops a 1.3 MB PNG being served as an icon.
        SiteSetting::set('site_favicon', '', 'text', 'general');
        SiteSetting::clearCache();

        $this->get('/')
            ->assertSee('images/favicon-32.png', false)
            ->assertDontSee('logo-square.png', false);
    }

    public function test_clear_cache_endpoint_is_gone(): void
    {
        // It ran optimize:clear for anyone who asked, with no authentication.
        $this->get('/clear-cache')->assertNotFound();
    }

    public function test_robots_no_longer_advertises_the_removed_endpoint(): void
    {
        // robots.txt is public: naming a sensitive path there points at it.
        $this->get('/robots.txt')->assertDontSee('clear-cache', false);
    }
}
