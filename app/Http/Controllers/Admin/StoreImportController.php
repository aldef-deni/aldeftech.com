<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreImport;
use App\Services\StoreZipImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Aldef Store ZIP import screens and the small JSON steps the browser drives:
 * create → chunk… → extract… → (preview) → run… → done.
 */
class StoreImportController extends Controller
{
    public function __construct(private readonly StoreZipImporter $importer)
    {
    }

    public function index()
    {
        $this->importer->expireStale();

        return view('admin.store.imports.index', [
            'imports' => StoreImport::with('user')->latest()->limit(20)->get(),
            'chunkBytes' => StoreZipImporter::chunkBytes(),
            'maxBytes' => StoreZipImporter::MAX_ZIP_BYTES,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|integer|min:1',
        ]);

        try {
            $import = $this->importer->start($data['name'], (int) $data['size'], auth()->id());
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'id' => $import->id,
            'chunk_url' => route('admin.store.imports.chunk', $import),
            'extract_url' => route('admin.store.imports.extract', $import),
            'show_url' => route('admin.store.imports.show', $import),
        ]);
    }

    public function chunk(Request $request, StoreImport $import): JsonResponse
    {
        $request->validate(['offset' => 'required|integer|min:0', 'chunk' => 'required|file']);

        try {
            $import = $this->importer->appendChunk($import, (int) $request->input('offset'), $request->file('chunk'));
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage(), 'received' => $import->fresh()->received], 409);
        }

        return response()->json(['received' => $import->received, 'status' => $import->status]);
    }

    public function extract(StoreImport $import): JsonResponse
    {
        $import = $this->importer->extractStep($import);

        return response()->json([
            'status' => $import->status,
            'done' => $import->cursor,
            'total' => $import->total,
            'message' => $import->error,
        ]);
    }

    /** Rebuild the preview, choosing whether the top folder is a wrapper. */
    public function plan(Request $request, StoreImport $import)
    {
        abort_unless($import->status === StoreImport::READY, 409);
        $this->importer->buildPlan($import, $request->boolean('strip_wrapper'));

        return redirect()->route('admin.store.imports.show', $import);
    }

    public function show(StoreImport $import)
    {
        return view('admin.store.imports.show', [
            'import' => $import,
            'counts' => $import->counts(),
        ]);
    }

    public function run(StoreImport $import): JsonResponse
    {
        abort_unless(in_array($import->status, [StoreImport::READY, StoreImport::IMPORTING, StoreImport::DONE], true), 409);

        $import = $this->importer->importStep($import);
        $counts = $import->counts();

        if ($import->status === StoreImport::DONE && ! ($import->summary['logged'] ?? false)) {
            ActivityLog::log('store.import.done', "ZIP import \"{$import->original_name}\": {$counts['created']} products created as drafts");
            $import->update(['summary' => array_merge($import->summary ?? [], ['logged' => true])]);
        }

        return response()->json([
            'status' => $import->status,
            'done' => $import->cursor,
            'total' => $import->total,
            'created' => $counts['created'],
        ]);
    }

    public function destroy(StoreImport $import)
    {
        $this->importer->cancel($import);

        return redirect()->route('admin.store.imports.index')->with('success', 'Import dibatalkan dan berkas sementaranya dihapus.');
    }
}
