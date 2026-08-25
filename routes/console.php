<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


use App\Models\MarketingContentIdea;
use App\Services\Marketing\MarketingContentGeneratorService;

Artisan::command('marketing:generate-content', function () {
    $this->info('Starting marketing content generation...');
    $this->newLine();

    $ideas = MarketingContentIdea::query()
        ->with('contents')
        ->where('status', 'idea')
        ->orderByDesc('priority')
        ->orderBy('id')
        ->get();

    if ($ideas->isEmpty()) {
        $this->warn('No eligible marketing content ideas found.');
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

            $failed++;
        }

        $this->newLine();
    }

    $this->newLine();
    $this->info('================ SUMMARY ================');
    $this->line('Generated : ' . $generated);
    $this->line('Skipped   : ' . $skipped);
    $this->line('Failed    : ' . $failed);

    return $failed > 0
        ? self::FAILURE
        : self::SUCCESS;
})->purpose('Generate marketing content from eligible content ideas');
