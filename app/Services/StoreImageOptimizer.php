<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Turns a large product photo into a web-sized WebP.
 *
 * Aldef Store accepts photos up to 50 MB, but a visitor's phone should not
 * download a 50 MB camera file per thumbnail. The long edge is capped at the
 * configured image_max_width / image_max_height (2400 px), phone photos are
 * turned upright from their EXIF orientation, and the result is saved as
 * WebP when that is smaller. Anything that cannot be processed safely (GD or
 * WebP missing, not enough memory to decode it) is left as uploaded: the
 * upload never fails because of optimisation.
 */
class StoreImageOptimizer
{
    private const QUALITY = 82;

    /** Small, already web-sized files are left alone. */
    private const KEEP_BELOW_BYTES = 600 * 1024;

    /**
     * @param  string  $path  absolute path of the stored image
     * @return string the absolute path of the image to keep (new file when optimised)
     */
    public function optimize(string $path): string
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagewebp')) {
            return $path;
        }

        $info = @getimagesize($path);
        if (! $info) {
            return $path;
        }

        [$width, $height, $type] = $info;
        $maxW = (int) config('aldeftech.upload.image_max_width', 2400);
        $maxH = (int) config('aldeftech.upload.image_max_height', 2400);
        $orientation = $this->orientation($path, $type);
        $tooBig = $width > $maxW || $height > $maxH;

        if (! $tooBig && $orientation <= 1 && filesize($path) <= self::KEEP_BELOW_BYTES) {
            return $path;
        }

        if (! $this->fitsInMemory($width, $height)) {
            return $path;
        }

        $source = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
        if (! $source) {
            return $path;
        }

        $source = $this->upright($source, $orientation);
        $width = imagesx($source);
        $height = imagesy($source);

        $scale = min(1, $maxW / $width, $maxH / $height);
        $targetW = max(1, (int) round($width * $scale));
        $targetH = max(1, (int) round($height * $scale));

        $target = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetW, $targetH, $width, $height);
        imagedestroy($source);

        $optimized = dirname($path) . '/' . Str::uuid() . '.webp';
        $ok = @imagewebp($target, $optimized, self::QUALITY);
        imagedestroy($target);

        // Keep the original only when re-encoding did not help, and it did
        // not need resizing or rotating either.
        if (! $ok || ! is_file($optimized) || (! $tooBig && $orientation <= 1 && filesize($optimized) >= filesize($path))) {
            @unlink($optimized);

            return $path;
        }

        @unlink($path);

        return $optimized;
    }

    /** EXIF orientation of a JPEG (1 = upright), when the exif extension exists. */
    private function orientation(string $path, int $type): int
    {
        if ($type !== IMAGETYPE_JPEG || ! function_exists('exif_read_data')) {
            return 1;
        }

        $exif = @exif_read_data($path);

        return (int) ($exif['Orientation'] ?? 1);
    }

    private function upright(\GdImage $image, int $orientation): \GdImage
    {
        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => null,
        };

        if ($rotated) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }

    /**
     * Decoding needs about 5 bytes a pixel for the source plus the resized
     * copy; a 50-megapixel photo is roughly 250 MB. Refuse rather than let
     * PHP hit memory_limit and kill the request.
     */
    private function fitsInMemory(int $width, int $height): bool
    {
        $limit = php_size_to_bytes((string) ini_get('memory_limit'));
        if ($limit <= 0) {
            return true; // unlimited
        }

        $maxW = (int) config('aldeftech.upload.image_max_width', 2400);
        $needed = ($width * $height * 5) + ($maxW * $maxW * 5) + 16 * 1024 * 1024;

        return memory_get_usage(true) + $needed < $limit * 0.9;
    }
}
