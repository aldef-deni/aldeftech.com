<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Media;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Client logos are social proof, so they may only appear where an editor put
 * them: the homepage marquee, the About grid, or both.
 */
class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        $role->permissions()->attach(Permission::create(['name' => 'manage-content', 'display_name' => 'Content']));

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($role);
    }

    /** A logo path that passes the rule set: uploaded through the shared uploader. */
    private function uploadedLogo(): string
    {
        return Media::create([
            'filename' => 'klien.jpg',
            'original_name' => 'klien.jpg',
            'mime_type' => 'image/png',
            'size' => 1024,
            'disk' => 'public',
            'path' => 'media/klien.jpg',
        ])->path;
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Nusantara Logistik',
            'logo' => $this->uploadedLogo(),
            'website_url' => 'https://nusantaralogistik.co.id',
            'is_published' => 1,
            'show_home' => 1,
            'show_about' => 1,
            'is_featured' => 0,
            'sort_order' => 5,
        ], $overrides);
    }

    private function createClient(array $overrides = []): Client
    {
        $data = $this->payload($overrides);
        $data = array_map(fn ($value) => $value === 1 ? true : ($value === 0 ? false : $value), $data);

        return Client::create($data);
    }

    public function test_guests_cannot_reach_the_admin_screens(): void
    {
        $client = $this->createClient();

        $this->get('/admin/clients')->assertRedirect('/admin/login');
        $this->get('/admin/clients/create')->assertRedirect('/admin/login');
        $this->post('/admin/clients', $this->payload())->assertRedirect('/admin/login');
        $this->put("/admin/clients/{$client->id}", $this->payload())->assertRedirect('/admin/login');
        $this->post("/admin/clients/{$client->id}/toggle-active")->assertRedirect('/admin/login');
        $this->delete("/admin/clients/{$client->id}")->assertRedirect('/admin/login');

        $this->assertDatabaseCount('clients', 1);
    }

    public function test_a_user_without_the_permission_is_forbidden(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin/clients')->assertForbidden();
    }

    public function test_the_list_opens_for_an_editor(): void
    {
        $this->createClient();

        $this->actingAs($this->admin)->get('/admin/clients')
            ->assertOk()
            ->assertSee('Klien Aldef Tech')
            ->assertSee('Kelola logo dan identitas klien')
            ->assertSee('Nusantara Logistik')
            ->assertSee('Tambah Klien');
    }

    public function test_the_empty_state_is_shown_without_clients(): void
    {
        $this->actingAs($this->admin)->get('/admin/clients')
            ->assertOk()
            ->assertSee('Belum ada Klien Aldef Tech.');
    }

    public function test_an_editor_can_create_a_client(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', $this->payload());

        $response->assertRedirect(route('admin.clients.index'));
        $response->assertSessionHas('success');

        $client = Client::firstOrFail();
        $this->assertSame('Nusantara Logistik', $client->name);
        $this->assertSame('media/klien.jpg', $client->logo);
        $this->assertTrue($client->is_published);
        $this->assertTrue($client->show_home);
        $this->assertTrue($client->show_about);
        $this->assertSame(5, $client->sort_order);
    }

    public function test_a_website_without_a_scheme_is_completed(): void
    {
        $this->actingAs($this->admin)->post('/admin/clients', $this->payload([
            'website_url' => 'nusantaralogistik.co.id',
        ]));

        $this->assertSame('https://nusantaralogistik.co.id', Client::firstOrFail()->website_url);
    }

    public function test_the_logo_is_validated(): void
    {
        $missing = $this->actingAs($this->admin)->post('/admin/clients', $this->payload(['logo' => '']));
        $missing->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('clients', 0);

        // A path nothing was uploaded to is not a logo.
        $invented = $this->actingAs($this->admin)->post('/admin/clients', $this->payload(['logo' => 'media/tidak-ada.jpg']));
        $invented->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('clients', 0);

        // No SVG sanitizer exists in this project, so an SVG logo is refused.
        Media::create([
            'filename' => 'klien.svg', 'original_name' => 'klien.svg',
            'mime_type' => 'image/svg+xml', 'size' => 512,
            'disk' => 'public', 'path' => 'media/klien.svg',
        ]);
        $svg = $this->actingAs($this->admin)->post('/admin/clients', $this->payload(['logo' => 'media/klien.svg']));
        $svg->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('clients', 0);
    }

    public function test_incomplete_input_is_rejected(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'name' => '',
            'website_url' => 'bukan url',
            'sort_order' => -3,
        ]);

        $response->assertSessionHasErrors(['name', 'logo', 'website_url', 'sort_order']);
        $this->assertDatabaseCount('clients', 0);
    }

    public function test_an_editor_can_update_a_client(): void
    {
        $client = $this->createClient(['show_home' => 1, 'show_about' => 1]);

        $this->actingAs($this->admin)->get("/admin/clients/{$client->id}/edit")->assertOk();

        $this->actingAs($this->admin)->put("/admin/clients/{$client->id}", $this->payload([
            'name' => 'Nusantara Logistik Group',
            'logo' => '',
            'website_url' => '',
            'show_home' => 0,
            'is_featured' => 1,
        ]))->assertRedirect(route('admin.clients.index'));

        $client->refresh();
        $this->assertSame('Nusantara Logistik Group', $client->name);
        $this->assertSame('media/klien.jpg', $client->logo, 'An untouched logo must survive an update.');
        $this->assertNull($client->website_url);
        $this->assertFalse($client->show_home);
        $this->assertTrue($client->is_featured);
    }

    public function test_an_editor_can_activate_and_deactivate(): void
    {
        $client = $this->createClient();

        $this->actingAs($this->admin)->post("/admin/clients/{$client->id}/toggle-active");
        $this->assertFalse($client->refresh()->is_published);
        $this->get('/')->assertDontSee('Nusantara Logistik');
        $this->get('/about')->assertDontSee('Nusantara Logistik');

        $this->actingAs($this->admin)->post("/admin/clients/{$client->id}/toggle-active");
        $this->assertTrue($client->refresh()->is_published);
        $this->get('/')->assertSee('Nusantara Logistik');
    }

    public function test_placement_flags_decide_where_a_logo_appears(): void
    {
        $both = $this->createClient(['name' => 'Klien Dua Halaman']);
        $homeOnly = $this->createClient(['name' => 'Klien Beranda', 'show_about' => 0, 'sort_order' => 1]);
        $aboutOnly = $this->createClient(['name' => 'Klien Tentang', 'show_home' => 0, 'sort_order' => 2]);
        $inactive = $this->createClient(['name' => 'Klien Nonaktif', 'is_published' => 0]);

        $home = $this->get('/');
        $home->assertOk();
        $home->assertSee('Klien Dua Halaman');
        $home->assertSee('Klien Beranda');
        $home->assertDontSee('Klien Tentang');
        $home->assertDontSee('Klien Nonaktif');
        $home->assertSee('Dipercaya untuk Membangun');

        $about = $this->get('/about');
        $about->assertOk();
        $about->assertSee('Klien Dua Halaman');
        $about->assertSee('Klien Tentang');
        $about->assertDontSee('Klien Beranda');
        $about->assertDontSee('Klien Nonaktif');
        $about->assertSee('Kepercayaan yang Tumbuh dari');

        $english = $this->get('/en');
        $english->assertOk();
        $english->assertSee('Trusted to Build');
    }

    public function test_sort_order_and_featured_decide_the_sequence(): void
    {
        $this->createClient(['name' => 'Klien Ketiga', 'sort_order' => 30]);
        $this->createClient(['name' => 'Klien Unggulan', 'sort_order' => 90, 'is_featured' => 1]);
        $this->createClient(['name' => 'Klien Pertama', 'sort_order' => 10]);

        $html = $this->get('/')->assertOk()->getContent();

        $featured = strpos($html, 'Klien Unggulan');
        $first = strpos($html, 'Klien Pertama');
        $third = strpos($html, 'Klien Ketiga');

        $this->assertNotFalse($featured);
        $this->assertNotFalse($first);
        $this->assertNotFalse($third);
        $this->assertTrue($featured < $first, 'Featured leads the marquee.');
        $this->assertTrue($first < $third, 'Then the editor order applies.');
    }

    public function test_a_logo_is_only_clickable_when_there_is_a_website(): void
    {
        $linked = $this->createClient(['name' => 'Klien Bertautan', 'sort_order' => 1]);
        $plain = $this->createClient(['name' => 'Klien Tanpa Tautan', 'website_url' => '', 'sort_order' => 2]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('href="https://nusantaralogistik.co.id"', $html);
        $this->assertStringNotContainsString('<a  class="client-logo"', $html);
        $this->assertStringNotContainsString('href=""', $html);

        $this->assertNull($plain->website());
        $this->assertSame('https://nusantaralogistik.co.id', $linked->website());
    }

    public function test_an_injected_name_is_escaped(): void
    {
        $this->createClient(['name' => '<script>alert(1)</script>']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('alt="&lt;script&gt;alert(1)&lt;/script&gt;"', $html);
    }

    public function test_deleting_a_client_soft_deletes_it(): void
    {
        $client = $this->createClient(['name' => 'Klien Dihapus']);

        $this->actingAs($this->admin)
            ->delete("/admin/clients/{$client->id}")
            ->assertRedirect(route('admin.clients.index'));

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
        $this->get('/')->assertOk()->assertDontSee('Klien Dihapus');
        $this->get('/about')->assertOk()->assertDontSee('Klien Dihapus');
        $this->actingAs($this->admin)->get('/admin/clients')->assertOk()->assertSee('Belum ada Klien Aldef Tech.');
    }

    public function test_an_uploaded_logo_is_accepted_and_a_non_image_is_not(): void
    {
        Storage::fake('public');

        $good = $this->actingAs($this->admin)->postJson('/admin/uploads', [
            'file' => UploadedFile::fake()->image('klien.png', 320, 120),
        ]);
        $good->assertOk();

        $bad = $this->actingAs($this->admin)->postJson('/admin/uploads', [
            'file' => UploadedFile::fake()->create('dokumen.pdf', 30, 'application/pdf'),
        ]);
        $bad->assertStatus(422);

        $this->actingAs($this->admin)->post('/admin/clients', $this->payload(['logo' => $good->json('path')]));
        $this->assertSame($good->json('path'), Client::firstOrFail()->logo);
    }

    public function test_the_sections_are_hidden_without_any_client(): void
    {
        $this->assertDatabaseCount('clients', 0);

        $this->get('/')->assertOk()->assertDontSee('id="klien"', false)->assertDontSee('client-marquee-track');
        $this->get('/about')->assertOk()->assertDontSee('id="klien"', false)->assertDontSee('class="client-grid');
    }
}
