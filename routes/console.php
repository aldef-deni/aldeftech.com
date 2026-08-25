<?php

use App\Models\MarketingCampaign;
use App\Models\MarketingContentIdea;
use App\Services\Marketing\MarketingContentGeneratorService;
use App\Services\Marketing\MarketingIntelligenceService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

<<<<<<< HEAD
Artisan::command('marketing:generate-ideas {campaign?} {--limit=12}', function () {
    $campaignId = $this->argument('campaign');
    $limit = (int) $this->option('limit');

    $query = MarketingCampaign::query()
        ->where('status', 'active')
        ->orderByDesc('priority');

    if ($campaignId) {
        $query->whereKey($campaignId);
    }

    $campaigns = $query->get();

    if ($campaigns->isEmpty()) {
        $this->warn('No active marketing campaign found.');
        return 0;
    }

    $intelligence = app(MarketingIntelligenceService::class);

    foreach ($campaigns as $campaign) {
        $ideas = $intelligence->generateIdeas($campaign, $limit);
        $this->info("Campaign #{$campaign->id}: {$ideas->count()} ideas prepared.");
    }

    return 0;
})->purpose('Generate AI marketing ideas for active campaigns');

Artisan::command('marketing:generate-content {--limit=5}', function () {
=======

use App\Models\MarketingContentIdea;
use App\Services\Marketing\MarketingContentGeneratorService;

Artisan::command('marketing:generate-content', function () {
>>>>>>> a4f16a9b4c3a6b5d155af1e7ac7ccd6e601bdec4
    $this->info('Starting marketing content generation...');
    $this->newLine();

    $ideas = MarketingContentIdea::query()
        ->with('contents')
        ->where('status', 'idea')
<<<<<<< HEAD
        ->whereDoesntHave('contents')
        ->orderByDesc('priority')
        ->orderBy('id')
        ->limit((int) $this->option('limit'))
=======
        ->orderByDesc('priority')
        ->orderBy('id')
>>>>>>> a4f16a9b4c3a6b5d155af1e7ac7ccd6e601bdec4
        ->get();

    if ($ideas->isEmpty()) {
        $this->warn('No eligible marketing content ideas found.');
<<<<<<< HEAD
        return 0;
    }

    $generator = app(MarketingContentGeneratorService::class);
    $generated = 0;
    $failed = 0;

    foreach ($ideas as $idea) {
        $this->line("[IDEA #{$idea->id}] {$idea->title}");

        try {
            $content = $generator->generate($idea);
            $this->info("  GENERATED: Content #{$content->id}");
            $generated++;
        } catch (\Throwable $e) {
            $this->error('  FAILED: ' . $e->getMessage());
=======
        return self::SUCCESS;
    }

    $generator = app(MarketingContentGeneratorService::class);

    $generated = 0;
    $skipped = 0;
    $failed = 0;

    foreach ($ideas as $idea) {
        $this->line(
            sprintf(
                '[IDEA #%d] %s',
                $idea->id,
                $idea->title
            )
        );

        if ($idea->contents->isNotEmpty()) {
            $this->comment('  → SKIP: content already exists.');
            $skipped++;
            continue;
        }

        try {
            $content = $generator->generate($idea);

            $this->info(
                '  → GENERATED: Content #' . $content->id
            );

            $generated++;
        } catch (\Throwable $e) {
            $this->error(
                '  → FAILED: ' . $e->getMessage()
            );

>>>>>>> a4f16a9b4c3a6b5d155af1e7ac7ccd6e601bdec4
            $failed++;
        }

        $this->newLine();
    }

<<<<<<< HEAD
    $this->info('================ SUMMARY ================');
    $this->line('Generated : ' . $generated);
    $this->line('Failed    : ' . $failed);

    return $failed > 0 ? 1 : 0;
})->purpose('Generate marketing content from eligible content ideas');

Artisan::command('marketing:run {--ideas=12} {--content=3}', function () {
    $this->call('marketing:generate-ideas', [
        '--limit' => (int) $this->option('ideas'),
    ]);

    return $this->call('marketing:generate-content', [
        '--limit' => (int) $this->option('content'),
    ]);
})->purpose('Run the daily AI marketing workflow');

Schedule::command('marketing:run --ideas=12 --content=3')
    ->dailyAt('09:00')
    ->withoutOverlapping();
=======
    $this->newLine();
    $this->info('================ SUMMARY ================');
    $this->line('Generated : ' . $generated);
    $this->line('Skipped   : ' . $skipped);
    $this->line('Failed    : ' . $failed);

    return $failed > 0
        ? self::FAILURE
        : self::SUCCESS;
})->purpose('Generate marketing content from eligible content ideas');
>>>>>>> a4f16a9b4c3a6b5d155af1e7ac7ccd6e601bdec4
