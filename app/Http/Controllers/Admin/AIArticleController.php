<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\PageSeo;
use App\Services\ArticleAIService;
use App\Services\ArticlePublishGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AIArticleController extends Controller
{
    public function automatic(Request $request)
    {
        try {
            $post = app(\App\Services\AutomatedContentService::class)->generate(false, $request->user()->id);
        } catch (Throwable $e) {
            return back()->withErrors(['generation' => 'Pembuatan otomatis gagal. Periksa riwayat ai_content_runs dan konfigurasi layanan.']);
        }
        if (! $post) {
            return back()->withErrors(['generation' => 'Pembuatan otomatis sedang berjalan. Tunggu sebelum mencoba lagi.']);
        }
        return redirect()->route('admin.blog.edit', $post)->with('success',
            ($post->status === 'published'
                ? 'Artikel otomatis berhasil dibuat dan diterbitkan.'
                : 'Draf artikel otomatis dibuat dan ditahan dari publikasi. Periksa isi dan klaim sebelum menerbitkan.')
            . ($post->featured_image ? '' : ' Gambar belum tersedia; tambahkan melalui editor.'));
    }

    public function create(Request $request)
    {
        $defaults = $request->validate(['topic' => 'nullable|string|max:500', 'primary_keyword' => 'nullable|string|max:150']);
        return view('admin.blog.ai', [
            'defaults' => $defaults,
            'categories' => BlogCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'topic' => 'required|string|max:500',
            'primary_keyword' => 'nullable|string|max:150',
            'secondary_keywords' => 'nullable|string|max:500',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'target_words' => 'required|integer|min:500|max:2500',
        ]);

        try {
            // Resolve inside the try so missing provider configuration is safe too.
            $article = app(ArticleAIService::class)->generate($input);
            $guard = app(ArticlePublishGuard::class);
            // The generator only guarantees content safety. Whether the article
            // may go live is decided here, by the same gate the scheduler uses;
            // incomplete, thin or duplicate output is stored as a draft instead.
            $article['slug'] = $guard->uniqueSlug($article['slug'] ?: $article['title']);
            $decision = $guard->decision($article);
            $post = DB::transaction(function () use ($article, $input, $request, $decision) {
                $seoActivity = $article['_seo_activity'] ?? [];
                unset($article['_seo_activity']);

                $post = BlogPost::create([
                    'title' => $article['title'],
                    'slug' => $article['slug'],
                    'excerpt' => $article['excerpt'],
                    'content' => $article['content'],
                    'meta_title' => $article['meta_title'],
                    'meta_description' => $article['meta_description'],
                    'category_id' => $input['category_id'] ?? null,
                    'author_id' => $request->user()->id,
                    'status' => $decision['status'],
                    'published_at' => $decision['published_at'],
                ]);
                \App\Services\SeoActivityService::articleSaved($post, $seoActivity);
                if ($post->status === 'published') {
                    // Cached page SEO for /blog and the homepage must not outlive
                    // the article that changed the listing.
                    PageSeo::clearCache();
                    ActivityLog::log('blog.published', "Published AI article \"{$post->title}\"", $post);
                } else {
                    ActivityLog::log('blog.drafted', "Created AI draft \"{$post->title}\"", $post,
                        ['reasons' => $decision['reasons']]);
                }

                return $post;
            });
        } catch (Throwable $e) {
            // Provider exceptions may contain request details. Never display or log them.
            return back()->withInput($input)->withErrors([
                'generation' => 'Artikel belum dapat dibuat. Hasil mungkin tidak lengkap, mengandung klaim yang belum terverifikasi, atau layanan sedang bermasalah. Coba lagi dengan topik tanpa angka atau klaim riset.',
            ]);
        }

        return redirect()->route('admin.blog.edit', $post)
            ->with('success', $post->status === 'published'
                ? 'Artikel berhasil dibuat dan diterbitkan. Tautan terkait dan sitemap akan mengikuti otomatis.'
                : 'Artikel disimpan sebagai draf karena: ' . $guard->summary($decision['reasons']) . '. Perbaiki lalu terbitkan manual.');
    }
}
