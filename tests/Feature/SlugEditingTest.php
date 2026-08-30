<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Permission;
use App\Models\Portfolio;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Slugs are editable and stable.
 *
 * Portfolio used to generate a slug only on create, so renaming a project left
 * its URL stuck on the old one forever with no way to fix it. Blog did the
 * opposite — it regenerated on every save, silently changing a published URL.
 */
class SlugEditingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'super-admin', 'display_name' => 'Super Admin']);
        foreach (['manage-content', 'manage-settings', 'manage-leads'] as $n) {
            $role->permissions()->attach(Permission::create(['name' => $n, 'display_name' => $n]));
        }
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }

    private function portfolio(array $attributes = []): Portfolio
    {
        return Portfolio::create(array_merge([
            'title' => 'Judul Lama',
            'slug' => 'slug-lama',
            'short_description' => 'Ringkasan.',
            'is_published' => true,
            'published_at' => now(),
        ], $attributes));
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Judul Lama',
            'short_description' => 'Ringkasan.',
            'sort_order' => 0,
            // The controller reads this as a boolean, so omitting it unpublishes.
            'is_published' => 1,
        ], $overrides);
    }

    public function test_portfolio_slug_can_be_corrected(): void
    {
        $portfolio = $this->portfolio();

        $this->actingAs($this->admin())
            ->put("/admin/portfolio/{$portfolio->id}", $this->payload([
                'title' => 'Aplikasi Absensi Aldef Tech',
                'slug' => 'absensi-aldef-tech',
            ]))
            ->assertRedirect();

        $this->assertSame('absensi-aldef-tech', $portfolio->fresh()->slug);
        $this->get('/portfolio/absensi-aldef-tech')->assertOk();
    }

    public function test_renaming_without_touching_the_slug_keeps_the_url(): void
    {
        $portfolio = $this->portfolio();

        $this->actingAs($this->admin())
            ->put("/admin/portfolio/{$portfolio->id}", $this->payload([
                'title' => 'Judul Yang Sudah Diganti',
                'slug' => 'slug-lama',
            ]))
            ->assertRedirect();

        // An indexed URL must not move just because the heading was reworded.
        $this->assertSame('slug-lama', $portfolio->fresh()->slug);
    }

    public function test_blank_slug_falls_back_to_the_title(): void
    {
        $portfolio = $this->portfolio(['slug' => 'apa-saja']);

        $this->actingAs($this->admin())
            ->put("/admin/portfolio/{$portfolio->id}", $this->payload([
                'title' => 'Sistem POS Multi Cabang',
                'slug' => '',
            ]))
            ->assertRedirect();

        $this->assertSame('sistem-pos-multi-cabang', $portfolio->fresh()->slug);
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        $this->portfolio(['slug' => 'sudah-dipakai', 'title' => 'Satu']);
        $second = $this->portfolio(['slug' => 'punya-sendiri', 'title' => 'Dua']);

        $this->actingAs($this->admin())
            ->put("/admin/portfolio/{$second->id}", $this->payload([
                'title' => 'Dua',
                'slug' => 'sudah-dipakai',
            ]))
            ->assertSessionHasErrors('slug');

        $this->assertSame('punya-sendiri', $second->fresh()->slug);
    }

    public function test_malformed_slug_is_rejected(): void
    {
        $portfolio = $this->portfolio();

        $this->actingAs($this->admin())
            ->put("/admin/portfolio/{$portfolio->id}", $this->payload([
                'title' => 'Judul',
                'slug' => 'Bukan Slug/Valid',
            ]))
            ->assertSessionHasErrors('slug');
    }

    public function test_blog_slug_survives_a_title_rewrite(): void
    {
        $post = BlogPost::create([
            'title' => 'Judul Awal',
            'slug' => 'judul-awal',
            'content' => 'Isi artikel.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->actingAs($this->admin())
            ->put("/admin/blog/{$post->id}", [
                'title' => 'Judul Yang Diperbaiki Setelah Terbit',
                'slug' => 'judul-awal',
                'content' => 'Isi artikel.',
                'status' => 'published',
            ])
            ->assertRedirect();

        $this->assertSame('judul-awal', $post->fresh()->slug);
    }
}
