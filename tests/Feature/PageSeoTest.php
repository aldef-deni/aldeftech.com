<?php

namespace Tests\Feature;

use App\Models\PageSeo;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Per-page meta an editor can change without a deploy.
 *
 * The whole point is that it layers on top of what already exists: a blank
 * override must fall through, never blank the tag.
 */
class PageSeoTest extends TestCase
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

    public function test_override_wins_over_the_page_default(): void
    {
        PageSeo::create([
            'route_name' => 'services',
            'locale' => 'id',
            'meta_title' => 'Judul Dari Dashboard',
            'meta_description' => 'Deskripsi dari dashboard.',
        ]);

        $this->get('/services')
            ->assertSee('<title>Judul Dari Dashboard</title>', false)
            ->assertSee('content="Deskripsi dari dashboard."', false);
    }

    public function test_blank_override_falls_through_instead_of_blanking(): void
    {
        PageSeo::create(['route_name' => 'services', 'locale' => 'id', 'meta_title' => null]);

        // The view's own lang-file title must survive an empty row.
        $this->get('/services')->assertSee('Layanan Software Development', false);
    }

    public function test_override_is_per_language(): void
    {
        PageSeo::create([
            'route_name' => 'services',
            'locale' => 'en',
            'meta_title' => 'English Override Title',
        ]);

        $this->get('/en/services')->assertSee('<title>English Override Title</title>', false);
        $this->get('/services')->assertDontSee('English Override Title', false);
    }

    public function test_noindex_emits_robots_tag_and_leaves_the_sitemap(): void
    {
        PageSeo::create(['route_name' => 'faq', 'locale' => 'id', 'noindex' => true]);

        $this->get('/faq')->assertSee('name="robots" content="noindex, nofollow"', false);

        // Advertising a page in the sitemap while telling Google not to index it
        // is a contradiction Search Console flags.
        $this->get('/sitemap.xml')->assertDontSee(url('/faq') . '</loc>', false);
    }

    public function test_admin_can_save_meta_for_both_languages(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/settings/page-seo')->assertOk()->assertSee('Beranda');
        $this->actingAs($admin)->get('/admin/settings/page-seo/services')->assertOk();

        $this->actingAs($admin)->put('/admin/settings/page-seo/services', [
            'seo' => [
                'id' => ['meta_title' => 'Judul ID', 'meta_description' => 'Deskripsi ID'],
                'en' => ['meta_title' => 'Title EN', 'meta_description' => 'Description EN'],
            ],
        ])->assertRedirect(route('admin.settings.page-seo.index'));

        $this->assertDatabaseHas('page_seo', ['route_name' => 'services', 'locale' => 'id', 'meta_title' => 'Judul ID']);
        $this->assertDatabaseHas('page_seo', ['route_name' => 'services', 'locale' => 'en', 'meta_title' => 'Title EN']);

        PageSeo::clearCache();
        $this->get('/services')->assertSee('<title>Judul ID</title>', false);
        $this->get('/en/services')->assertSee('<title>Title EN</title>', false);
    }

    public function test_unknown_page_is_rejected(): void
    {
        $this->actingAs($this->admin())->get('/admin/settings/page-seo/tidak-ada')->assertNotFound();
    }
}
