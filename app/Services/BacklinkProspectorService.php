<?php

namespace App\Services;

use App\Models\BacklinkProspect;
use App\Models\BlogPost;

class BacklinkProspectorService
{
    public function discover(): int
    {
        $research = app(SeoResearchService::class);
        $limit = $research->limit('backlink_prospect_limit');
        $post = BlogPost::published()->latest('published_at')->first();
        if (! $limit || ! $post) {
            return 0;
        }
        $result = $research->search('backlinks',
            'Temukan maksimal ' . $limit . ' publikasi teknologi/bisnis, komunitas IT editorial, direktori software resmi, resource page atau mitra teknologi yang relevan dengan artikel. '
            . 'Gunakan Google Search. Prioritaskan organisasi Indonesia dan sumber internasional yang relevan. '
            . 'Jelaskan alasan relevansi dengan sitasi. Jangan mengasumsikan menerima guest post atau memberikan backlink. '
            . 'Hindari PBN, jual beli tautan, spam directories, mass reciprocal links. Tidak perlu data kontak. Gunakan satu pencarian terfokus.',
            ['article' => $post->getBase('title'), 'summary' => $research->text($post->getBase('excerpt'), 500),
                'exclude_domains' => BacklinkProspect::latest()->limit(150)->pluck('domain')->all()]);
        $metadata = $result['metadata'];
        $sources = [];
        foreach (array_slice($metadata['groundingChunks'] ?? [], 0, 10, true) as $index => $chunk) {
            $uri = $chunk['web']['uri'] ?? null;
            if (! is_string($uri)) {
                continue;
            }
            $evidence = [];
            foreach ($metadata['groundingSupports'] ?? [] as $support) {
                if (in_array($index, $support['groundingChunkIndices'] ?? [], true)) {
                    $evidence[] = $support['segment']['text'] ?? '';
                }
            }
            if (! $evidence) {
                continue;
            }
            $url = app(SeoPublicUrlVerifier::class)->verify($uri);
            if (! $url) {
                continue;
            }
            $domain = preg_replace('/^www\./', '', strtolower(parse_url($url, PHP_URL_HOST)));
            $own = preg_replace('/^www\./', '', strtolower(parse_url(config('app.url'), PHP_URL_HOST) ?? 'aldeftech.com'));
            if ($domain === $own || str_ends_with($domain, '.' . $own)
                || $domain === 'google.com' || str_ends_with($domain, '.google.com')
                || BacklinkProspect::where('domain', $domain)->exists()) {
                continue;
            }
            $sources[$domain] = ['domain' => $domain, 'url' => $url, 'source_url' => $uri,
                'title' => $research->text($chunk['web']['title'] ?? $domain, 255),
                'evidence' => $research->text(implode(' ', $evidence), 2500)];
            if (count($sources) >= $limit) {
                break;
            }
        }
        if (! $sources) {
            return 0;
        }
        $sources = array_values($sources);
        $data = $research->json('backlink-relevance',
            'Nilai kandidat dari bukti yang diberikan, bukan DA/DR. Tolak jika bukti tidak cukup, tidak relevan, spam, PBN, atau jaringan tautan berbayar. '
            . 'Setiap komponen 0 sampai 5: niche, editorial, topic_fit, legitimacy. Ini penilaian internal sementara, bukan metrik provider. '
            . 'JSON {"prospects":[{"source_index":0,"category":"publication","niche":0,"editorial":0,"topic_fit":0,"legitimacy":0,"reject":true,"reason":"..."}]}. '
            . 'Kategori: publication, community, directory, partner, resource, guest_post. Guest_post hanya bila bukti menyebut penerimaan kontribusi.',
            ['article' => $post->getBase('title'), 'sources' => $sources], [
                'prospects' => 'required|array|max:5',
                'prospects.*.source_index' => 'required|integer|min:0|max:4',
                'prospects.*.category' => 'required|in:publication,community,directory,partner,resource,guest_post',
                'prospects.*.niche' => 'required|integer|between:0,5',
                'prospects.*.editorial' => 'required|integer|between:0,5',
                'prospects.*.topic_fit' => 'required|integer|between:0,5',
                'prospects.*.legitimacy' => 'required|integer|between:0,5',
                'prospects.*.reject' => 'required|boolean',
                'prospects.*.reason' => 'required|string|max:2000',
            ]);
        $count = 0;
        foreach ($data['prospects'] as $item) {
            $source = $sources[$item['source_index']] ?? null;
            $score = 6 * $item['niche'] + 4 * $item['editorial'] + 6 * $item['topic_fit'] + 4 * $item['legitimacy'];
            if (! $source || $item['reject'] || $score < 60 || $item['legitimacy'] < 3 || $item['editorial'] < 3) {
                continue;
            }
            $record = BacklinkProspect::firstOrCreate(['domain' => $source['domain']], array_merge([
                'url' => $source['url'], 'website_name' => $source['domain'], 'category' => $item['category'],
                'relevance_score' => $score, 'spam_risk' => null,
                'target_blog_post_id' => $post->id, 'suggested_anchor' => $post->getBase('title'),
                'suggested_pitch' => $item['reason'], 'status' => 'new',
                'notes' => 'Sumber pencarian dan URL diperiksa. Kualitas editorial, peluang kontribusi dan kontak tetap perlu review manusia.',
                'evidence' => ['source' => $source, 'assessment' => $item, 'search' => $result],
                'verified_at' => now(),
            ], app(SeoMetricsService::class)->authority($source['domain'])));
            $count += (int) $record->wasRecentlyCreated;
        }
        return $count;
    }
}
