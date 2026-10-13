<?php

namespace App\Services;

use App\Models\Media;
use App\Models\StoreBrand;
use App\Models\StoreCategory;
use App\Models\StoreImport;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

/**
 * Aldef Store bulk import from one ZIP of folders:
 *
 *   Brand/Kategori/Subkategori/Nama Produk/1.jpg, 2.jpg, video.mp4
 *
 * Kategori and Subkategori are optional (Brand/Nama Produk/ also works), and
 * a single wrapper folder around everything is ignored.
 *
 * Shared hosting cannot take a 500 MB request or a five-minute PHP run, so
 * each phase is split into short steps that the browser calls repeatedly:
 *   1. upload in chunks          appendChunk()
 *   2. extract a few files       extractStep()   → builds the preview plan
 *   3. create a few products     importStep()
 * Nothing touches the catalogue before step 3, and every product it creates
 * is a draft.
 */
class StoreZipImporter
{
    public function __construct(private readonly StoreImageOptimizer $optimizer)
    {
    }

    public const MAX_ZIP_BYTES = 500 * 1024 * 1024;
    public const CHUNK_BYTES = 5 * 1024 * 1024;
    private const MIN_CHUNK_BYTES = 512 * 1024;

    /** Guards against zip bombs and runaway archives. */
    public const MAX_ENTRIES = 20000;
    public const MAX_UNCOMPRESSED_BYTES = 2048 * 1024 * 1024;

    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    public const VIDEO_EXTENSIONS = ['mp4', 'webm'];

    /** Seconds of work per request; well under any host's time limit. */
    public const STEP_SECONDS = 15;

    /** System files that zip tools add and nobody meant to import. */
    private const JUNK = ['.ds_store', 'thumbs.db', 'desktop.ini'];

    // ── 1. Upload ──────────────────────────────────────────────────────────

    /**
     * Chunk size the browser should send: 5 MB, or less when PHP on this host
     * accepts less (cPanel defaults to 2M until public/.user.ini applies), so
     * the import works whatever the PHP limits are.
     */
    public static function chunkBytes(): int
    {
        $limit = min(
            php_size_to_bytes(ini_get('upload_max_filesize')),
            php_size_to_bytes(ini_get('post_max_size')) - 64 * 1024, // room for the form fields
        );

        return max(self::MIN_CHUNK_BYTES, min(self::CHUNK_BYTES, $limit));
    }

    public function start(string $name, int $size, ?int $userId): StoreImport
    {
        if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) !== 'zip') {
            throw new RuntimeException('Berkas harus berformat .zip.');
        }
        if ($size <= 0 || $size > self::MAX_ZIP_BYTES) {
            throw new RuntimeException('Ukuran ZIP maksimal 500 MB.');
        }
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP "zip" belum aktif di server. Hubungi penyedia hosting.');
        }

        $import = StoreImport::create([
            'user_id' => $userId,
            'original_name' => mb_substr(basename($name), 0, 255),
            'size' => $size,
            'status' => StoreImport::UPLOADING,
        ]);

        File::ensureDirectoryExists($import->workDir());
        touch($import->zipPath());

        return $import;
    }

    /**
     * Append one chunk at the given offset. A chunk the server already has
     * (a retry after a lost response) is accepted without writing it twice.
     */
    public function appendChunk(StoreImport $import, int $offset, UploadedFile $chunk): StoreImport
    {
        if ($import->status !== StoreImport::UPLOADING) {
            throw new RuntimeException('Unggahan ini tidak lagi menerima data.');
        }
        if (! $chunk->isValid() || $chunk->getSize() > self::CHUNK_BYTES) {
            throw new RuntimeException('Potongan unggahan tidak valid.');
        }

        $current = (int) @filesize($import->zipPath());

        if ($offset + $chunk->getSize() <= $current) {
            return $import; // already received
        }
        if ($offset !== $current) {
            throw new RuntimeException("Urutan unggahan tidak cocok (server {$current}, dikirim {$offset}).");
        }
        if ($current + $chunk->getSize() > $import->size) {
            throw new RuntimeException('Data yang dikirim melebihi ukuran berkas.');
        }

        $in = fopen($chunk->getRealPath(), 'rb');
        $out = fopen($import->zipPath(), 'ab');
        stream_copy_to_stream($in, $out);
        fclose($in);
        fclose($out);
        clearstatcache(true, $import->zipPath());

        $received = (int) filesize($import->zipPath());
        $import->update([
            'received' => $received,
            'status' => $received >= $import->size ? StoreImport::EXTRACTING : StoreImport::UPLOADING,
        ]);

        return $import;
    }

    // ── 2. Extract & plan ──────────────────────────────────────────────────

    /**
     * Extract entries for up to STEP_SECONDS, skipping anything that is not
     * a product photo or video. When the archive is exhausted, the ZIP is
     * deleted and the preview plan is built.
     */
    public function extractStep(StoreImport $import): StoreImport
    {
        if ($import->status !== StoreImport::EXTRACTING) {
            return $import;
        }

        $zip = new ZipArchive();
        if ($zip->open($import->zipPath()) !== true) {
            return $this->fail($import, 'Berkas ZIP rusak atau tidak dapat dibuka.');
        }

        $summary = $import->summary ?? ['extracted' => 0, 'ignored' => 0, 'notes' => []];

        if ($import->cursor === 0) {
            if ($zip->numFiles > self::MAX_ENTRIES) {
                $zip->close();

                return $this->fail($import, 'ZIP berisi lebih dari ' . number_format(self::MAX_ENTRIES, 0, ',', '.') . ' berkas.');
            }
            $uncompressed = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $uncompressed += (int) ($zip->statIndex($i)['size'] ?? 0);
            }
            if ($uncompressed > self::MAX_UNCOMPRESSED_BYTES) {
                $zip->close();

                return $this->fail($import, 'Isi ZIP setelah diekstrak lebih dari 2 GB.');
            }
            $import->total = $zip->numFiles;
            File::ensureDirectoryExists($import->filesDir());
        }

        $deadline = microtime(true) + self::STEP_SECONDS;
        $cursor = $import->cursor;

        while ($cursor < $zip->numFiles && microtime(true) < $deadline) {
            $stat = $zip->statIndex($cursor);
            $cursor++;

            $reason = $this->extractEntry($zip, $stat, $import->filesDir());
            if ($reason === null) {
                $summary['extracted']++;
            } elseif ($reason !== '') {
                $summary['ignored']++;
                if (count($summary['notes']) < 50) {
                    $summary['notes'][] = $reason;
                }
            }
        }

        $zip->close();
        $import->fill(['cursor' => $cursor, 'summary' => $summary])->save();

        if ($cursor >= $import->total) {
            @unlink($import->zipPath());
            $this->buildPlan($import);
        }

        return $import;
    }

    /**
     * Write one entry to disk. Returns null when extracted, '' for entries
     * silently skipped (folders, system files), or the reason it was refused.
     */
    private function extractEntry(ZipArchive $zip, array $stat, string $target): ?string
    {
        // Some Windows tools write backslashes; normalise before any check.
        $name = str_replace('\\', '/', (string) $stat['name']);

        if (str_ends_with($name, '/')) {
            return '';
        }

        $parts = array_values(array_filter(explode('/', $name), fn ($p) => $p !== ''));
        $file = strtolower(end($parts) ?: '');

        if ($parts === [] || in_array('__MACOSX', $parts, true) || str_starts_with($file, '._') || in_array($file, self::JUNK, true)) {
            return '';
        }

        // Zip slip: nothing may climb out of the target or start at the root.
        if (in_array('..', $parts, true) || str_starts_with($name, '/') || preg_match('/^[a-z]:/i', $name)) {
            return "{$name}: jalur tidak aman, diabaikan";
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $isImage = in_array($extension, self::IMAGE_EXTENSIONS, true);
        $isVideo = in_array($extension, self::VIDEO_EXTENSIONS, true);

        if (! $isImage && ! $isVideo) {
            return "{$name}: bukan foto/video (JPG, PNG, WebP, MP4, WebM), diabaikan";
        }

        $limit = (int) config($isImage ? 'aldeftech.upload.store_image_max_size' : 'aldeftech.upload.video_max_size', 51200) * 1024;
        if ((int) $stat['size'] > $limit) {
            return "{$name}: lebih dari " . format_upload_mb($limit) . ', diabaikan';
        }

        $relative = implode('/', array_map(fn ($p) => $this->cleanSegment($p), $parts));
        $path = $target . '/' . $relative;
        File::ensureDirectoryExists(dirname($path));

        $in = $zip->getStream($stat['name']);
        if (! $in) {
            return "{$name}: gagal dibaca dari ZIP";
        }
        $out = fopen($path, 'wb');
        stream_copy_to_stream($in, $out);
        fclose($in);
        fclose($out);

        $valid = $isImage
            ? @getimagesize($path) !== false
            : str_starts_with((string) (new \finfo(FILEINFO_MIME_TYPE))->file($path), 'video/');

        if (! $valid) {
            @unlink($path);

            return "{$name}: isi berkas bukan " . ($isImage ? 'foto' : 'video') . ' yang valid, diabaikan';
        }

        return null;
    }

    /** A folder or file name made safe for the local filesystem. */
    private function cleanSegment(string $segment): string
    {
        $segment = preg_replace('/[\x00-\x1F\x7F<>:"|?*]/u', '', $segment);
        $segment = trim(preg_replace('/\s+/u', ' ', $segment), " .");

        return $segment === '' ? '_' : mb_substr($segment, 0, 120);
    }

    /**
     * One entry per folder that directly holds photos or videos. The folders
     * above it name the brand, category and subcategory.
     *
     * A ZIP made by compressing a folder has that folder around everything
     * ("Katalog/Nike/..."). When the whole archive sits in one folder, that
     * folder is either a wrapper or the only brand. It is treated as a wrapper
     * automatically only when the folders run deeper than a brand allows;
     * otherwise the preview lets the admin decide ($stripWrapper).
     */
    public function buildPlan(StoreImport $import, ?bool $stripWrapper = null): void
    {
        $root = $import->filesDir();
        $groups = [];

        if (is_dir($root)) {
            foreach (File::allFiles($root) as $file) {
                $dir = trim(str_replace('\\', '/', $file->getRelativePath()), '/');
                $groups[$dir][] = $file->getFilename();
            }
        }

        $dirs = collect(array_keys($groups));
        $topLevel = $dirs->map(fn ($dir) => explode('/', $dir)[0])->unique();
        // One top folder holding no media itself: a wrapper, or the only brand.
        $candidate = $topLevel->count() === 1 && $topLevel->first() !== '' && ! array_key_exists($topLevel->first(), $groups)
            ? $topLevel->first()
            : null;
        // Wrapper/Brand/Kategori/Subkategori/Produk is the only way to be 4 deep.
        $strip = $candidate !== null && ($stripWrapper ?? $dirs->contains(fn ($dir) => substr_count($dir, '/') >= 4));
        $wrapper = $strip ? $candidate : null;

        $plan = [];
        $seen = [];

        ksort($groups, SORT_NATURAL | SORT_FLAG_CASE);
        foreach ($groups as $dir => $files) {
            $segments = $dir === '' ? [] : explode('/', $dir);
            if ($wrapper !== null) {
                array_shift($segments);
            }

            $name = array_pop($segments);
            $entry = [
                'folder' => $dir,
                'name' => $name,
                'brand' => $segments[0] ?? null,
                'category' => $segments[1] ?? null,
                'subcategory' => $segments[2] ?? null,
                'images' => [],
                'videos' => [],
                'state' => 'new',
                'note' => null,
            ];

            natcasesort($files);
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $key = in_array($ext, self::VIDEO_EXTENSIONS, true) ? 'videos' : 'images';
                $entry[$key][] = $file;
            }

            // Same cap as the product form: photos and videos together. Photos
            // are kept first, since the main photo has to come from them.
            $notes = [];
            $max = (int) config('aldeftech.upload.store_max_media', 50);
            $found = count($entry['images']) + count($entry['videos']);
            if ($found > $max) {
                $entry['images'] = array_slice($entry['images'], 0, $max);
                $entry['videos'] = array_slice($entry['videos'], 0, max(0, $max - count($entry['images'])));
                $notes[] = ($found - $max) . ' foto/video lebih dari ' . $max . ', tidak diambil';
            }

            if ($name === null || $entry['brand'] === null) {
                $entry['state'] = 'error';
                $notes = ['Foto/video harus berada di folder Brand/…/Nama Produk, bukan di luar folder brand'];
            } elseif (count($segments) > 3) {
                $entry['state'] = 'error';
                $notes = ['Folder terlalu dalam; maksimal Brand/Kategori/Subkategori/Nama Produk'];
            } elseif ($entry['images'] === []) {
                $entry['state'] = 'error';
                $notes = ['Tidak ada foto; minimal satu foto JPG, PNG, atau WebP'];
            } else {
                $key = mb_strtolower($entry['brand'] . '|' . $name);
                if (isset($seen[$key])) {
                    $entry['state'] = 'error';
                    $notes = ['Nama produk yang sama muncul dua kali untuk brand ini di ZIP'];
                } elseif ($this->productExists($entry['brand'], $name)) {
                    $entry['state'] = 'skip';
                    $notes = ['Produk dengan nama dan brand ini sudah ada, dilewati'];
                }
                $seen[$key] = true;
            }

            $entry['note'] = $notes ? implode('; ', $notes) : null;
            $plan[] = $entry;
        }

        $summary = $import->summary ?? [];
        $summary['wrapper'] = $wrapper;
        $summary['wrapper_candidate'] = $candidate;

        $import->fill([
            'status' => StoreImport::READY,
            'plan' => $plan,
            'summary' => $summary,
            'cursor' => 0,
            'total' => count($plan),
        ])->save();
    }

    private function productExists(string $brand, string $name): bool
    {
        return StoreProduct::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->whereHas('brand', fn ($q) => $q->whereRaw('LOWER(name) = ?', [mb_strtolower($brand)]))
            ->exists();
    }

    // ── 3. Import ──────────────────────────────────────────────────────────

    /**
     * Create products from the plan for up to STEP_SECONDS. Every product
     * is a draft; brands and categories missing so far are created.
     */
    public function importStep(StoreImport $import): StoreImport
    {
        if ($import->status === StoreImport::READY) {
            $import->update(['status' => StoreImport::IMPORTING]);
        }
        if ($import->status !== StoreImport::IMPORTING) {
            return $import;
        }

        $plan = $import->plan ?? [];
        $cursor = $import->cursor;
        $deadline = microtime(true) + self::STEP_SECONDS;

        while ($cursor < count($plan) && microtime(true) < $deadline) {
            $entry = &$plan[$cursor];
            $cursor++;

            if ($entry['state'] !== 'new') {
                $entry['result'] = $entry['state'] === 'skip' ? 'skipped' : 'failed';
                continue;
            }

            // Re-checked here: the catalogue may have changed since the preview.
            if ($this->productExists($entry['brand'], $entry['name'])) {
                $entry['result'] = 'skipped';
                $entry['note'] = 'Produk dengan nama dan brand ini sudah ada, dilewati';
                continue;
            }

            try {
                $product = $this->createProduct($import, $entry);
                $entry['result'] = 'created';
                $entry['product_id'] = $product->id;
                $entry['sku'] = $product->sku;
            } catch (\Throwable $e) {
                report($e);
                $entry['result'] = 'failed';
                $entry['note'] = 'Gagal dibuat: ' . Str::limit($e->getMessage(), 160);
            }
        }
        unset($entry);

        $done = $cursor >= count($plan);
        $import->fill([
            'plan' => $plan,
            'cursor' => $cursor,
            'status' => $done ? StoreImport::DONE : StoreImport::IMPORTING,
        ])->save();

        if ($done) {
            $this->cleanup($import);
        }

        return $import;
    }

    private function createProduct(StoreImport $import, array $entry): StoreProduct
    {
        $source = $import->filesDir() . '/' . $entry['folder'];
        $moved = [];

        try {
            return DB::transaction(function () use ($entry, $source, $import, &$moved) {
                $brand = StoreBrand::findOrCreateByName($entry['brand']);
                $category = $entry['category'] ? StoreCategory::findOrCreateByName($entry['category']) : null;
                if ($category && $entry['subcategory']) {
                    $category = StoreCategory::findOrCreateByName($entry['subcategory'], $category);
                }

                $product = StoreProduct::createWithSku([
                    'name' => mb_substr($entry['name'], 0, 255),
                    'slug' => StoreProduct::uniqueSlug($entry['name']),
                    'store_brand_id' => $brand->id,
                    'store_category_id' => $category?->id,
                    'size_system' => 'EU',
                    'status' => StoreProduct::STATUS_DRAFT,
                ]);

                $order = 0;
                $items = array_merge(
                    array_map(fn ($f) => ['image', $f], $entry['images']),
                    array_map(fn ($f) => ['video', $f], $entry['videos']),
                );

                foreach ($items as [$type, $file]) {
                    $path = $this->publishFile($source . '/' . $file, $file, $import->user_id);
                    $moved[] = $path;

                    StoreProductImage::create([
                        'store_product_id' => $product->id,
                        'type' => $type,
                        'path' => $path,
                        'is_primary' => $order === 0,
                        'sort_order' => $order++,
                    ]);
                }

                return $product;
            });
        } catch (\Throwable $e) {
            // The rows rolled back; do not leave their files behind.
            foreach ($moved as $path) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }

    /** Move an extracted file into public media/ under a fresh name. */
    private function publishFile(string $from, string $original, ?int $userId): string
    {
        $extension = strtolower(pathinfo($from, PATHINFO_EXTENSION));
        $filename = Str::uuid() . '.' . $extension;
        $path = 'media/' . $filename;
        $disk = Storage::disk('public');

        File::ensureDirectoryExists(dirname($disk->path($path)));
        if (! @rename($from, $disk->path($path)) && ! File::copy($from, $disk->path($path))) {
            throw new RuntimeException("Berkas {$original} gagal dipindahkan.");
        }

        // Large camera photos are resized for the web, as in the product form.
        if (in_array($extension, self::IMAGE_EXTENSIONS, true)) {
            $kept = $this->optimizer->optimize($disk->path($path));
            if ($kept !== $disk->path($path)) {
                $filename = basename($kept);
                $path = 'media/' . $filename;
            }
        }

        Media::create([
            'filename' => $filename,
            'original_name' => mb_substr($original, 0, 255),
            'mime_type' => (string) (new \finfo(FILEINFO_MIME_TYPE))->file($disk->path($path)),
            'size' => (int) filesize($disk->path($path)),
            'disk' => 'public',
            'path' => $path,
            'uploaded_by' => $userId,
        ]);

        return $path;
    }

    // ── Housekeeping ───────────────────────────────────────────────────────

    public function cancel(StoreImport $import): void
    {
        $this->cleanup($import);
        if ($import->isActive()) {
            $import->update(['status' => StoreImport::CANCELLED]);
        }
    }

    /** Imports left unfinished for a day lose their working files. */
    public function expireStale(): void
    {
        StoreImport::whereIn('status', StoreImport::ACTIVE)
            ->where('updated_at', '<', now()->subDay())
            ->get()
            ->each(function (StoreImport $import) {
                $this->cleanup($import);
                $import->update(['status' => StoreImport::EXPIRED]);
            });
    }

    public function cleanup(StoreImport $import): void
    {
        File::deleteDirectory($import->workDir());
    }

    private function fail(StoreImport $import, string $message): StoreImport
    {
        $this->cleanup($import);
        $import->update(['status' => StoreImport::FAILED, 'error' => $message]);

        return $import;
    }
}
