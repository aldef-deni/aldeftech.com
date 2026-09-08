<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Services\ArticleAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            'Artikel otomatis berhasil diterbitkan.'
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
            $post = DB::transaction(function () use ($article, $input, $request) {
                $base = Str::limit(Str::slug($article['slug'] ?: $article['title']), 200, '') ?: 'artikel';
                $slug = $base;
                $suffix = 2;
                while (BlogPost::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $suffix++;
                }

                $post = BlogPost::create([
                    'title' => $article['title'],
                    'slug' => $slug,
                    'excerpt' => $article['excerpt'],
                    'content' => $article['content'],
                    'meta_title' => $article['meta_title'],
                    'meta_description' => $article['meta_description'],
                    'category_id' => $input['category_id'] ?? null,
                    'author_id' => $request->user()->id,
                    'status' => 'draft',
                    'published_at' => null,
                ]);
                ActivityLog::log('blog.created', "Created AI draft \"{$post->title}\"", $post);

                return $post;
            });
        } catch (Throwable $e) {
            // Provider exceptions may contain request details. Never display or log them.
            return back()->withInput($input)->withErrors([
                'generation' => 'Artikel belum dapat dibuat. Hasil mungkin tidak lengkap, mengandung klaim yang belum terverifikasi, atau layanan sedang bermasalah. Coba lagi dengan topik tanpa angka atau klaim riset.',
            ]);
        }

        return redirect()->route('admin.blog.edit', $post)
            ->with('success', 'Draf artikel berhasil dibuat. Periksa isi dan kebenaran klaim sebelum menerbitkan.');
    }
}
