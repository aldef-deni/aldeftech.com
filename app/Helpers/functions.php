<?php

use Illuminate\Support\Str;

if (! function_exists('media_url')) {
    /**
     * Resolve an image reference that may come from three different eras of
     * this project: an absolute URL, a path already living under public/
     * (e.g. "images/portfolio/foo.webp"), or an uploaded file relative to the
     * public disk (e.g. "portfolio/foo.webp").
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return $fallback ? asset(ltrim($fallback, '/')) : null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        // Already a public asset (images/…, assets/…, storage/…)
        if (Str::startsWith($path, ['images/', 'assets/', 'storage/', 'build/'])) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }
}

if (! function_exists('excerpt_text')) {
    /**
     * Strip markup and clamp to a readable length for card summaries.
     */
    function excerpt_text(?string $text, int $limit = 140): string
    {
        return Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text))), $limit);
    }
}

if (! function_exists('initials_of')) {
    function initials_of(?string $name, int $max = 2): string
    {
        $parts = preg_split('/\s+/u', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return Str::upper(implode('', array_map(
            fn ($p) => Str::substr($p, 0, 1),
            array_slice($parts, 0, $max)
        ))) ?: '·';
    }
}
