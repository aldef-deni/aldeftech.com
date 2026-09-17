<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\ArticleAIService;
use App\Services\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class AIArticleTest extends TestCase
{
    use RefreshDatabase;

    private function editor(): User
    {
        $role = Role::create(['name' => 'editor', 'display_name' => 'Editor']);
        $role->permissions()->attach(Permission::create(['name' => 'manage-content', 'display_name' => 'Content']));
        $user = User::factory()->create();
        $user->roles()->attach($role);
        return $user;
    }

    /** A complete article: long enough, with the full SEO metadata block. */
    private function output(): array
    {
        $paragraph = '<p>Otomasi proses bisnis membantu tim mengurangi pekerjaan manual berulang dan menjaga konsistensi data antar divisi.</p>';

        return ['title' => 'Otomasi bisnis', 'slug' => 'otomasi-bisnis',
            'excerpt' => 'Panduan ringkas menyiapkan otomasi proses bisnis tanpa klaim berlebihan.',
            'content' => '<h2>Manfaat otomasi</h2>' . str_repeat($paragraph, 40),
            'meta_title' => 'Otomasi bisnis untuk perusahaan',
            'meta_description' => 'Panduan menyiapkan otomasi proses bisnis secara bertahap, termasuk hal yang perlu diperhatikan sebelum implementasi.'];
    }

    public function test_access_and_generator_page(): void
    {
        $this->get('/admin/blog/ai/create')->assertRedirect('/admin/login');
        $this->post('/admin/blog/ai')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create())->get('/admin/blog/ai/create')->assertForbidden();
        $this->post('/admin/blog/ai')->assertForbidden();
        $this->actingAs($this->editor())->get('/admin/blog/ai/create')->assertOk()->assertSee('Buat Draf Artikel');
        $this->get('/admin/blog')->assertOk()->assertSee('Generate Artikel dengan AI');
    }

    public function test_complete_article_is_published_with_a_clean_unique_slug(): void
    {
        // The slug collides with a soft-deleted row and with a live one; a
        // distinct title on each keeps this about slugs, not about duplicates.
        $deleted = BlogPost::create(array_replace($this->output(), [
            'title' => 'Otomasi pabrik manufaktur', 'slug' => 'otomasi-bisnis', 'status' => 'draft',
        ]));
        $deleted->delete();
        BlogPost::create(array_replace($this->output(), [
            'title' => 'Transformasi digital ritel', 'slug' => 'otomasi-bisnis-2',
            'status' => 'published', 'published_at' => now()->subDay(),
        ]));
        $category = BlogCategory::create(['name' => 'Teknologi']);
        $this->mock(GeminiService::class)->shouldReceive('generateJson')->once()->andReturn($this->output());
        $user = $this->editor();
        $response = $this->actingAs($user)->post('/admin/blog/ai', [
            'topic' => 'Otomasi bisnis', 'category_id' => $category->id, 'primary_keyword' => 'otomasi',
            'secondary_keywords' => 'bisnis, teknologi', 'target_words' => 1600, 'status' => 'draft', 'published_at' => null,
        ]);
        $post = BlogPost::where('slug', 'otomasi-bisnis-3')->firstOrFail();
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertTrue($post->isPublished());
        $this->assertEquals($user->id, $post->author_id);
        $this->assertEquals($category->id, $post->category_id);
        foreach (['title', 'excerpt', 'content', 'meta_title', 'meta_description'] as $field) {
            $this->assertSame($this->output()[$field], $post->$field);
        }
        $response->assertRedirect(route('admin.blog.edit', $post));
        $response->assertSessionHas('success');
        $this->get(route('admin.blog.edit', $post))->assertOk();
    }

    public function test_incomplete_output_is_kept_as_draft_and_stays_off_the_public_site(): void
    {
        // Short prose and a stub meta description: the generator accepts both,
        // the publication gate does not.
        $this->mock(GeminiService::class)->shouldReceive('generateJson')->andReturn(array_replace($this->output(), [
            'content' => '<p>Otomasi membantu proses bisnis.</p>',
            'meta_description' => 'Kenali otomasi.',
        ]));
        $user = $this->editor();
        $this->actingAs($user)->post('/admin/blog/ai', ['topic' => 'Otomasi bisnis', 'target_words' => 1600])
            ->assertSessionHas('success');

        $post = BlogPost::firstOrFail();
        $this->assertSame('draft', $post->status);
        $this->assertNull($post->published_at);
        $this->assertFalse($post->isPublished());
        $this->assertStringContainsString('draf', (string) session('success'));

        $this->get('/blog')->assertOk()->assertDontSee($post->title);
        $this->get(route('blog.show', $post->slug))->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee($post->slug);
    }

    public function test_duplicate_title_or_slug_is_kept_as_draft(): void
    {
        BlogPost::create(array_replace($this->output(), ['status' => 'published', 'published_at' => now()->subDay()]));
        $this->mock(GeminiService::class)->shouldReceive('generateJson')->andReturn($this->output());
        $this->actingAs($this->editor())->post('/admin/blog/ai', ['topic' => 'Otomasi bisnis', 'target_words' => 1600]);

        $this->assertSame(2, BlogPost::count());
        $this->assertSame(1, BlogPost::where('status', 'published')->count());
        $this->assertSame(1, BlogPost::where('status', 'draft')->count());
    }

    public function test_auto_publish_can_be_disabled_from_configuration(): void
    {
        config(['ai_article.auto_publish' => false]);
        $this->mock(GeminiService::class)->shouldReceive('generateJson')->once()->andReturn($this->output());
        $this->actingAs($this->editor())->post('/admin/blog/ai', ['topic' => 'Otomasi bisnis', 'target_words' => 1600]);

        $post = BlogPost::firstOrFail();
        $this->assertSame('draft', $post->status);
        $this->assertNull($post->published_at);
    }

    public function test_published_article_is_served_on_blog_and_in_the_sitemap(): void
    {
        $published = BlogPost::create(array_replace($this->output(), [
            'status' => 'published', 'published_at' => now()->subDay(),
        ]));
        $draft = BlogPost::create(array_replace($this->output(), [
            'title' => 'Transformasi digital ritel', 'slug' => 'transformasi-digital-ritel', 'status' => 'draft',
        ]));

        $this->get('/blog')->assertOk()->assertSee($published->title)->assertDontSee($draft->title);
        $this->get(route('blog.show', $published->slug))->assertOk()->assertSee($published->title);
        $this->get(route('blog.show', $draft->slug))->assertNotFound();
        $this->get('/')->assertOk()->assertSee($published->title);

        $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('/blog/' . $published->slug, $sitemap);
        $this->assertStringNotContainsString($draft->slug, $sitemap);
    }

    public function test_validation_prevents_generation(): void
    {
        $this->mock(GeminiService::class)->shouldNotReceive('generateJson');
        $this->actingAs($this->editor())->post('/admin/blog/ai', ['target_words' => 9999, 'category_id' => 999999])
            ->assertSessionHasErrors(['topic', 'target_words', 'category_id']);
        $this->assertDatabaseCount('blog_posts', 0);
    }

    public function test_provider_failure_is_safe_and_preserves_input(): void
    {
        $this->mock(GeminiService::class)->shouldReceive('generateJson')->andThrow(new \RuntimeException('private-provider-detail'));
        $this->actingAs($this->editor())->from('/admin/blog/ai/create')->post('/admin/blog/ai', [
            'topic' => 'Otomasi', 'target_words' => 1600,
        ])->assertRedirect('/admin/blog/ai/create')->assertSessionHasErrors('generation')
            ->assertSessionHas('_old_input.topic', 'Otomasi');
        $this->assertStringNotContainsString('private-provider-detail', session('errors')->first('generation'));
        $this->assertDatabaseCount('blog_posts', 0);
    }

    public function test_unsupported_claims_and_unsafe_html_are_rejected(): void
    {
        foreach (['<p>Hemat 80% biaya.</p>', '<p>Hemat delapan puluh persen.</p>',
            '<p>Menurut penelitian, bisnis lebih efisien.</p>', '<p>Hemat &#56; persen.</p>',
            '<p onclick="alert(1)">Manfaat</p>', '<script>alert(1)</script>', 'Artikel tanpa HTML', '<p>**Markdown**</p>',
            '<p>Artikel belum selesai', '<p><strong>Artikel</p></strong>', ''] as $content) {
            $gemini = \Mockery::mock(GeminiService::class);
            $gemini->shouldReceive('generateJson')->andReturn(array_replace($this->output(), ['content' => $content]));
            try {
                (new ArticleAIService($gemini))->generate(['topic' => 'Otomasi']);
                $this->fail('Unsafe output was accepted: ' . $content);
            } catch (\RuntimeException $e) {
                $this->assertNotEmpty($e->getMessage());
            }
        }
    }

    public function test_gemini_rejects_truncated_or_non_object_responses(): void
    {
        config(['services.gemini.api_key' => 'test-key', 'services.gemini.model' => 'test-model',
            'services.gemini.base_url' => 'https://example.test']);
        foreach ([['MAX_TOKENS', '{}'], ['STOP', 'null'], ['STOP', '[]'], ['STOP', '{invalid']] as [$reason, $text]) {
            Http::swap(new \Illuminate\Http\Client\Factory);
            Http::fake(['*' => Http::response(['candidates' => [[
                'finishReason' => $reason, 'content' => ['parts' => [['text' => $text]]],
            ]]])]);
            try {
                (new GeminiService)->generateJson('Test');
                $this->fail('Invalid provider response was accepted.');
            } catch (\RuntimeException $e) {
                $this->assertNotEmpty($e->getMessage());
            }
        }
    }

    public function test_provider_error_does_not_include_response_body(): void
    {
        config(['services.gemini.api_key' => 'test-key', 'services.gemini.model' => 'test-model',
            'services.gemini.base_url' => 'https://example.test']);
        Http::fake(['*' => Http::response('private-provider-detail', 500)]);
        try {
            (new GeminiService)->generateJson('Test');
            $this->fail('Provider error was accepted.');
        } catch (\RuntimeException $e) {
            $this->assertStringNotContainsString('private-provider-detail', $e->getMessage());
        }
    }
}
