<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\SeoPageReview;

class SeoRefreshService
{
    public function analyze(BlogPost $post, bool $research = false): SeoPageReview
    {
        $helper = app(SeoResearchService::class);
        $links = app(InternalLinkService::class);
        $content = (string) $post->getBase('content');
        $related = array_slice($links->related($post->getBase('title'), $links->catalog($post->id)), 0, 5);
        $recommendations = [];
        if (! $post->meta_title || mb_strlen($post->meta_title) > 60) {
            $recommendations[] = 'Tinjau meta title agar ringkas dan sesuai intent; sekitar 60 karakter.';
        }
        if (! $post->meta_description || mb_strlen($post->meta_description) > 160) {
            $recommendations[] = 'Tinjau meta description yang unik dan deskriptif; sekitar 150–160 karakter.';
        }
        if (! preg_match('/<h2\b/i', $content)) {
            $recommendations[] = 'Tambahkan heading H2 yang membantu pembaca memindai topik.';
        }
        if (preg_match('/<h1\b/i', $content)) {
            $recommendations[] = 'Tinjau H1 di body: judul utama sudah ditampilkan oleh halaman.';
        }
        if (! $post->featured_image) {
            $recommendations[] = 'Pertimbangkan gambar relevan; alt featured image mengikuti judul artikel.';
        }
        $recommendations[] = 'Periksa istilah terkini, bagian yang kurang, CTA, FAQ dan tumpang tindih topik sebelum mengubah isi.';
        $details = [
            'checks' => $recommendations,
            'related_pages' => $related,
            'inbound_candidates' => array_values(array_filter($related, fn ($p) => $p['kind'] === 'blog.show')),
            'inbound_note' => 'Kandidat editorial: periksa artikel lama untuk menautkan ke artikel ini; belum diubah otomatis.',
        ];
        if ($research) {
            $data = $helper->json('refresh:' . $post->id,
                'Tinjau artikel, jangan tulis ulang. Usulkan meta_title, meta_description dan maksimal 5 recommendations untuk bagian kurang, heading, CTA, istilah yang perlu verifikasi dan overlap dengan related_pages. '
                . 'Jangan mengklaim informasi usang tanpa bukti. Jangan ubah slug. JSON: {"meta_title":"...","meta_description":"...","recommendations":["..."]}.',
                ['title' => $post->getBase('title'), 'content' => $helper->text($content), 'related_pages' => $related], [
                    'meta_title' => 'required|string|max:60',
                    'meta_description' => 'required|string|max:160',
                    'recommendations' => 'required|array|max:5',
                    'recommendations.*' => 'required|string|max:1500',
                ]);
            $details['editorial_draft'] = $data;
        }
        $previous = SeoPageReview::where('blog_post_id', $post->id)->first();
        if (! $research && isset($previous?->recommendations['editorial_draft'])) {
            $details['editorial_draft'] = $previous->recommendations['editorial_draft'];
        }
        $review = SeoPageReview::updateOrCreate(['blog_post_id' => $post->id], [
            'page_url' => route('blog.show', $post->slug),
            'target_keyword' => $previous?->target_keyword,
            'optimization_status' => 'review_needed',
            'recommendations' => $details, 'analyzed_at' => now(),
            'next_review_at' => $research ? now()->addDays((int) config('seo_growth.review_days', 90)) : now(),
        ]);
        SeoActivityService::record('seo.analysis', 'Analyzed article', $review, ['status' => 'completed']);
        if ($related) {
            SeoActivityService::record('seo.internal_link', 'Prepared internal link recommendations', $review,
                ['mode' => 'recommended', 'count' => count($related)]);
        }
        if ($research) {
            SeoActivityService::record('seo.content_refresh', 'Prepared article refresh recommendations', $review,
                ['mode' => 'recommended', 'count' => 1]);
        }
        return $review;
    }
}
