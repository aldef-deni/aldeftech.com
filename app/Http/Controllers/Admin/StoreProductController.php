<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use App\Models\StoreProductVariant;
use App\Services\StoreWhatsApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Aldef Store products. There is no price field on purpose: the catalogue
 * sends visitors to WhatsApp to ask for price and stock.
 */
class StoreProductController extends Controller
{
    private const MAX_IMAGES = 12;
    private const MAX_VIDEOS = 3;
    private const MAX_SIZES = 40;
    private const MAX_COLORS = 20;
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $products = StoreProduct::with(['category.parent', 'images', 'variants'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(fn ($q) => $q->where('name', 'like', $like)
                    ->orWhere('sku', 'like', $like)
                    ->orWhere('brand', 'like', $like));
            })
            ->when(in_array($status, [StoreProduct::STATUS_DRAFT, StoreProduct::STATUS_PUBLISHED], true),
                fn ($query) => $query->where('status', $status))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.store.products.index', [
            'products' => $products,
            'filters' => ['q' => $search, 'status' => $status],
            'waNumber' => StoreWhatsApp::number(),
        ]);
    }

    public function create()
    {
        return view('admin.store.products.create', [
            'categoryOptions' => StoreCategory::treeOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $product = DB::transaction(function () use ($request, $data) {
            $product = StoreProduct::createWithSku($data['attributes']);
            $this->syncImages($product, $data['images'], $data['primary']);
            $this->syncVariants($product, $data['sizes'], $data['colors'], $data['available'], $request->boolean('variant_matrix'));

            return $product;
        });

        ActivityLog::log('store.product.created', "Created store product \"{$product->name}\" ({$product->sku})", $product);

        return redirect()->route('admin.store.products.edit', $product)
            ->with('success', "Produk tersimpan dengan kode {$product->sku}.");
    }

    public function edit(StoreProduct $product)
    {
        $product->load(['images', 'variants']);

        return view('admin.store.products.edit', [
            'product' => $product,
            'categoryOptions' => StoreCategory::treeOptions(),
        ]);
    }

    public function update(Request $request, StoreProduct $product)
    {
        $data = $this->validated($request, $product);

        DB::transaction(function () use ($request, $product, $data) {
            $product->update($data['attributes']);
            $this->syncImages($product, $data['images'], $data['primary']);
            $this->syncVariants($product, $data['sizes'], $data['colors'], $data['available'], $request->boolean('variant_matrix'));
        });

        ActivityLog::log('store.product.updated', "Updated store product \"{$product->name}\" ({$product->sku})", $product);

        return redirect()->route('admin.store.products.edit', $product)->with('success', 'Produk diperbarui.');
    }

    public function destroy(StoreProduct $product)
    {
        // Soft delete: the SKU number and slug stay reserved.
        $product->delete();
        ActivityLog::log('store.product.deleted', "Deleted store product \"{$product->name}\" ({$product->sku})");

        return redirect()->route('admin.store.products.index')->with('success', 'Produk dihapus.');
    }

    /**
     * @return array{attributes: array, images: list<array{path: string, alt: ?string}>, primary: int, sizes: list<string>, colors: list<string>, available: list<string>}
     */
    private function validated(Request $request, ?StoreProduct $product = null): array
    {
        $option = ['nullable', 'string', 'not_regex:/\|/'];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                // Includes soft-deleted products: their slug stays reserved.
                Rule::unique('store_products', 'slug')->ignore($product?->id)],
            'store_category_id' => 'nullable|integer|exists:store_categories,id',
            'brand' => 'nullable|string|max:120',
            'description' => 'nullable|string|max:10000',
            'material' => 'nullable|string|max:255',
            'size_system' => 'nullable|string|max:20',
            'size_guide' => 'nullable|string|max:5000',
            'status' => ['required', Rule::in([StoreProduct::STATUS_DRAFT, StoreProduct::STATUS_PUBLISHED])],
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:100000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sizes' => 'nullable|array|max:' . self::MAX_SIZES,
            'sizes.*' => [...$option, 'max:20'],
            'colors' => 'nullable|array|max:' . self::MAX_COLORS,
            'colors.*' => [...$option, 'max:60'],
            'available' => 'nullable|array',
            'available.*' => 'string|max:90',
            'images' => 'nullable|array|max:' . (self::MAX_IMAGES + self::MAX_VIDEOS),
            'images.*.type' => 'nullable|in:image,video',
            'images.*.path' => 'required|string|max:500',
            'images.*.alt' => 'nullable|string|max:255',
            'primary_image' => 'nullable|integer|min:0',
        ], [
            'sizes.*.not_regex' => 'Ukuran tidak boleh memuat karakter |.',
            'colors.*.not_regex' => 'Warna tidak boleh memuat karakter |.',
            'images.max' => 'Maksimal ' . self::MAX_IMAGES . ' foto dan ' . self::MAX_VIDEOS . ' video per produk.',
        ]);

        $images = $this->validatedImages($validated['images'] ?? []);

        $slug = filled($validated['slug'] ?? null)
            ? $validated['slug']
            : $this->uniqueSlug($validated['name'], $product);

        $status = $validated['status'];
        $publishedAt = $product?->published_at;
        if ($status === StoreProduct::STATUS_PUBLISHED && ! $publishedAt) {
            $publishedAt = now();
        }

        return [
            'attributes' => [
                'name' => $validated['name'],
                'slug' => $slug,
                'store_category_id' => $validated['store_category_id'] ?? null,
                'brand' => $validated['brand'] ?? null,
                'description' => $validated['description'] ?? null,
                'material' => $validated['material'] ?? null,
                'size_system' => filled($validated['size_system'] ?? null) ? trim($validated['size_system']) : 'EU',
                'size_guide' => $validated['size_guide'] ?? null,
                'status' => $status,
                'is_featured' => $request->boolean('is_featured'),
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'published_at' => $publishedAt,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
            ],
            'images' => $images,
            'primary' => $this->primaryIndex($images, (int) ($validated['primary_image'] ?? 0)),
            'sizes' => $this->cleanList($validated['sizes'] ?? []),
            'colors' => $this->cleanList($validated['colors'] ?? []),
            'available' => array_values($validated['available'] ?? []),
        ];
    }

    /**
     * Only files that went through the admin uploader are accepted: a path
     * under media/ on the public disk, with a photo or video extension, whose
     * content really is a photo (decodes) or a video (MIME type). Anything
     * else is an edited form, not an upload.
     *
     * @return list<array{type: string, path: string, alt: ?string}>
     */
    private function validatedImages(array $images): array
    {
        $disk = Storage::disk('public');
        $videoExtensions = config('aldeftech.upload.video_mimes', ['mp4', 'webm']);
        $clean = [];
        $counts = [StoreProductImage::TYPE_IMAGE => 0, StoreProductImage::TYPE_VIDEO => 0];

        foreach (array_values($images) as $index => $image) {
            $type = ($image['type'] ?? '') === StoreProductImage::TYPE_VIDEO
                ? StoreProductImage::TYPE_VIDEO
                : StoreProductImage::TYPE_IMAGE;
            $path = ltrim(trim((string) ($image['path'] ?? '')), '/');
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            $valid = str_starts_with($path, 'media/')
                && ! str_contains($path, '..')
                && $disk->exists($path)
                && ($type === StoreProductImage::TYPE_VIDEO
                    ? in_array($extension, $videoExtensions, true)
                        // finfo on the bytes: Storage::mimeType() falls back to the
                        // extension, which would let a renamed text file through.
                        && str_starts_with((string) (new \finfo(FILEINFO_MIME_TYPE))->file($disk->path($path)), 'video/')
                    : in_array($extension, self::IMAGE_EXTENSIONS, true)
                        && @getimagesize($disk->path($path)) !== false);

            if (! $valid) {
                throw ValidationException::withMessages([
                    "images.{$index}.path" => $type === StoreProductImage::TYPE_VIDEO
                        ? 'Video ke-' . ($index + 1) . ' tidak valid. Unggah ulang dalam format MP4 atau WebM.'
                        : 'Foto ke-' . ($index + 1) . ' tidak valid. Unggah ulang dalam format JPG, PNG, atau WebP.',
                ]);
            }

            $counts[$type]++;
            $clean[] = ['type' => $type, 'path' => $path, 'alt' => filled($image['alt'] ?? null) ? trim($image['alt']) : null];
        }

        if ($counts[StoreProductImage::TYPE_IMAGE] > self::MAX_IMAGES || $counts[StoreProductImage::TYPE_VIDEO] > self::MAX_VIDEOS) {
            throw ValidationException::withMessages([
                'images' => 'Maksimal ' . self::MAX_IMAGES . ' foto dan ' . self::MAX_VIDEOS . ' video per produk.',
            ]);
        }

        return $clean;
    }

    /**
     * The main photo is what catalogue cards and link previews show, so it
     * must be a photo: a video chosen as main falls back to the first photo.
     */
    private function primaryIndex(array $items, int $requested): int
    {
        if (isset($items[$requested]) && $items[$requested]['type'] === StoreProductImage::TYPE_IMAGE) {
            return $requested;
        }

        foreach ($items as $index => $item) {
            if ($item['type'] === StoreProductImage::TYPE_IMAGE) {
                return $index;
            }
        }

        return -1;
    }

    /** Readable and unique; a clash gets -2, -3… never a random suffix. */
    private function uniqueSlug(string $name, ?StoreProduct $product): string
    {
        $base = Str::slug($name) ?: 'sepatu';
        $slug = $base;
        $n = 2;

        while (StoreProduct::withTrashed()->where('slug', $slug)
            ->when($product, fn ($q) => $q->whereKeyNot($product->id))->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    /** @return list<string> trimmed, non-empty, first occurrence kept, admin's order */
    private function cleanList(array $values): array
    {
        $out = [];
        foreach ($values as $value) {
            $value = trim((string) $value);
            if ($value !== '' && ! in_array($value, $out, true)) {
                $out[] = $value;
            }
        }

        return $out;
    }

    private function syncImages(StoreProduct $product, array $images, int $primary): void
    {
        $product->images()->delete();

        foreach ($images as $index => $image) {
            StoreProductImage::create([
                'store_product_id' => $product->id,
                'type' => $image['type'],
                'path' => $image['path'],
                'alt' => $image['alt'],
                'is_primary' => $index === $primary,
                'sort_order' => $index,
            ]);
        }
    }

    /**
     * One variant per size × colour. With the availability table submitted,
     * a ticked box means in stock. Without it (JavaScript off), existing
     * combinations keep their state and new ones start in stock.
     */
    private function syncVariants(StoreProduct $product, array $sizes, array $colors, array $available, bool $matrixSubmitted): void
    {
        $sizes = $sizes ?: [''];
        $colors = $colors ?: [''];

        if ($sizes === [''] && $colors === ['']) {
            $product->variants()->delete();

            return;
        }

        $existing = $product->variants()->get()->keyBy(fn ($v) => $v->size . '|' . $v->color);
        $keep = [];
        $order = 0;

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                $key = $size . '|' . $color;
                $isAvailable = $matrixSubmitted
                    ? in_array($key, $available, true)
                    : ($existing->get($key)?->is_available ?? true);

                $variant = StoreProductVariant::updateOrCreate(
                    ['store_product_id' => $product->id, 'size' => $size, 'color' => $color],
                    ['is_available' => $isAvailable, 'sort_order' => $order++]
                );
                $keep[] = $variant->id;
            }
        }

        $product->variants()->whereNotIn('id', $keep)->delete();
    }
}
