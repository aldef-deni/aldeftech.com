<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['name' => 'super-admin'], ['display_name' => 'Super Admin']);
        foreach (['manage-content', 'manage-settings', 'manage-leads', 'manage-users'] as $name) {
            $permission = Permission::firstOrCreate(['name' => $name], ['display_name' => $name]);
            if (! $role->permissions()->where('permissions.id', $permission->id)->exists()) {
                $role->permissions()->attach($permission);
            }
        }
        $user = User::factory()->create(['name' => 'Admin Aldef Tech']);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_edit_form_offers_an_uploader(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get("/admin/users/{$admin->id}/edit")
            ->assertOk()
            ->assertSee('Foto Profil', false)
            ->assertSee('aldef-uploader', false);
    }

    public function test_avatar_is_saved_and_rendered_instead_of_initials(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => 'Admin Aldef Tech',
            'email' => $admin->email,
            'role' => 'super-admin',
            'avatar' => 'media/foto-admin.webp',
        ])->assertRedirect();

        $this->assertSame('media/foto-admin.webp', $admin->fresh()->avatar);

        // The navbar renders on every admin page, so this covers it too.
        $this->actingAs($admin->fresh())->get('/admin/users')
            ->assertSee('media/foto-admin.webp', false);
    }

    public function test_initials_are_the_fallback_when_no_avatar_is_set(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/users')
            ->assertOk()
            // A stock silhouette reads as a broken image; initials read as a person.
            ->assertSee('avatar-initial', false)
            ->assertSee('AA', false);
    }

    public function test_clearing_the_field_returns_to_initials(): void
    {
        $admin = $this->admin();
        $admin->forceFill(['avatar' => 'media/lama.webp'])->save();

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => 'Admin Aldef Tech',
            'email' => $admin->email,
            'role' => 'super-admin',
            'avatar' => '',
        ])->assertRedirect();

        $this->assertNull($admin->fresh()->avatar);
    }

    public function test_password_is_untouched_when_only_the_avatar_changes(): void
    {
        $admin = $this->admin();
        $before = $admin->password;

        $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => 'Admin Aldef Tech',
            'email' => $admin->email,
            'role' => 'super-admin',
            'avatar' => 'media/baru.webp',
        ])->assertRedirect();

        $this->assertSame($before, $admin->fresh()->password);
    }
}
