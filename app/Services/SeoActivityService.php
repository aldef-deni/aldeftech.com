<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\BacklinkProspect;
use App\Models\BlogPost;
use App\Models\MarketingContent;
use App\Models\SeoGrowthRun;
use App\Models\SeoOpportunity;
use App\Models\SeoOutreachDraft;
use App\Models\SeoPageReview;
use Cron\CronExpression;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SeoActivityService
{
    public static function register(): void
    {
        foreach ([
            SeoOpportunity::class => ['seo.content_opportunity', 'Created content opportunity'],
            BacklinkProspect::class => ['seo.backlink_prospect', 'Found backlink prospect'],
            SeoOutreachDraft::class => ['seo.outreach_draft', 'Created outreach draft'],
        ] as $model => [$action, $description]) {
            $model::created(fn ($record) => self::record($action, $description, $record));
        }
        MarketingContent::created(function ($record) {
            if ($record->content_type === 'distribution') {
                self::record('seo.distribution_pack', 'Created distribution pack', $record);
            }
        });
        SeoGrowthRun::saved(function ($run) {
            if (! in_array($run->task, ['daily', 'weekly'], true) || ! $run->wasChanged('status')) {
                return;
            }
            if (in_array($run->status, ['completed', 'partial', 'failed'], true)) {
                self::record('seo.growth_run.' . ($run->status === 'completed' ? 'completed' : 'failed'),
                    'SEO Growth ' . $run->task . ' run', $run, ['status' => $run->status]);
            }
        });
    }

    public static function record(string $action, string $description, Model $subject, array $properties = []): void
    {
        try {
            // Callers supply application-owned messages and numeric action counts,
            // never exception messages, provider payloads or credentials.
            ActivityLog::log($action, $description, $subject, $properties + ['status' => $subject->status]);
        } catch (Throwable $e) {
            Log::warning('SEO activity record unavailable.', ['action' => $action]);
        }
    }

    public static function articleSaved(BlogPost $post, array $changes): void
    {
        if (($changes['links_added'] ?? 0) > 0) {
            self::record('seo.internal_link', 'Added internal links to article', $post,
                ['mode' => 'added', 'count' => (int) $changes['links_added']]);
        }
        if (($changes['optimized'] ?? false) === true) {
            self::record('seo.content_refresh', 'Applied SEO improvements to new article', $post,
                ['mode' => 'applied', 'count' => 1]);
        }
    }

    public function dashboard(): array
    {
        $start = now('Asia/Jakarta')->startOfDay()->setTimezone(config('app.timezone'));
        $end = $start->copy()->addDay();
        $today = fn ($query, $column = 'created_at') => $query->where($column, '>=', $start)->where($column, '<', $end);
        $workflow = fn () => SeoGrowthRun::whereIn('task', ['daily', 'weekly']);
        $latest = $workflow()->latest('id')->first();
        $lastSuccess = $workflow()->where('status', 'completed')->latest('updated_at')->first();
        $lastFailure = SeoGrowthRun::whereIn('status', ['failed', 'partial'])->latest('updated_at')->first();
        $applied = $today(ActivityLog::where('action', 'seo.content_refresh'))->where('properties->mode', 'applied')->distinct()->count('subject_id');
        $added = (int) $today(ActivityLog::where('action', 'seo.internal_link'))->where('properties->mode', 'added')
            ->sum(DB::raw("COALESCE(JSON_EXTRACT(properties, '$.count'), 0)"));
        $recommended = (int) $today(SeoPageReview::query(), 'analyzed_at')
            ->sum(DB::raw("COALESCE(JSON_LENGTH(JSON_EXTRACT(recommendations, '$.related_pages')), 0)"));
        $metrics = [
            'Articles analyzed' => $today(SeoPageReview::query(), 'analyzed_at')->count(),
            'Content opportunities found' => $today(SeoOpportunity::query())->count(),
            'Backlink prospects found' => $today(BacklinkProspect::query())->count(),
            'Outreach drafts created' => $today(SeoOutreachDraft::query())->count(),
            'Distribution packs created' => $today(MarketingContent::where('content_type', 'distribution'))->count(),
            'Internal links added' => $added,
            'Internal link recommendations' => $recommended,
            'Articles optimized (applied)' => $applied,
            'Refresh recommendations prepared' => $today(ActivityLog::where('action', 'seo.content_refresh'))->where('properties->mode', 'recommended')->count(),
            'Failed API attempts' => $today(SeoGrowthRun::where('task', 'like', 'api:%'), 'updated_at')->where('status', 'failed')->count(),
            'Failed / partial growth runs' => $today($workflow(), 'updated_at')->whereIn('status', ['failed', 'partial'])->count(),
        ];
        $schedules = [];
        foreach (array_intersect_key(config('seo_growth.schedules', []), array_flip(['daily', 'weekly'])) as $name => $expression) {
            try {
                $next = Carbon::instance((new CronExpression($expression))->getNextRunDate(now('Asia/Jakarta'), 0, false, 'Asia/Jakarta'));
                $schedules[] = ['name' => $name, 'expression' => $expression, 'next' => $next->timezone('Asia/Jakarta')];
            } catch (Throwable $e) {
                $schedules[] = ['name' => $name, 'expression' => $expression, 'next' => null];
            }
        }
        $enabled = (bool) config('seo_growth.enabled');
        $running = $workflow()->where('status', 'running')->where('created_at', '>=', now()->subHour())->exists();
        $stale = $latest?->status === 'running' && $latest->created_at->lt(now()->subHour());
        $status = ! $enabled ? 'INACTIVE' : ($running ? 'RUNNING' : ((in_array($latest?->status, ['failed', 'partial'], true)) ? 'FAILED' : 'ACTIVE'));
        return [
            'enabled' => $enabled, 'status' => $status, 'latest' => $latest,
            'last_success' => $lastSuccess, 'last_failure' => $lastFailure,
            'last_error' => $lastFailure ? $this->safeError($lastFailure) : null,
            'stale' => $stale, 'metrics' => $metrics,
            'has_today' => array_sum($metrics) > 0 || $today($workflow())->exists()
                || $today(ActivityLog::where('action', 'like', 'seo.%'))->exists(),
            'schedules' => $schedules,
            'configured' => count($schedules) === 2 && collect($schedules)->every(fn ($schedule) => $schedule['next'] !== null),
            'next' => $enabled ? collect($schedules)->pluck('next')->filter()->sort()->first() : null,
            'timeline' => $this->timeline(),
        ];
    }

    public function safeError(SeoGrowthRun $run): ?string
    {
        if (! in_array($run->status, ['partial', 'failed'], true)) {
            return null;
        }
        // Do not render stored free-text errors or provider responses.
        return $run->status === 'partial'
            ? 'Some stages were deferred or failed. Check processed stages, API limits and service configuration.'
            : 'SEO work failed. Check service configuration and API limits. Sensitive error details are withheld.';
    }

    private function timeline(): array
    {
        $columns = [
            BlogPost::class => ['id', 'title', 'status'],
            SeoOpportunity::class => ['id', 'topic', 'status', 'created_at'],
            BacklinkProspect::class => ['id', 'website_name', 'status', 'created_at'],
            SeoOutreachDraft::class => ['id', 'backlink_prospect_id', 'status', 'created_at'],
            MarketingContent::class => ['id', 'title', 'status', 'created_at'],
            SeoPageReview::class => ['id', 'blog_post_id', 'analyzed_at'],
            SeoGrowthRun::class => ['id', 'task', 'status', 'created_at', 'updated_at'],
        ];
        $logs = ActivityLog::where('action', 'like', 'seo.%')->with(['subject' => function ($relation) use ($columns) {
            $relation->constrain(array_map(fn ($fields) => fn ($query) => $query->select($fields), $columns));
            $relation->morphWith([SeoPageReview::class => ['post:id,title']]);
        }])->latest('id')->limit(20)->get();
        $items = $logs->map(fn ($log) => $this->item($log->created_at, $log->action, $log->description,
            $log->subject, $log->properties ?? []))->all();
        // Read-only fallback for real records created before activity logging was
        // introduced. No backfilled logs and no duplicate timeline entries.
        foreach ([
            SeoOpportunity::class => ['seo.content_opportunity', 'Created content opportunity', 'created_at'],
            BacklinkProspect::class => ['seo.backlink_prospect', 'Found backlink prospect', 'created_at'],
            SeoOutreachDraft::class => ['seo.outreach_draft', 'Created outreach draft', 'created_at'],
            MarketingContent::class => ['seo.distribution_pack', 'Created distribution pack', 'created_at'],
            SeoPageReview::class => ['seo.analysis', 'Analyzed article', 'analyzed_at'],
            SeoGrowthRun::class => ['seo.growth_run', 'SEO Growth run', 'updated_at'],
        ] as $model => [$action, $description, $column]) {
            $table = (new $model)->getTable();
            $query = $model::whereNotNull($column)->whereNotExists(function ($query) use ($model, $table, $action) {
                $query->selectRaw('1')->from('activity_logs')->whereColumn('subject_id', $table . '.id')
                    ->where('subject_type', $model)->where('action', 'like', $action . '%');
            });
            if ($model === MarketingContent::class) {
                $query->where('content_type', 'distribution');
            }
            if ($model === SeoGrowthRun::class) {
                $query->whereIn('task', ['daily', 'weekly']);
            }
            if ($model === SeoPageReview::class) {
                $query->with('post:id,title');
            }
            foreach ($query->orderByDesc($column)->limit(20)->get($columns[$model]) as $record) {
                $items[] = $this->item($record->$column, $action, $description, $record, []);
            }
        }
        usort($items, fn ($a, $b) => $b['time']->getTimestamp() <=> $a['time']->getTimestamp());
        return array_slice($items, 0, 20);
    }

    private function item(Carbon $time, string $action, string $description, ?Model $subject, array $properties): array
    {
        $url = null;
        $label = null;
        if ($subject instanceof BlogPost) {
            $url = route('admin.blog.edit', $subject);
            $label = $subject->getBase('title');
        } elseif ($subject instanceof SeoPageReview) {
            $url = $subject->post ? route('admin.blog.edit', $subject->blog_post_id) : null;
            $label = $subject->post?->getBase('title') ?? 'Article #' . $subject->blog_post_id;
        } elseif ($subject instanceof BacklinkProspect || $subject instanceof SeoOutreachDraft) {
            $id = $subject instanceof BacklinkProspect ? $subject->id : $subject->backlink_prospect_id;
            $url = route('admin.seo-growth.index', ['prospect_id' => $id]) . '#prospect-' . $id;
            $label = $subject instanceof BacklinkProspect ? $subject->website_name : 'Outreach draft #' . $subject->id;
        } elseif ($subject instanceof SeoOpportunity) {
            $url = route('admin.seo-growth.index', ['opportunity_id' => $subject->id]) . '#opportunity-' . $subject->id;
            $label = $subject->topic;
        } elseif ($subject instanceof MarketingContent) {
            $url = route('admin.seo-growth.index', ['pack_id' => $subject->id]) . '#pack-' . $subject->id;
            $label = $subject->title;
        } elseif ($subject instanceof SeoGrowthRun) {
            $url = route('admin.seo-growth.index', ['run_id' => $subject->id]) . '#run-' . $subject->id;
            $label = $subject->task . ' #' . $subject->id;
        }
        return ['time' => $time->copy()->timezone('Asia/Jakarta'), 'action' => $action,
            'description' => $description, 'url' => $url, 'label' => $label,
            'count' => isset($properties['count']) ? (int) $properties['count'] : null,
            'status' => $properties['status'] ?? $subject?->status];
    }
}
