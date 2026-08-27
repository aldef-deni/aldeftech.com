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
 */
class MediaUploader
{
    /**
     * @throws MediaUploadException when the file is missing, too large, or not an accepted format
     */
    public function store(?UploadedFile $file, ?int $userId = null): Media
    {
        $limit = effective_upload_label();

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

        if ($file->getSize() > max_upload_bytes()) {
            throw new MediaUploadException("Ukuran berkas melebihi batas {$limit}.");
        }

        $allowed = config('aldeftech.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $allowed, true)) {
            throw new MediaUploadException(
                'Format berkas tidak didukung. Yang diterima: ' . implode(', ', $allowed) . '.'
            );
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
