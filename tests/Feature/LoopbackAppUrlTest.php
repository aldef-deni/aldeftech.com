<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Production once ran with APP_URL=http://localhost:8000 copied from
 * .env.example, and every canonical tag, the sitemap, robots.txt and the
 * JSON-LD @id values pointed Google at localhost. A loopback APP_URL must
 * never reach those signals when the request came in on the real host.
 */
class LoopbackAppUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'http://localhost:8000']);
    }

    public function test_page_signals_use_the_request_host(): void
    {
        $html = $this->get('https://aldeftech.com/faq')->assertOk()->getContent();

        $this->assertStringContainsString('<link rel="canonical" href="https://aldeftech.com/faq">', $html);
        $this->assertStringContainsString('"@id":"https://aldeftech.com#organization"', $html);
        $this->assertStringNotContainsString('localhost', $html);
    }

    public function test_sitemap_and_robots_use_the_request_host(): void
    {
        $sitemap = $this->get('https://aldeftech.com/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('<loc>https://aldeftech.com/</loc>', $sitemap);
        $this->assertStringNotContainsString('localhost', $sitemap);

        $this->get('https://aldeftech.com/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: https://aldeftech.com/sitemap.xml', false);
    }

    public function test_a_real_app_url_is_left_alone(): void
    {
        config(['app.url' => 'https://aldeftech.com']);

        $this->get('https://aldeftech.com/faq')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://aldeftech.com/faq">', false);
    }
}
