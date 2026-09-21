<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:articles-clean-slugs {--dry-run : Report what would change without touching the database}')]
#[Description('Strip the UUID suffix from article slugs, record the old address for a 301')]
class ArticlesCleanSlugsCommand extends Command
{
    /**
     * Articles generated before the slug rule was tightened are addressed by
     * "judul-artikel-<uuid>". The UUID helps nobody: it is not the article's id,
     * it carries no keyword, and it makes every shared link look like a machine
     * wrote it. This rewrites those slugs to the clean form and stores the old
     * one so the old address answers 301 instead of 404 — the URLs are already
     * in Google's index and must not simply vanish.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        // withTrashed: a soft-deleted article's URL was public once, and it must
        // not later be handed to a different article.
        $posts = BlogPost::withTrashed()->orderBy('id')->get();

        $renamed = 0;
        $skipped = 0;

        foreach ($posts as $post) {
            $clean = BlogPost::cleanSlug((string) $post->slug);

            if ($clean === '' || $clean === $post->slug) {
                $skipped++;
                continue;
            }

            $target = BlogPost::uniqueSlug($clean, $post->getKey());

            $this->line(sprintf(
                '%s %s -> %s%s',
                $dryRun ? '[dry-run]' : '[renamed]',
                $post->slug,
                $target,
                $post->trashed() ? ' (trashed)' : ''
            ));

            if (! $dryRun) {
                // Saving is enough: BlogPost cleans the slug, keeps it unique and
                // records the slug it just left, which is what serves the 301.
                $post->slug = $target;
                $post->save();
            }

            $renamed++;
        }

        $this->newLine();
        $this->info($dryRun
            ? "{$renamed} slug(s) would be cleaned, {$skipped} already clean. Nothing was written."
            : "{$renamed} slug(s) cleaned and recorded for redirect, {$skipped} already clean.");

        return self::SUCCESS;
    }
}
