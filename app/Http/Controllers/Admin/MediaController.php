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
        $request->validate([
            'file' => 'required|file|max:' . (config('aldeftech.upload.max_size', 5120)),
        ]);

        $file = $request->file('file');
        $allowedMimes = config('aldeftech.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

        if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
            return back()->withErrors(['file' => 'File type not allowed.']);
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

        return back()->with('success', 'File uploaded successfully.');
    }

    public function destroy(Media $media)
    {
        $path = storage_path('app/public/' . $media->path);
        if (file_exists($path)) {
            unlink($path);
        }

        $media->delete();

        return back()->with('success', 'Media deleted.');
    }
}
