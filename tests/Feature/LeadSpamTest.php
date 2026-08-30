<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\SpamScorer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Spam is filed away, never rejected.
 *
 * A dropped submission is invisible to everyone including the sender, so a
 * false positive would silently cost a sale. Flagging keeps the mistake
 * recoverable with one click.
 */
class LeadSpamTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['name' => 'super-admin'], ['display_name' => 'Super Admin']);
        foreach (['manage-content', 'manage-settings', 'manage-leads'] as $name) {
            $permission = Permission::firstOrCreate(['name' => $name], ['display_name' => $name]);
            if (! $role->permissions()->where('permissions.id', $permission->id)->exists()) {
                $role->permissions()->attach($permission);
            }
        }
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }

    private function submit(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post('/contact', array_merge([
            'name' => 'Budi Santoso',
            'email' => 'budi@perusahaan.co.id',
            'message' => 'Halo, kami butuh sistem inventory untuk gudang kami di Bogor.',
            'form_started_at' => encrypt(now()->subSeconds(40)->timestamp),
        ], $overrides));
    }

    public function test_a_real_enquiry_is_not_flagged(): void
    {
        $this->submit()->assertRedirect();

        $lead = Lead::firstOrFail();
        $this->assertFalse($lead->is_spam);
        $this->assertSame(0, $lead->spam_score);
    }

    public function test_honeypot_alone_is_decisive(): void
    {
        $this->submit(['website_url' => 'https://spam.example'])->assertRedirect();

        $lead = Lead::firstOrFail();
        $this->assertTrue($lead->is_spam);
        // Still stored, still recoverable — never silently dropped.
        $this->assertDatabaseCount('leads', 1);
        $this->assertNotEmpty($lead->spam_reasons);
    }

    public function test_instant_submission_is_flagged(): void
    {
        $this->submit(['form_started_at' => encrypt(now()->timestamp)])->assertRedirect();

        $this->assertTrue(Lead::firstOrFail()->is_spam);
    }

    public function test_link_stuffed_message_is_flagged(): void
    {
        $this->submit([
            'message' => 'Best SEO services http://a.example and http://b.example order now',
        ])->assertRedirect();

        $this->assertTrue(Lead::firstOrFail()->is_spam);
    }

    public function test_a_tampered_timing_field_does_not_punish_the_visitor(): void
    {
        // An expired session or a rewritten field must not flag a real person.
        $this->submit(['form_started_at' => 'bukan-ciphertext'])->assertRedirect();

        $this->assertFalse(Lead::firstOrFail()->is_spam);
    }

    public function test_spam_is_hidden_from_the_working_list_and_the_bell(): void
    {
        // project_type appears in the table but not in the navbar bell dropdown,
        // which lists every unread lead on every admin page — asserting on the
        // name or email would be satisfied by the dropdown instead of the list.
        Lead::create([
            'name' => 'Robot', 'email' => 'bot@x.test', 'message' => 'x',
            'project_type' => 'PENANDA-SPAM',
            'status' => 'new', 'source' => 'website', 'is_spam' => true, 'spam_score' => 100,
        ]);
        Lead::create([
            'name' => 'Manusia', 'email' => 'orang@x.test', 'message' => 'x',
            'project_type' => 'PENANDA-ASLI',
            'status' => 'new', 'source' => 'website',
        ]);

        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/leads')
            ->assertSee('PENANDA-ASLI', false)
            ->assertDontSee('PENANDA-SPAM', false);

        $this->actingAs($admin)->get('/admin/leads?spam=1')
            ->assertSee('PENANDA-SPAM', false)
            ->assertDontSee('PENANDA-ASLI', false);

        $this->assertSame(1, Lead::unread()->count());
    }

    public function test_an_editor_can_overrule_the_filter_in_both_directions(): void
    {
        $lead = Lead::create([
            'name' => 'Salah Tandai', 'email' => 'klien@nyata.co.id', 'message' => 'x',
            'status' => 'new', 'source' => 'website', 'is_spam' => true, 'spam_score' => 60,
        ]);

        $admin = $this->admin();

        $this->actingAs($admin)->put("/admin/leads/{$lead->id}/spam")->assertRedirect();
        $this->assertFalse($lead->fresh()->is_spam);

        $this->actingAs($admin)->put("/admin/leads/{$lead->id}/spam")->assertRedirect();
        $this->assertTrue($lead->fresh()->is_spam);
    }

    public function test_scorer_explains_every_flag(): void
    {
        $result = (new SpamScorer)->score(
            ['name' => 'X', 'email' => 'a@mailinator.com', 'message' => 'buy backlink http://x.test'],
            1,
            false
        );

        $this->assertGreaterThanOrEqual(SpamScorer::THRESHOLD, $result['score']);
        // Every point added must come with a reason an editor can read.
        $this->assertNotEmpty($result['reasons']);
        foreach ($result['reasons'] as $reason) {
            $this->assertIsString($reason);
            $this->assertNotSame('', trim($reason));
        }
    }
}
