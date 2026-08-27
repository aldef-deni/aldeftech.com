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

if (! function_exists('php_size_to_bytes')) {
    /**
     * Turn a php.ini shorthand size ("2M", "8M", "512K") into bytes.
     */
    function php_size_to_bytes(?string $value): int
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '-1') {
            return PHP_INT_MAX;
        }

        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => (int) $value,
        };
    }
}

if (! function_exists('configured_upload_bytes')) {
    /**
     * The limit the console advertises, straight from config.
     */
    function configured_upload_bytes(): int
    {
        return (int) config('aldeftech.upload.max_size', 5120) * 1024;
    }
}

if (! function_exists('max_upload_bytes')) {
    /**
     * The size limit that actually applies to an upload.
     *
     * A request larger than PHP's upload_max_filesize / post_max_size is
     * rejected by PHP (or the web server) before Laravel boots, so validation
     * rules never run and the browser lands on a raw 413 page. Enforcement
     * therefore has to respect the smallest of the three.
     */
    function max_upload_bytes(): int
    {
        return min(
            php_size_to_bytes(ini_get('upload_max_filesize')),
            php_size_to_bytes(ini_get('post_max_size')),
            configured_upload_bytes(),
        );
    }
}

if (! function_exists('format_upload_mb')) {
    /**
     * "5 MB", "2,5 MB" — Indonesian decimal comma, trailing zeros trimmed.
     */
    function format_upload_mb(int $bytes): string
    {
        $mb = $bytes / 1048576;

        return rtrim(rtrim(number_format($mb, 1, ',', '.'), '0'), ',') . ' MB';
    }
}

if (! function_exists('max_upload_label')) {
    /**
     * The limit shown on the upload fields: the app's own 5 MB, not whatever
     * php.ini happens to be set to on the machine rendering the page.
     */
    function max_upload_label(): string
    {
        return format_upload_mb(configured_upload_bytes());
    }
}

if (! function_exists('effective_upload_label')) {
    /**
     * What the server will really accept. Same as max_upload_label() on a
     * correctly configured host; lower when php.ini has not been raised, which
     * is exactly when a rejection message must not repeat the advertised 5 MB.
     */
    function effective_upload_label(): string
    {
        return format_upload_mb(max_upload_bytes());
    }
}
