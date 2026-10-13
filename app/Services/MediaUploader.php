<?php

namespace App\Services;

use App\Exceptions\MediaUploadException;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Single place where an uploaded image is checked and put on disk.
 *
 * Callers get back a Media row; what the forms actually store is its `path`,
 * so every image column stays a plain string. media_url() resolves that path
 * for display and still understands the hand-typed "images/…" values that
 * existed before uploads moved inline.
 *
 * Aldef Store product galleries use two more kinds with their own limits:
 * videos, and product photos (large camera files, resized for the web).
 */
class MediaUploader
{
    public const KIND_IMAGE = 'image';
    public const KIND_VIDEO = 'video';
    public const KIND_STORE_IMAGE = 'store-image';

    public const STORE_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public function __construct(private readonly StoreImageOptimizer $optimizer)
    {
    }

    public static function kinds(): array
    {
        return [self::KIND_IMAGE, self::KIND_VIDEO, self::KIND_STORE_IMAGE];
    }

    public static function maxBytes(string $kind): int
    {
        return match ($kind) {
            self::KIND_VIDEO => max_video_upload_bytes(),
            self::KIND_STORE_IMAGE => max_store_image_upload_bytes(),
            default => max_upload_bytes(),
        };
    }

    /**
     * @throws MediaUploadException when the file is missing, too large, or not an accepted format
     */
    public function store(?UploadedFile $file, ?int $userId = null, string $kind = self::KIND_IMAGE): Media
    {
        $maxBytes = self::maxBytes($kind);
        $limit = format_upload_mb($maxBytes);

        if (! $file) {
            throw new MediaUploadException('Tidak ada berkas yang dipilih.');
        }

        // An upload over upload_max_filesize arrives as an UploadedFile carrying
        // an error code instead of a readable temp file.
        if (! $file->isValid()) {
            throw new MediaUploadException(match ($file->getError()) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Ukuran berkas melebihi batas {$limit}.",
                UPLOAD_ERR_PARTIAL => 'Berkas hanya terkirim sebagian. Silakan coba lagi.',
                UPLOAD_ERR_NO_FILE => 'Tidak ada berkas yang dipilih.',
                default => 'Berkas gagal diunggah: ' . $file->getErrorMessage(),
            });
        }

        if ($file->getSize() > $maxBytes) {
            throw new MediaUploadException("Ukuran berkas melebihi batas {$limit}.");
        }

        $allowed = match ($kind) {
            self::KIND_VIDEO => config('aldeftech.upload.video_mimes', ['mp4', 'webm']),
            self::KIND_STORE_IMAGE => self::STORE_IMAGE_EXTENSIONS,
            default => config('aldeftech.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp']),
        };
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $allowed, true)) {
            throw new MediaUploadException(
                'Format berkas tidak didukung. Yang diterima: ' . implode(', ', $allowed) . '.'
            );
        }

        // A renamed file is not a video or a photo: check what the bytes are.
        if ($kind === self::KIND_VIDEO && ! str_starts_with((string) $file->getMimeType(), 'video/')) {
            throw new MediaUploadException('Berkas ini bukan video yang valid. Gunakan MP4 atau WebM.');
        }
        if ($kind === self::KIND_STORE_IMAGE && @getimagesize($file->getRealPath()) === false) {
            throw new MediaUploadException('Berkas ini bukan foto yang valid. Gunakan JPG, PNG, atau WebP.');
        }

        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs('media', $filename, 'public');
        $mime = $file->getMimeType();
        $size = $file->getSize();

        if ($kind === self::KIND_STORE_IMAGE) {
            $disk = Storage::disk('public');
            $kept = $this->optimizer->optimize($disk->path($path));
            if ($kept !== $disk->path($path)) {
                $filename = basename($kept);
                $path = 'media/' . $filename;
                $mime = 'image/webp';
                $size = (int) filesize($kept);
            }
        }

        return Media::create([
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mime,
            'size' => $size,
            'disk' => 'public',
            'path' => $path,
            'uploaded_by' => $userId,
        ]);
    }
}
