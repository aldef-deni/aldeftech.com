<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\MarketingContent;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SeoDistributionService
{
    public function prepare(BlogPost $post): MarketingContent
    {
        if (! $post->isPublished()) {
            throw new RuntimeException('Distribusi memerlukan artikel terbit.');
        }
        $existing = MarketingContent::where('published_blog_post_id', $post->id)->where('content_type', 'distribution')->first();
        if ($existing) {
            return $existing;
        }
        $research = app(SeoResearchService::class);
        $rules = ['summary' => 'required|string|max:500', 'platform_posts' => 'required|array:linkedin,facebook,x,instagram'];
        foreach (['linkedin', 'facebook', 'x', 'instagram'] as $platform) {
            $rules['platform_posts.' . $platform] = 'required|array:hook,caption,hashtags,cta';
            foreach (['hook', 'caption', 'hashtags', 'cta'] as $field) {
                $rules['platform_posts.' . $platform . '.' . $field] = 'required|string|max:' . ($platform === 'x' ? 200 : 2200);
            }
        }
        $data = $research->json('distribution:' . $post->id,
            'Buat paket distribusi Bahasa Indonesia berdasarkan artikel saja, tanpa menambah klaim. '
            . 'JSON {"summary":"...","platform_posts":{"linkedin":{"hook":"...","caption":"...","hashtags":"...","cta":"..."},"facebook":{...},"x":{...},"instagram":{...}}}. '
            . 'X: gabungan hook, caption, hashtags, CTA maksimal 240 karakter, sisakan ruang untuk tautan. Hashtag relevan maksimal 3. Tanpa HTML dan tanpa URL buatan.',
            ['title' => $post->getBase('title'), 'article' => $research->text($post->getBase('content'))], $rules);
        $x = $data['platform_posts']['x'];
        if (mb_strlen(implode(' ', $x)) > 240) {
            throw new RuntimeException('Draf X terlalu panjang; review ulang diperlukan.');
        }
        return DB::transaction(function () use ($post, $data) {
            $locked = BlogPost::lockForUpdate()->findOrFail($post->id);
            if (! $locked->isPublished()) {
                throw new RuntimeException('Artikel sudah tidak terbit.');
            }
            return MarketingContent::firstOrCreate(['published_blog_post_id' => $post->id, 'content_type' => 'distribution'], [
                'title' => $post->getBase('title'), 'excerpt' => $data['summary'],
                'content' => route('blog.show', $post->slug), 'platform_posts' => $data['platform_posts'],
                'distribution_checklist' => ['Review klaim dan caption.', 'Salin URL artikel dan caption ke platform secara manual.'],
                'status' => 'draft', 'generated_at' => now(),
            ]);
        });
    }
}
