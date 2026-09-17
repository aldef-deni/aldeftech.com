<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Testimonials are attributions to real people, so the publication rules are
 * strict: only published rows with a past date reach the public page, and the
 * English page never shows an untranslated (Indonesian) quote.
 */
class TestimonialTest extends TestCase
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

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'client_name' => 'Rani Kusuma',
            'company' => 'Nusantara Logistik',
            'position' => 'Direktur Operasional',
            'testimonial' => 'Tim AldefTech memetakan alur kerja kami sebelum menulis satu baris kode.',
            'rating' => 5,
            'is_published' => 1,
            'is_featured' => 1,
            'sort_order' => 3,
        ], $overrides);
    }

    public function test_guests_cannot_reach_the_admin_screens(): void
    {
        $testimonial = Testimonial::create($this->payload());

        $this->get('/admin/testimonials')->assertRedirect('/admin/login');
        $this->get('/admin/testimonials/create')->assertRedirect('/admin/login');
        $this->post('/admin/testimonials', $this->payload())->assertRedirect('/admin/login');
        $this->put("/admin/testimonials/{$testimonial->id}", $this->payload())->assertRedirect('/admin/login');
        $this->post("/admin/testimonials/{$testimonial->id}/toggle-published")->assertRedirect('/admin/login');
        $this->delete("/admin/testimonials/{$testimonial->id}")->assertRedirect('/admin/login');

        $this->assertDatabaseCount('testimonials', 1);
    }

    public function test_a_user_without_the_permission_is_forbidden(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin/testimonials')->assertForbidden();
    }

    public function test_the_list_opens_for_an_editor(): void
    {
        Testimonial::create($this->payload());

        $this->actingAs($this->admin)->get('/admin/testimonials')
            ->assertOk()
            ->assertSee('Testimoni')
            ->assertSee('Rani Kusuma')
            ->assertSee('Tambah Testimoni');
    }

    public function test_an_editor_can_create_a_testimonial(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/testimonials', $this->payload());

        $response->assertRedirect(route('admin.testimonials.index'));
        $response->assertSessionHas('success');

        $testimonial = Testimonial::firstOrFail();
        $this->assertSame('Rani Kusuma', $testimonial->client_name);
        $this->assertSame(5, $testimonial->rating);
        $this->assertTrue($testimonial->is_featured);
        $this->assertTrue($testimonial->is_published);
        $this->assertNotNull($testimonial->published_at);
    }

    public function test_validation_rejects_incomplete_input(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/testimonials', [
            'client_name' => '',
            'testimonial' => '',
            'rating' => 9,
            'sort_order' => -4,
        ]);

        $response->assertSessionHasErrors(['client_name', 'testimonial', 'rating', 'sort_order']);
        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_a_draft_is_saved_without_a_publication_date(): void
    {
        $this->actingAs($this->admin)->post('/admin/testimonials', $this->payload([
            'is_published' => 0,
            'published_at' => now()->subWeek()->format('Y-m-d\TH:i'),
        ]));

        $testimonial = Testimonial::firstOrFail();
        $this->assertFalse($testimonial->is_published);
        $this->assertNull($testimonial->published_at);
    }

    public function test_an_editor_can_edit_a_testimonial(): void
    {
        $testimonial = Testimonial::create($this->payload());

        $this->actingAs($this->admin)->get("/admin/testimonials/{$testimonial->id}/edit")->assertOk();

        $this->actingAs($this->admin)->put("/admin/testimonials/{$testimonial->id}", $this->payload([
            'client_name' => 'Rani K.',
            'rating' => 4,
            'is_featured' => 0,
        ]))->assertRedirect(route('admin.testimonials.index'));

        $testimonial->refresh();
        $this->assertSame('Rani K.', $testimonial->client_name);
        $this->assertSame(4, $testimonial->rating);
        $this->assertFalse($testimonial->is_featured);
    }

    public function test_an_editor_can_publish_and_unpublish_from_the_list(): void
    {
        $testimonial = Testimonial::create($this->payload(['is_published' => 0, 'published_at' => null]));

        $this->actingAs($this->admin)
            ->post("/admin/testimonials/{$testimonial->id}/toggle-published")
            ->assertSessionHas('success');

        $testimonial->refresh();
        $this->assertTrue($testimonial->is_published);
        $this->assertNotNull($testimonial->published_at, 'Publishing must stamp a date.');

        $this->actingAs($this->admin)->post("/admin/testimonials/{$testimonial->id}/toggle-published");
        $this->assertFalse($testimonial->refresh()->is_published);
    }

    public function test_the_featured_flag_can_be_toggled(): void
    {
        $testimonial = Testimonial::create($this->payload(['is_featured' => 0]));

        $this->actingAs($this->admin)->post("/admin/testimonials/{$testimonial->id}/toggle-featured");

        $this->assertTrue($testimonial->refresh()->is_featured);
    }

    public function test_a_published_testimonial_appears_on_the_homepage(): void
    {
        Testimonial::create($this->payload(['published_at' => now()->subDay()]));

        $this->get('/')->assertOk()->assertSee('Rani Kusuma')->assertSee('Testimoni Klien');
    }

    public function test_a_draft_and_a_future_testimonial_stay_off_the_homepage(): void
    {
        Testimonial::create($this->payload([
            'client_name' => 'Draf Tersembunyi',
            'is_published' => 0,
            'published_at' => null,
        ]));
        Testimonial::create($this->payload([
            'client_name' => 'Terjadwal Nanti',
            'is_published' => 1,
            'published_at' => now()->addWeek(),
        ]));

        $response = $this->get('/');
        $response->assertOk();
        $response->assertDontSee('Draf Tersembunyi');
        $response->assertDontSee('Terjadwal Nanti');
        // Nothing visible at all: the section is not rendered.
        $response->assertDontSee('Testimoni Klien');
    }

    public function test_the_english_page_only_shows_english_testimonials(): void
    {
        $translated = Testimonial::create($this->payload([
            'client_name' => 'Rani Kusuma',
            'published_at' => now()->subDay(),
        ]));
        $translated->setTranslations('en', [
            'position' => 'Chief Operating Officer',
            'testimonial' => 'The team mapped our workflows before writing a single line of code.',
        ]);
        $translated->save();

        Testimonial::create($this->payload([
            'client_name' => 'Belum Diterjemahkan',
            'published_at' => now()->subDay(),
        ]));

        $english = $this->get('/en');
        $english->assertOk();
        $english->assertSee('Rani Kusuma');
        $english->assertSee('Chief Operating Officer');
        $english->assertDontSee('Belum Diterjemahkan');

        $indonesian = $this->get('/');
        $indonesian->assertOk();
        $indonesian->assertSee('Belum Diterjemahkan');
    }

    public function test_deleting_a_testimonial_soft_deletes_and_leaves_the_site(): void
    {
        $testimonial = Testimonial::create($this->payload(['published_at' => now()->subDay()]));

        $this->actingAs($this->admin)
            ->delete("/admin/testimonials/{$testimonial->id}")
            ->assertRedirect(route('admin.testimonials.index'));

        $this->assertSoftDeleted('testimonials', ['id' => $testimonial->id]);
        $this->get('/')->assertOk()->assertDontSee('Rani Kusuma');
        $this->actingAs($this->admin)->get('/admin/testimonials')->assertOk()->assertSee('Belum ada testimoni');
    }

    public function test_a_photo_upload_is_stored_and_saved_on_the_testimonial(): void
    {
        Storage::fake('public');

        $upload = $this->actingAs($this->admin)->postJson('/admin/uploads', [
            'file' => UploadedFile::fake()->image('klien.jpg', 400, 400),
        ]);

        $upload->assertOk();
        $path = $upload->json('path');

        $this->actingAs($this->admin)->post('/admin/testimonials', $this->payload(['photo' => $path]));

        $this->assertSame($path, Testimonial::firstOrFail()->photo);
        Storage::disk('public')->assertExists($path);
    }

    public function test_an_invalid_image_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->postJson('/admin/uploads', [
            'file' => UploadedFile::fake()->create('dokumen.pdf', 40, 'application/pdf'),
        ])->assertStatus(422);

        $this->assertDatabaseCount('media', 0);
    }
}
