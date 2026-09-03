<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'super-admin', 'display_name' => 'Super Admin']);
        foreach (['manage-content', 'manage-settings', 'manage-leads'] as $name) {
            $role->permissions()->attach(Permission::create(['name' => $name, 'display_name' => $name]));
        }
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }

    /**
     * The field lives on the Analytics screen, next to GTM and Meta Pixel —
     * one home for every third-party verification code.
     */
    public function test_verification_code_can_be_saved_and_reaches_the_page(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/settings/analytics')
            ->assertOk()
            ->assertSee('google_search_console_verification', false);

        // And nowhere else: two fields writing one key is a trap.
        $this->actingAs($admin)->get('/admin/settings/seo')
            ->assertOk()
            ->assertDontSee('google_search_console_verification', false);

        $this->actingAs($admin)->put('/admin/settings/analytics', [
            'google_analytics_id' => 'G-RBWMWTEHKG',
            'google_tag_manager_id' => '',
            'meta_pixel_id' => '',
            'google_search_console_verification' => 'TOKEN-UJI-123',
        ])->assertRedirect();

        SiteSetting::clearCache();

        $this->get('/')->assertSee(
            '<meta name="google-site-verification" content="TOKEN-UJI-123">',
            false
        );
    }
}
