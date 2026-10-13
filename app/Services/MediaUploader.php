<?php

namespace App\Services;

use App\Exceptions\MediaUploadException;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Single place where an uploaded image is checked and put on disk.
 *
 * Callers get back a Media row; what the forms actually store is its `path`,
 * so every image column stays a plain string. media_url() resolves that path
 * for display and still understands the hand-typed "images/…" values that
 * existed before uploads moved inline.
 *
 * Videos (Aldef Store product galleries) go through the same path with their
 * own formats and size limit.
 */
class MediaUploader
{
    public const KIND_IMAGE = 'image';
    public const KIND_VIDEO = 'video';

    /**
     * @throws MediaUploadException when the file is missing, too large, or not an accepted format
     */
    public function store(?UploadedFile $file, ?int $userId = null, string $kind = self::KIND_IMAGE): Media
    {
        $isVideo = $kind === self::KIND_VIDEO;
        $maxBytes = $isVideo ? max_video_upload_bytes() : max_upload_bytes();
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

        $allowed = $isVideo
            ? config('aldeftech.upload.video_mimes', ['mp4', 'webm'])
            : config('aldeftech.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $allowed, true)) {
            throw new MediaUploadException(
                'Format berkas tidak didukung. Yang diterima: ' . implode(', ', $allowed) . '.'
            );
        }

        // A renamed file is not a video: check what the bytes actually are.
        if ($isVideo && ! str_starts_with((string) $file->getMimeType(), 'video/')) {
            throw new MediaUploadException('Berkas ini bukan video yang valid. Gunakan MP4 atau WebM.');
        }

        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs('media', $filename, 'public');

        return Media::create([
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'disk' => 'public',
            'path' => $path,
            'uploaded_by' => $userId,
        ]);
    }
}
