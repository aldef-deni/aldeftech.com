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

    public function test_verification_code_can_be_saved_and_reaches_the_page(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/settings/seo')
            ->assertOk()
            ->assertSee('google_search_console_verification', false);

        $this->actingAs($admin)->put('/admin/settings/seo', [
            'seo_default_title' => 'Aldef Tech',
            'seo_default_description' => 'Deskripsi',
            'seo_default_image' => '',
            'google_search_console_verification' => 'TOKEN-UJI-123',
        ])->assertRedirect();

        SiteSetting::clearCache();

        $this->get('/')->assertSee(
            '<meta name="google-site-verification" content="TOKEN-UJI-123">',
            false
        );
    }
}
