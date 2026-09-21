<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogPostSlugRedirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogSlugRedirectTest extends TestCase
{
    use RefreshDatabase;

    private function post(array $attributes = []): BlogPost
    {
        $category = BlogCategory::firstOrCreate(
            ['slug' => 'insight'],
            ['name' => 'Insight']
        );

        return BlogPost::create(array_merge([
            'title' => 'Penerapan Kecerdasan Buatan untuk Bisnis',
            'slug' => 'penerapan-kecerdasan-buatan-untuk-bisnis',
            'excerpt' => 'Ringkasan.',
            'content' => '<p>Isi artikel.</p>',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_a_generated_slug_loses_its_uuid_tail(): void
    {
        $post = $this->post([
            'slug' => 'penerapan-kecerdasan-buatan-untuk-bisnis-c386a6ae-0e71-457b-a958-dc93220aec4f',
        ]);

        $this->assertSame('penerapan-kecerdasan-buatan-untuk-bisnis', $post->slug);
    }

    public function test_a_duplicate_slug_is_suffixed_instead_of_colliding(): void
    {
        $this->post();
        $second = $this->post(['title' => 'Penerapan Kecerdasan Buatan untuk Bisnis']);

        $this->assertSame('penerapan-kecerdasan-buatan-untuk-bisnis-2', $second->slug);
    }

    public function test_renaming_an_article_keeps_the_old_address_working(): void
    {
        $post = $this->post();

        $post->update(['slug' => 'penerapan-ai-untuk-bisnis']);

        $this->assertDatabaseHas('article_slug_redirects', [
            'article_id' => $post->id,
            'old_slug' => 'penerapan-kecerdasan-buatan-untuk-bisnis',
        ]);

        $this->get('/blog/penerapan-kecerdasan-buatan-untuk-bisnis')
            ->assertRedirect('/blog/penerapan-ai-untuk-bisnis')
            ->assertStatus(301);
    }

    public function test_a_uuid_address_that_was_never_stored_still_redirects(): void
    {
        $this->post();

        $this->get('/blog/penerapan-kecerdasan-buatan-untuk-bisnis-c386a6ae-0e71-457b-a958-dc93220aec4f')
            ->assertRedirect('/blog/penerapan-kecerdasan-buatan-untuk-bisnis')
            ->assertStatus(301);
    }

    public function test_the_cleanup_command_rewrites_stored_uuid_slugs(): void
    {
        $post = $this->post();

        // Bypass the model rule to imitate the rows already in production.
        BlogPost::withoutEvents(fn () => BlogPost::whereKey($post->id)->update([
            'slug' => 'penerapan-kecerdasan-buatan-untuk-bisnis-c386a6ae-0e71-457b-a958-dc93220aec4f',
        ]));

        $this->artisan('app:articles-clean-slugs')->assertSuccessful();

        $this->assertSame(
            'penerapan-kecerdasan-buatan-untuk-bisnis',
            $post->fresh()->slug
        );

        $this->assertDatabaseHas('article_slug_redirects', [
            'article_id' => $post->id,
            'old_slug' => 'penerapan-kecerdasan-buatan-untuk-bisnis-c386a6ae-0e71-457b-a958-dc93220aec4f',
        ]);
    }

    public function test_an_untranslated_article_does_not_claim_two_canonicals(): void
    {
        $post = $this->post();

        $this->get('/blog/' . $post->slug)
            ->assertSee('<link rel="canonical" href="' . url('/blog/' . $post->slug) . '">', false)
            ->assertDontSee('hreflang="en"', false);

        // The /en copy is the same Indonesian document, so it points at the
        // original instead of at itself.
        $this->get('/en/blog/' . $post->slug)
            ->assertSee('<link rel="canonical" href="' . url('/blog/' . $post->slug) . '">', false);
    }

    public function test_the_legacy_id_prefix_forwards_to_the_bare_page(): void
    {
        $this->get('/id/services')->assertRedirect('/services')->assertStatus(301);
    }
}
