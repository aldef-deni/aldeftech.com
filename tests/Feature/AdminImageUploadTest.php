<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The admin forms take an image directly; there is no media library screen and
 * no path field to copy a filename into.
 */
class AdminImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'super-admin',
            'display_name' => 'Super Admin',
            'description' => 'Full access to all features',
        ]);

        // Permissions are explicit in this app - super-admin has no implicit bypass.
        foreach (['manage-content', 'manage-settings', 'manage-leads'] as $name) {
            $role->permissions()->attach(Permission::create([
                'name' => $name,
                'display_name' => $name,
            ]));
        }

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($role);
    }

    public static function formRoutes(): array
    {
        return [
            'blog' => ['/admin/blog/create'],
            'portfolio' => ['/admin/portfolio/create'],
            'testimonial' => ['/admin/testimonials/create'],
            'ceo profile' => ['/admin/ceo'],
            'site settings' => ['/admin/settings/site'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('formRoutes')]
    public function test_form_offers_an_uploader_not_a_path_field(string $url): void
    {
        $response = $this->actingAs($this->admin)->get($url);

        $response->assertOk();
        $response->assertSee('aldef-uploader');
        $response->assertDontSee('Path Gambar');
        $response->assertDontSee('Unggah lewat menu Media', false);
    }

    public function test_field_advertises_the_configured_limit_not_php_ini(): void
    {
        config(['aldeftech.upload.max_size' => 5120]);

        $response = $this->actingAs($this->admin)->get('/admin/blog/create');

        $response->assertOk();
        $response->assertSee('maks 5 MB');
    }

    public function test_upload_returns_a_stored_path(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->postJson('/admin/uploads', [
            'file' => UploadedFile::fake()->image('sampul.jpg', 800, 500),
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['path', 'url', 'name']);

        $path = $response->json('path');
        $this->assertStringStartsWith('media/', $path);
        Storage::disk('public')->assertExists($path);

        $this->assertDatabaseHas('media', ['path' => $path, 'original_name' => 'sampul.jpg']);
    }

    public function test_upload_rejects_a_format_that_is_not_allowed(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->postJson('/admin/uploads', [
            'file' => UploadedFile::fake()->create('dokumen.pdf', 40, 'application/pdf'),
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
        $this->assertDatabaseCount('media', 0);
    }

    public function test_upload_requires_authentication(): void
    {
        $response = $this->postJson('/admin/uploads', [
            'file' => UploadedFile::fake()->image('x.jpg'),
        ]);

        $this->assertContains($response->status(), [401, 403, 302]);
    }

    public function test_media_library_screen_is_gone(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/media');

        $response->assertNotFound();
    }
}
