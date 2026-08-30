<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The bell counts leads nobody has opened yet.
 *
 * It used to count status = 'new', which meant the only way to clear the badge
 * was to move a lead to 'contacted' — falsifying the pipeline to silence a
 * notification. read_at keeps "seen" and "sales stage" independent.
 */
class LeadNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A page with no lead list of its own, so an assertion about the bell
     * dropdown cannot be satisfied by the page body instead — and one that
     * manage-content alone can open, so the permission test can load it too.
     */
    private const NEUTRAL_PAGE = '/admin/portfolio';

    private function admin(array $permissions = ['manage-content', 'manage-settings', 'manage-leads']): User
    {
        // firstOrCreate so a test may call this more than once without tripping
        // the unique index on roles.name.
        $role = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['display_name' => 'Super Admin']
        );

        foreach ($permissions as $name) {
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => $name]
            );

            if (! $role->permissions()->where('permissions.id', $permission->id)->exists()) {
                $role->permissions()->attach($permission);
            }
        }
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }

    private function lead(array $attributes = []): Lead
    {
        return Lead::create(array_merge([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'message' => 'Butuh sistem inventory.',
            'status' => 'new',
            'source' => 'website',
        ], $attributes));
    }

    public function test_bell_shows_a_count_of_unread_leads(): void
    {
        $this->lead(['name' => 'Satu']);
        $this->lead(['name' => 'Dua']);

        $this->actingAs($this->admin())->get(self::NEUTRAL_PAGE)
            ->assertOk()
            ->assertSee('Lead Baru', false)
            ->assertSee('2 baru', false)
            ->assertSee('Satu', false);
    }

    public function test_dropdown_lists_at_most_five(): void
    {
        foreach (range(1, 8) as $i) {
            $this->lead(['name' => "Lead {$i}"]);
        }

        $response = $this->actingAs($this->admin())->get(self::NEUTRAL_PAGE);

        $response->assertSee('8 baru', false);
        // Newest five only; the oldest three stay behind "Lihat semua lead".
        $response->assertSee('Lead 8', false);
        $response->assertSee('Lead 4', false);
        $response->assertDontSee('Lead 3', false);
    }

    public function test_opening_a_lead_marks_it_read(): void
    {
        $lead = $this->lead();

        $this->actingAs($this->admin())->get("/admin/leads/{$lead->id}")->assertOk();

        $this->assertNotNull($lead->fresh()->read_at);
        // Reading it must not disturb where it sits in the pipeline.
        $this->assertSame('new', $lead->fresh()->status);
    }

    public function test_mark_all_read_clears_the_badge(): void
    {
        $this->lead(['name' => 'Satu']);
        $this->lead(['name' => 'Dua']);

        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/leads/read-all')->assertRedirect();

        $this->assertSame(0, Lead::unread()->count());
        $this->actingAs($admin)->get(self::NEUTRAL_PAGE)->assertDontSee('2 baru', false);
    }

    public function test_a_lead_can_be_deleted_from_the_dropdown(): void
    {
        $lead = $this->lead();

        $this->actingAs($this->admin())->delete("/admin/leads/{$lead->id}")->assertRedirect();

        $this->assertSoftDeleted('leads', ['id' => $lead->id]);
        $this->assertSame(0, Lead::unread()->count());
    }

    public function test_bell_is_hidden_from_users_who_cannot_open_leads(): void
    {
        $this->lead();

        // An editor without manage-leads would only get a 403 from the link.
        $editor = $this->admin(['manage-content']);

        $this->actingAs($editor)->get(self::NEUTRAL_PAGE)
            ->assertOk()
            ->assertDontSee('Lead Baru', false);
    }
}
