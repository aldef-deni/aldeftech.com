<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Only projects that actually have a demo say anything about one.
 */
class PortfolioDemoTest extends TestCase
{
    use RefreshDatabase;

    private function portfolio(array $attributes = []): Portfolio
    {
        return Portfolio::create(array_merge([
            'title' => 'Sistem Inventory',
            'slug' => 'sistem-inventory',
            'short_description' => 'Ringkasan singkat proyek.',
            'is_published' => true,
            'published_at' => now(),
        ], $attributes));
    }

    public function test_project_without_a_demo_says_nothing_about_one(): void
    {
        $this->portfolio();

        $this->get('/portfolio/sistem-inventory')
            ->assertOk()
            ->assertDontSee('Coba Demo', false)
            ->assertDontSee('demo-title-', false);

        $this->get('/portfolio')
            ->assertOk()
            ->assertDontSee('Demo tersedia', false);
    }

    public function test_demo_url_alone_is_what_turns_the_feature_on(): void
    {
        $this->portfolio(['demo_url' => 'https://demo.aldeftech.com/inventory']);

        $this->get('/portfolio/sistem-inventory')
            ->assertOk()
            ->assertSee('Coba Demo', false)
            ->assertSee('https://demo.aldeftech.com/inventory', false)
            // No credentials given, so it must say the demo opens without login.
            ->assertSee('tanpa login', false);

        $this->get('/portfolio')->assertSee('Demo tersedia', false);
    }

    public function test_credentials_are_shown_when_provided(): void
    {
        $this->portfolio([
            'demo_url' => 'https://demo.aldeftech.com/inventory',
            'demo_username' => 'demo',
            'demo_password' => 'rahasia123',
            'demo_note' => 'Data direset tiap 24 jam.',
        ]);

        $this->get('/portfolio/sistem-inventory')
            ->assertSee('demo', false)
            ->assertSee('rahasia123', false)
            ->assertSee('Data direset tiap 24 jam.', false)
            ->assertDontSee('tanpa login', false);
    }

    public function test_modal_is_translated(): void
    {
        $this->portfolio(['demo_url' => 'https://demo.aldeftech.com/inventory']);

        $this->get('/en/portfolio/sistem-inventory')
            ->assertOk()
            ->assertSee('Try the demo', false)
            ->assertDontSee('Coba Demo', false);
    }

    public function test_demo_fields_save_from_the_admin_form(): void
    {
        $portfolio = $this->portfolio();

        $this->assertFalse($portfolio->hasDemo());

        $portfolio->update(['demo_url' => 'https://demo.aldeftech.com/x']);

        $this->assertTrue($portfolio->fresh()->hasDemo());
    }
}
