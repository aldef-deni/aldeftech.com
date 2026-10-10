<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Production had a GA property number in GOOGLE_TAG_MANAGER_ID. With the
 * admin field empty, the layout loaded a GTM container that does not exist
 * and GA4 received nothing. Only correctly formatted IDs may be emitted.
 */
class AnalyticsBootstrapTest extends TestCase
{
    use RefreshDatabase;

    public function test_malformed_env_ids_are_ignored_and_ga4_loads_directly(): void
    {
        config([
            'aldeftech.analytics.google_tag_manager_id' => '552589062',
            'aldeftech.analytics.google_analytics_id' => 'G-RBWMWTEHKG',
        ]);

        $this->get('/')->assertOk()
            ->assertDontSee('googletagmanager.com/gtm.js', false)
            ->assertDontSee('552589062', false)
            ->assertSee('googletagmanager.com/gtag/js?id=G-RBWMWTEHKG', false)
            ->assertSee("gtag('config', 'G-RBWMWTEHKG')", false);
    }

    public function test_a_malformed_ga_id_loads_nothing(): void
    {
        config([
            'aldeftech.analytics.google_tag_manager_id' => '',
            'aldeftech.analytics.google_analytics_id' => '406773167',
        ]);

        $this->get('/')->assertOk()
            ->assertDontSee('googletagmanager.com', false)
            ->assertSee('"mode":"none"', false);
    }

    public function test_a_valid_gtm_id_from_admin_takes_over(): void
    {
        SiteSetting::set('google_tag_manager_id', 'GTM-NH9MFHSM', 'text', 'analytics');
        SiteSetting::set('google_analytics_id', 'G-RBWMWTEHKG', 'text', 'analytics');

        $this->get('/')->assertOk()
            ->assertSee("'dataLayer','GTM-NH9MFHSM'", false)
            ->assertDontSee('gtag/js?id=', false);
    }
}
