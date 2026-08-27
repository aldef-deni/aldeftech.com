<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('original_name', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            $type = $request->input('type');
            $query->where('mime_type', 'like', "{$type}%");
        }

        $media = $query->latest()->paginate(24)->withQueryString();

        return view('admin.media.index', ['media' => $media]);
    }

    public function upload(Request $request)
    {
        $allowedMimes = config('aldeftech.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $limit = max_upload_label();

        // The Media screen has no form fields to hang inline errors on, so every
        // failure is reported as a flash message rather than a validation bag.
        $fail = fn (string $message) => $request->ajax()
            ? response()->json(['success' => false, 'message' => $message], 422)
            : back()->with('error', $message);

        // A request larger than post_max_size reaches PHP with $_POST and $_FILES
        // already emptied, so there is nothing left to validate — only the raw
        // Content-Length still tells us what happened.
        if (empty($_FILES) && $request->server('CONTENT_LENGTH') > 0) {
            return $fail("Berkas melebihi batas {$limit} dan ditolak server sebelum sempat diproses.");
        }

        $file = $request->file('file');

        if (!$file) {
            return $fail('Tidak ada berkas yang dipilih.');
        }

        // An upload over upload_max_filesize arrives as an UploadedFile carrying
        // an error code instead of a readable temp file.
        if (!$file->isValid()) {
            $message = match ($file->getError()) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Ukuran berkas melebihi batas {$limit}.",
                UPLOAD_ERR_PARTIAL => 'Berkas hanya terkirim sebagian. Silakan coba lagi.',
                UPLOAD_ERR_NO_FILE => 'Tidak ada berkas yang dipilih.',
                default => 'Berkas gagal diunggah: ' . $file->getErrorMessage(),
            };

            return $fail($message);
        }

        if ($file->getSize() > max_upload_bytes()) {
            return $fail("Ukuran berkas melebihi batas {$limit}.");
        }

        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedMimes, true)) {
            return $fail('Format berkas tidak didukung. Format yang diterima: ' . implode(', ', $allowedMimes) . '.');
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('media', $filename, 'public');

        $media = Media::create([
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'disk' => 'public',
            'path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'path' => $media->path,
                'url' => $media->url,
                'id' => $media->id,
            ]);
        }

        return back()->with('success', 'Berkas "' . $media->original_name . '" berhasil diunggah.');
    }

    public function destroy(Media $media)
    {
        $path = storage_path('app/public/' . $media->path);
        if (file_exists($path)) {
            unlink($path);
        }

        $media->delete();

        return back()->with('success', 'Berkas berhasil dihapus.');
    }
}
