<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCategoryController extends Controller
{
    public function index()
    {
        return view('admin.store.categories.index', [
            'categories' => StoreCategory::withCount('products')->ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.store.categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $category = StoreCategory::create($this->validated($request));
        ActivityLog::log('store.category.created', "Created store category \"{$category->name}\"", $category);

        return redirect()->route('admin.store.categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(StoreCategory $category)
    {
        return view('admin.store.categories.form', ['category' => $category]);
    }

    public function update(Request $request, StoreCategory $category)
    {
        $category->update($this->validated($request, $category));
        ActivityLog::log('store.category.updated', "Updated store category \"{$category->name}\"", $category);

        return redirect()->route('admin.store.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(StoreCategory $category)
    {
        // Products keep existing; they simply lose their category.
        $name = $category->name;
        $category->delete();
        ActivityLog::log('store.category.deleted', "Deleted store category \"{$name}\"");

        return redirect()->route('admin.store.categories.index')->with('success', 'Kategori dihapus.');
    }

    private function validated(Request $request, ?StoreCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('store_categories', 'slug')->ignore($category?->id)],
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ]);

        $slug = $validated['slug'] ?? null;
        if (! filled($slug)) {
            $base = Str::slug($validated['name']) ?: 'kategori';
            $slug = $base;
            $n = 2;
            while (StoreCategory::where('slug', $slug)->when($category, fn ($q) => $q->whereKeyNot($category->id))->exists()) {
                $slug = $base . '-' . $n++;
            }
        }

        return [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }
}
