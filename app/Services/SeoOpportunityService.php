<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\SeoOpportunity;
use Illuminate\Support\Str;

class SeoOpportunityService
{
    public function discover(): int
    {
        $research = app(SeoResearchService::class);
        $limit = $research->limit('content_opportunity_limit');
        if (! $limit) {
            return 0;
        }
        $posts = BlogPost::latest()->limit(200)->get(['id', 'title'])->map(fn ($p) => ['id' => $p->id, 'title' => $p->getBase('title')])->all();
        $existing = SeoOpportunity::latest()->limit(200)->get(['topic', 'keyword', 'cluster'])->toArray();
        $data = $research->json('opportunities',
            'Usulkan maksimal ' . $limit . ' peluang evergreen Bahasa Indonesia. Bidang: Artificial Intelligence, AI Agent, software development, SaaS, web/mobile application, otomasi bisnis, transformasi digital, integrasi sistem, IT consulting dan custom business systems. '
            . 'Cari content gap, supporting article, perbandingan atau problem/solution dengan intent berbeda. Hindari duplikat/parafrasa/maksud pencarian yang sudah ada. '
            . 'Ini ide editorial, bukan tren atau volume pencarian terukur. Kelompokkan ke cluster dan pillar_blog_post_id yang ada, atau null jika belum ada pillar. '
            . 'JSON: {"opportunities":[{"category":"...","cluster":"...","topic":"...","keyword":"...","reason":"...","priority":3,"pillar_blog_post_id":null}]}. Priority 1 rendah sampai 5 tinggi.',
            ['articles' => $posts, 'existing_opportunities' => $existing], [
                'opportunities' => 'required|array|max:5',
                'opportunities.*.category' => 'required|string|max:100',
                'opportunities.*.cluster' => 'required|string|max:150',
                'opportunities.*.topic' => 'required|string|max:255',
                'opportunities.*.keyword' => 'required|string|max:150',
                'opportunities.*.reason' => 'required|string|max:2000',
                'opportunities.*.priority' => 'required|integer|between:1,5',
                'opportunities.*.pillar_blog_post_id' => 'nullable|integer',
            ]);
        $known = array_merge(array_column($posts, 'title'), array_column($existing, 'topic'));
        $keywords = array_map(fn ($k) => Str::slug($k), array_column($existing, 'keyword'));
        $count = 0;
        foreach (array_slice($data['opportunities'], 0, $limit) as $idea) {
            if ($this->duplicates($idea['topic'], $known) || in_array(Str::slug($idea['keyword']), $keywords, true)) {
                continue;
            }
            $pillar = $idea['pillar_blog_post_id'] ?? null;
            $idea['pillar_blog_post_id'] = $pillar && BlogPost::published()->whereKey($pillar)->exists() ? $pillar : null;
            $record = SeoOpportunity::firstOrCreate(['fingerprint' => hash('sha256', Str::slug($idea['keyword']))],
                array_intersect_key($idea, array_flip(['category', 'cluster', 'topic', 'keyword', 'reason', 'priority', 'pillar_blog_post_id'])));
            $known[] = $idea['topic'];
            $keywords[] = Str::slug($idea['keyword']);
            $count += (int) $record->wasRecentlyCreated;
        }
        return $count;
    }

    private function duplicates(string $topic, array $known): bool
    {
        $tokens = app(InternalLinkService::class)->tokens($topic);
        foreach ($known as $title) {
            $other = app(InternalLinkService::class)->tokens($title);
            similar_text(Str::slug($topic), Str::slug($title), $similarity);
            $overlap = count(array_intersect($tokens, $other)) / max(1, count(array_unique(array_merge($tokens, $other))));
            if ($similarity >= 85 || $overlap >= 0.65) {
                return true;
            }
        }
        return false;
    }
}
