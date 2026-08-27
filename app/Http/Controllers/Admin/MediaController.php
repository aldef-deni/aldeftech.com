<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\MediaUploadException;
use App\Http\Controllers\Controller;
use App\Services\MediaUploader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Backs the inline uploader in <x-admin.form.image>.
 *
 * There is no media library screen any more: an editor picks an image on the
 * form they are already filling in, and the field keeps the returned path.
 */
class MediaController extends Controller
{
    public function __construct(private readonly MediaUploader $uploader)
    {
    }

    public function store(Request $request): JsonResponse
    {
        // A request larger than post_max_size reaches PHP with $_POST and $_FILES
        // already emptied, so there is nothing left to validate — only the raw
        // Content-Length still tells us what happened.
        if (empty($_FILES) && (int) $request->server('CONTENT_LENGTH') > 0) {
            return response()->json([
                'message' => 'Berkas melebihi batas ' . effective_upload_label()
                    . ' dan ditolak server sebelum sempat diproses.',
            ], 413);
        }

        try {
            $media = $this->uploader->store($request->file('file'), auth()->id());
        } catch (MediaUploadException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'path' => $media->path,
            'url' => $media->url,
            'name' => $media->original_name,
        ]);
    }
}
