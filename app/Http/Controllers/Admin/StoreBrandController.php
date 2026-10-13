<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreBrand;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Aldef Store brands. A brand is independent of categories: the list shows,
 * for each brand, how many products it has and in which categories.
 */
class StoreBrandController extends Controller
{
    public function index()
    {
        $brands = StoreBrand::ordered()
            ->withCount('products')
            ->withCount(['products as published_count' => fn ($q) => $q->published()])
            ->get();

        // Categories each brand's products sit in, for the "used in" column.
        $pairs = StoreProduct::query()
            ->whereNotNull('store_brand_id')->whereNotNull('store_category_id')
            ->distinct()->get(['store_brand_id', 'store_category_id']);
        $categories = StoreCategory::with('parent')->whereIn('id', $pairs->pluck('store_category_id'))->get()->keyBy('id');
        $usedIn = $pairs->groupBy('store_brand_id')->map(fn ($rows) => $rows
            ->map(fn ($row) => $categories->get($row->store_category_id)?->label())
            ->filter()->sort()->values());

        return view('admin.store.brands.index', [
            'brands' => $brands,
            'usedIn' => $usedIn,
            'unbranded' => StoreProduct::whereNull('store_brand_id')->count(),
        ]);
    }

    public function create()
    {
        return view('admin.store.brands.form', ['brand' => null]);
    }

    public function store(Request $request)
    {
        $brand = StoreBrand::create($this->validated($request));
        ActivityLog::log('store.brand.created', "Created store brand \"{$brand->name}\"", $brand);

        return redirect()->route('admin.store.brands.index')->with('success', 'Brand ditambahkan.');
    }

    public function edit(StoreBrand $brand)
    {
        return view('admin.store.brands.form', ['brand' => $brand]);
    }

    public function update(Request $request, StoreBrand $brand)
    {
        $brand->update($this->validated($request, $brand));
        ActivityLog::log('store.brand.updated', "Updated store brand \"{$brand->name}\"", $brand);

        return redirect()->route('admin.store.brands.index')->with('success', 'Brand diperbarui.');
    }

    public function destroy(StoreBrand $brand)
    {
        // Products stay; they simply lose their brand.
        $name = $brand->name;
        $brand->delete();
        ActivityLog::log('store.brand.deleted', "Deleted store brand \"{$name}\"");

        return redirect()->route('admin.store.brands.index')->with('success', 'Brand dihapus.');
    }

    private function validated(Request $request, ?StoreBrand $brand = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120',
                // Case-insensitive: "Nike" and "nike" are one brand.
                function ($attribute, $value, $fail) use ($brand) {
                    $exists = StoreBrand::whereRaw('LOWER(name) = ?', [mb_strtolower(trim($value))])
                        ->when($brand, fn ($q) => $q->whereKeyNot($brand->id))->exists();
                    if ($exists) {
                        $fail('Brand dengan nama ini sudah ada.');
                    }
                }],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('store_brands', 'slug')->ignore($brand?->id)],
            'logo' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ]);

        $logo = trim((string) ($validated['logo'] ?? ''));

        return [
            'name' => trim($validated['name']),
            'slug' => filled($validated['slug'] ?? null) ? $validated['slug'] : StoreBrand::uniqueSlug($validated['name'], $brand?->id),
            // Only a file from the admin uploader, never a hand-typed path.
            'logo' => str_starts_with($logo, 'media/') && ! str_contains($logo, '..') ? $logo : null,
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }
}
