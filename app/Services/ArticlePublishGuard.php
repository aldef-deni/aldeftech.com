<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Decides whether a generated article may be published without a human.
 *
 * The generator already throws on unsafe HTML and on unsupported claims, so
 * nothing reaching this guard is content-unsafe. What the guard adds is the
 * publication contract that both entry points — the admin generator and the
 * scheduled runner — must agree on:
 *
 *   - a title and a slug,
 *   - real article prose (not empty, not a raw JSON/markdown payload),
 *   - complete SEO metadata (excerpt, meta title, meta description),
 *   - no duplicate slug, and no title that already exists as an article.
 *
 * Anything failing one of those is saved as a draft for an editor and is never
 * auto-published. Every reason below is a fixed application literal, so the
 * list is safe to log, store on ai_content_runs or show an admin.
 */
class ArticlePublishGuard
{
    /** Shortest acceptable meta description; anything under this is "missing". */
    private const MIN_META_DESCRIPTION = 50;

    /** Token-overlap and similarity thresholds shared with the scheduler. */
    private const TITLE_OVERLAP = 0.7;
    private const TITLE_SIMILARITY = 85;

    public const AUTO_PUBLISH_DISABLED = 'publikasi otomatis dinonaktifkan';

    public function autoPublishEnabled(): bool
    {
        return (bool) config('ai_article.auto_publish', true);
    }

    public function minWords(): int
    {
        return max(50, (int) config('ai_article.min_words', 350));
    }

    /**
     * @return array{publishable: bool, reasons: array<int, string>}
     */
    public function evaluate(array $article, ?int $ignorePostId = null): array
    {
        $title = trim((string) ($article['title'] ?? ''));
        $slug = trim((string) ($article['slug'] ?? ''));
        $content = (string) ($article['content'] ?? '');
        $reasons = [];

        if ($title === '') {
            $reasons[] = 'judul kosong';
        }

        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            $reasons[] = 'slug kosong atau tidak valid';
        }

        if ($this->looksLikeRawPayload($content)) {
            $reasons[] = 'konten bukan HTML artikel';
        } elseif ($this->wordCount($content) < $this->minWords()) {
            $reasons[] = 'konten lebih pendek dari ' . $this->minWords() . ' kata';
        }

        if (trim(strip_tags((string) ($article['excerpt'] ?? ''))) === '') {
            $reasons[] = 'excerpt kosong';
        }

        if (trim(strip_tags((string) ($article['meta_title'] ?? ''))) === '') {
            $reasons[] = 'meta title kosong';
        }

        if (mb_strlen(trim(strip_tags((string) ($article['meta_description'] ?? '')))) < self::MIN_META_DESCRIPTION) {
            $reasons[] = 'meta description tidak lengkap';
        }

        if ($slug !== '' && $this->slugTaken($slug, $ignorePostId)) {
            $reasons[] = 'slug sudah dipakai';
        }

        if ($title !== '' && $this->titleDuplicates($title, $ignorePostId)) {
            $reasons[] = 'judul mirip artikel lain';
        }

        return ['publishable' => $reasons === [], 'reasons' => $reasons];
    }

    /**
     * The status/published_at pair to persist, plus why a draft was chosen.
     *
     * @return array{status: string, published_at: Carbon|null, reasons: array<int, string>}
     */
    public function decision(array $article, ?int $ignorePostId = null): array
    {
        $result = $this->evaluate($article, $ignorePostId);
        $reasons = $result['reasons'];
        $publish = $result['publishable'] && $this->autoPublishEnabled();

        if ($result['publishable'] && ! $this->autoPublishEnabled()) {
            $reasons[] = self::AUTO_PUBLISH_DISABLED;
        }

        return [
            'status' => $publish ? 'published' : 'draft',
            'published_at' => $publish ? now() : null,
            'reasons' => $reasons,
        ];
    }

    /** @param  array<int, string>  $reasons */
    public function summary(array $reasons): string
    {
        return $reasons === [] ? 'lolos validasi publikasi' : implode('; ', array_unique($reasons));
    }

    /**
     * A human-readable, collision-free slug.
     *
     * The generator used to append a UUID, which produced long, indexable-URL
     * hostile paths like ...-automation-d39a35fb-1266-46b9-be30-3e25d5404493.
     * A numbered suffix keeps the URL short and readable.
     */
    public function uniqueSlug(string $titleOrSlug, ?int $ignorePostId = null): string
    {
        $base = Str::limit(Str::slug($titleOrSlug), 180, '') ?: 'artikel';
        $slug = $base;
        $suffix = 2;

        while ($this->slugTaken($slug, $ignorePostId)) {
            $slug = mb_substr($base, 0, 170) . '-' . $suffix++;
        }

        return $slug;
    }

    private function slugTaken(string $slug, ?int $ignorePostId): bool
    {
        // Trashed rows keep their slug in the unique index, so they count too.
        return BlogPost::withTrashed()
            ->where('slug', $slug)
            ->when($ignorePostId, fn ($query) => $query->whereKeyNot($ignorePostId))
            ->exists();
    }

    private function titleDuplicates(string $title, ?int $ignorePostId): bool
    {
        $normalized = Str::slug($title, ' ');

        if ($normalized === '') {
            return false;
        }

        $tokens = array_unique(explode(' ', $normalized));

        $existing = BlogPost::withTrashed()
            ->when($ignorePostId, fn ($query) => $query->whereKeyNot($ignorePostId))
            ->latest('id')
            ->limit(200)
            ->pluck('title');

        foreach ($existing as $other) {
            $otherSlug = Str::slug((string) $other, ' ');

            if ($otherSlug === '') {
                continue;
            }

            if ($otherSlug === $normalized) {
                return true;
            }

            $otherTokens = array_unique(explode(' ', $otherSlug));
            $overlap = count(array_intersect($tokens, $otherTokens))
                / max(1, count(array_unique(array_merge($tokens, $otherTokens))));
            similar_text($normalized, $otherSlug, $similarity);

            if ($overlap >= self::TITLE_OVERLAP || $similarity >= self::TITLE_SIMILARITY) {
                return true;
            }
        }

        return false;
    }

    private function wordCount(string $html): int
    {
        $text = trim((string) preg_replace('/\s+/u', ' ', strip_tags($html)));

        return $text === '' ? 0 : count(preg_split('/\s+/u', $text));
    }

    private function looksLikeRawPayload(string $content): bool
    {
        if (trim($content) === '' || ! preg_match('/<(?:p|h2|h3|ul|ol|blockquote)\b/i', $content)) {
            return true;
        }

        // Markdown fences, the Markdown heading/bold syntax, or a payload that
        // is really the raw JSON response object rather than rendered article.
        if (preg_match('/```|\*\*|(?:^|\n)\s*#{1,6}\s|^\s*[\[{]/', $content)) {
            return true;
        }

        return (bool) preg_match('/"\s*(?:title|slug|content|meta_title|meta_description)\s*"\s*:/', $content);
    }
}
