<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRbacTest extends TestCase
{
    use RefreshDatabase;

    protected Role $superAdmin;
    protected Role $editor;
    protected Role $salesManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = Role::create([
            'name' => 'super-admin',
            'display_name' => 'Super Admin',
            'description' => 'Full access to all features',
        ]);

        $this->editor = Role::create([
            'name' => 'editor',
            'display_name' => 'Editor',
            'description' => 'Can manage content',
        ]);

        $this->salesManager = Role::create([
            'name' => 'sales-manager',
            'display_name' => 'Sales Manager',
            'description' => 'Can manage leads',
        ]);
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_without_role_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_editor_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->editor);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_sales_manager_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->salesManager);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_super_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->superAdmin);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_editor_cannot_access_user_management(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->editor);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_sales_manager_cannot_access_user_management(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->salesManager);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_user_without_role_cannot_access_user_management(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_user_management(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->superAdmin);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertOk();
    }

    public function test_editor_cannot_access_activity_logs(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->editor);

        $response = $this->actingAs($user)->get('/admin/activity-logs');

        $response->assertStatus(403);
    }

    public function test_sales_manager_cannot_access_activity_logs(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->salesManager);

        $response = $this->actingAs($user)->get('/admin/activity-logs');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_activity_logs(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->superAdmin);

        $response = $this->actingAs($user)->get('/admin/activity-logs');

        $response->assertOk();
    }

    public function test_role_helpers_work_correctly(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->editor);

        $this->assertTrue($user->hasRole('editor'));
        $this->assertFalse($user->hasRole('super-admin'));
        $this->assertTrue($user->hasAnyRole(['editor', 'sales-manager']));
        $this->assertTrue($user->isEditor());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isSalesManager());
    }
}
