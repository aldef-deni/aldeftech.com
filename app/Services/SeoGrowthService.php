<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\MarketingContent;
use App\Models\SeoGrowthRun;
use App\Models\SeoOpportunity;
use App\Models\SeoPageReview;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SeoGrowthService
{
    public function prepareArticle(array $article): array
    {
        if (! config('seo_growth.enabled')) {
            return $article;
        }
        try {
            $optimized = $article;
            $optimized['meta_title'] = Str::limit($article['meta_title'] ?: $article['title'], 60, '');
            $optimized['meta_description'] = Str::limit($article['meta_description'] ?: $article['excerpt'], 160, '');
            $optimized['excerpt'] = Str::limit($article['excerpt'], 300, '');
            $optimized['content'] = app(InternalLinkService::class)->improve($article['content'], $article['title']);
            // Heading structure, FAQ, keyword placement and safe claims remain the
            // responsibility of the existing validated article generator.
            return $optimized;
        } catch (Throwable $e) {
            Log::warning('SEO enhancement skipped; validated article preserved.');
            return $article;
        }
    }

    public function run(bool $weekly = false): array
    {
        $research = app(SeoResearchService::class);
        if (! $research->ready()) {
            return ['status' => 'disabled_or_not_migrated'];
        }
        $lock = Cache::store('database')->lock('seo-growth-work', 3600);
        if (! $lock->get()) {
            return ['status' => 'already_running'];
        }
        $period = $weekly ? now('Asia/Jakarta')->format('o-W') : now('Asia/Jakarta')->format('Y-m-d');
        $task = $weekly ? 'weekly' : 'daily';
        try {
            $key = $task . ':' . $period;
            if (SeoGrowthRun::where('run_key', $key)->exists()) {
                return ['status' => 'already_attempted'];
            }
            $run = SeoGrowthRun::create(['run_key' => $key, 'task' => $task, 'status' => 'running']);
            $results = [];
            $work = $weekly ? [
                'refresh' => function () use ($research) {
                    $posts = BlogPost::published()->whereIn('id', SeoPageReview::where('next_review_at', '<=', now())->select('blog_post_id'))
                        ->orderBy('updated_at')->limit($research->limit('refresh_limit', 3))->get();
                    $count = 0;
                    foreach ($posts as $post) {
                        try {
                            app(SeoRefreshService::class)->analyze($post, true);
                            $count++;
                        } catch (Throwable $e) {
                            Log::warning('SEO refresh deferred.', ['post_id' => $post->id]);
                        }
                    }
                    return ['analyzed' => $count, 'deferred' => $posts->count() - $count];
                },
                'summary' => fn () => [
                    'published_articles' => BlogPost::published()->count(),
                    'opportunities' => SeoOpportunity::where('status', 'new')->count(),
                    'reviews_needed' => SeoPageReview::where('optimization_status', 'review_needed')->count(),
                    'search_console' => app(SeoMetricsService::class)->searchConsole(config('app.url')),
                ],
            ] : [
                'analysis' => function () use ($research) {
                    $posts = BlogPost::published()->whereNotIn('id', SeoPageReview::select('blog_post_id'))
                        ->latest('published_at')->limit($research->limit('article_limit', 3))->get();
                    foreach ($posts as $post) {
                        app(SeoRefreshService::class)->analyze($post);
                    }
                    return $posts->count();
                },
                'opportunities' => fn () => app(SeoOpportunityService::class)->discover(),
                'backlinks' => fn () => app(BacklinkProspectorService::class)->discover(),
                'distribution' => function () {
                    $post = BlogPost::published()->whereNotIn('id', MarketingContent::where('content_type', 'distribution')
                        ->whereNotNull('published_blog_post_id')->select('published_blog_post_id'))->latest('published_at')->first();
                    return $post ? app(SeoDistributionService::class)->prepare($post)->id : null;
                },
            ];
            foreach ($work as $stage => $callback) {
                try {
                    $results[$stage] = ['status' => 'completed', 'result' => $callback()];
                } catch (Throwable $e) {
                    $results[$stage] = ['status' => 'deferred', 'reason' => 'Dibatasi, konfigurasi belum tersedia, atau hasil belum valid; coba pada jadwal berikutnya.'];
                    Log::warning('SEO growth stage deferred.', ['run_id' => $run->id, 'stage' => $stage]);
                }
            }
            $partial = collect($results)->contains(fn ($item) => $item['status'] !== 'completed' || data_get($item, 'result.deferred', 0) > 0);
            $run->update(['status' => $partial ? 'partial' : 'completed', 'result' => $results]);
            return ['status' => $run->status, 'stages' => $results];
        } finally {
            $lock->release();
        }
    }
}
