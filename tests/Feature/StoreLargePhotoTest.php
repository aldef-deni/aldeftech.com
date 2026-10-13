<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\StoreImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Aldef Store product photos: up to 50 MB each, resized for the web so the
 * product page never serves a camera original.
 */
class StoreLargePhotoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $role = Role::create(['name' => 'content', 'display_name' => 'Content']);
        $role->permissions()->attach(Permission::create(['name' => 'manage-content', 'display_name' => 'Manage Content']));
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($role);

        // The PHP limits of the test runner must not hide the app's own limit.
        config(['aldeftech.upload.store_image_max_size' => 51200]);
        ini_set('memory_limit', '512M');
    }

    public function test_a_large_product_photo_is_accepted_and_resized_for_the_web(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD without WebP');
        }

        $photo = UploadedFile::fake()->image('kamera.jpg', 3600, 2400)->size(8 * 1024);

        if ($photo->getSize() > max_store_image_upload_bytes()) {
            $this->markTestSkipped('PHP upload limits of this runner are below 8 MB');
        }

        $data = $this->actingAs($this->admin)
            ->post('/admin/uploads?kind=store-image', ['file' => $photo], ['Accept' => 'application/json'])
            ->assertOk()->json();

        $this->assertStringEndsWith('.webp', $data['path']);
        Storage::disk('public')->assertExists($data['path']);
        [$width, $height] = getimagesize(Storage::disk('public')->path($data['path']));
        $this->assertSame([2400, 1600], [$width, $height]);

        // Only the web copy is kept.
        $this->assertCount(1, Storage::disk('public')->files('media'));
    }

    public function test_the_store_photo_limit_does_not_change_other_uploads(): void
    {
        $big = UploadedFile::fake()->image('besar.jpg', 100, 100)->size(6 * 1024);

        // Everywhere else in the admin, images stay at 5 MB.
        $this->actingAs($this->admin)
            ->post('/admin/uploads', ['file' => $big], ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function test_store_photo_upload_refuses_what_is_not_a_photo(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/uploads?kind=store-image', ['file' => UploadedFile::fake()->createWithContent('foto.jpg', 'bukan foto')], ['Accept' => 'application/json'])
            ->assertStatus(422);
        $this->actingAs($this->admin)
            ->post('/admin/uploads?kind=store-image', ['file' => UploadedFile::fake()->create('foto.gif', 10, 'image/gif')], ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function test_small_photos_are_left_untouched(): void
    {
        $path = Storage::disk('public')->path('media/kecil.png');
        @mkdir(dirname($path), 0777, true);
        $image = imagecreatetruecolor(800, 600);
        imagepng($image, $path);
        imagedestroy($image);

        $this->assertSame($path, app(StoreImageOptimizer::class)->optimize($path));
        $this->assertFileExists($path);
    }
}
