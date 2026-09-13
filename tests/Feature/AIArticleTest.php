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

    private function output(): array
    {
        return ['title' => 'Otomasi bisnis', 'slug' => 'otomasi-bisnis', 'excerpt' => 'Manfaat otomasi.',
            'content' => '<h2>Manfaat</h2><p>Otomasi membantu proses bisnis.</p>',
            'meta_title' => 'Otomasi bisnis', 'meta_description' => 'Kenali manfaat otomasi.'];
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

    public function test_generation_forces_draft_and_handles_deleted_slug(): void
    {
        $old = BlogPost::create($this->output() + ['status' => 'draft']);
        $old->delete();
        BlogPost::create(array_replace($this->output(), ['slug' => 'otomasi-bisnis-2', 'status' => 'draft']));
        $category = BlogCategory::create(['name' => 'Teknologi']);
        $this->mock(GeminiService::class)->shouldReceive('generateJson')->once()->andReturn($this->output());
        $user = $this->editor();
        $response = $this->actingAs($user)->post('/admin/blog/ai', [
            'topic' => 'Otomasi bisnis', 'category_id' => $category->id, 'primary_keyword' => 'otomasi',
            'secondary_keywords' => 'bisnis, teknologi', 'target_words' => 1600, 'status' => 'published', 'published_at' => now(),
        ]);
        $post = BlogPost::where('slug', 'otomasi-bisnis-3')->firstOrFail();
        $this->assertSame('draft', $post->status);
        $this->assertNull($post->published_at);
        $this->assertEquals($user->id, $post->author_id);
        $this->assertEquals($category->id, $post->category_id);
        foreach (['title', 'excerpt', 'content', 'meta_title', 'meta_description'] as $field) {
            $this->assertSame($this->output()[$field], $post->$field);
        }
        $response->assertRedirect(route('admin.blog.edit', $post));
        $this->get(route('admin.blog.edit', $post))->assertOk();
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
