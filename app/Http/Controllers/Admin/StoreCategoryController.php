<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Aldef Store categories, with one level of subcategories.
 */
class StoreCategoryController extends Controller
{
    public function index()
    {
        $tree = StoreCategory::topLevel()->ordered()
            ->withCount('products')
            ->with(['children' => fn ($q) => $q->withCount('products')])
            ->get();

        return view('admin.store.categories.index', ['tree' => $tree]);
    }

    public function create(Request $request)
    {
        return view('admin.store.categories.form', [
            'category' => null,
            'parents' => $this->parentOptions(),
            'presetParent' => $request->integer('parent') ?: null,
        ]);
    }

    public function store(Request $request)
    {
        $category = StoreCategory::create($this->validated($request));
        ActivityLog::log('store.category.created', "Created store category \"{$category->name}\"", $category);

        return redirect()->route('admin.store.categories.index')
            ->with('success', $category->parent_id ? 'Subkategori ditambahkan.' : 'Kategori ditambahkan.');
    }

    public function edit(StoreCategory $category)
    {
        return view('admin.store.categories.form', [
            'category' => $category->loadCount('children'),
            'parents' => $this->parentOptions($category),
            'presetParent' => null,
        ]);
    }

    public function update(Request $request, StoreCategory $category)
    {
        $category->update($this->validated($request, $category));
        ActivityLog::log('store.category.updated', "Updated store category \"{$category->name}\"", $category);

        return redirect()->route('admin.store.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(StoreCategory $category)
    {
        // Products keep existing without a category, and subcategories move up
        // to the top level (foreign keys are set to null).
        $name = $category->name;
        $category->delete();
        ActivityLog::log('store.category.deleted', "Deleted store category \"{$name}\"");

        return redirect()->route('admin.store.categories.index')->with('success', 'Kategori dihapus.');
    }

    /** Top-level categories that can take subcategories; never the one being edited. */
    private function parentOptions(?StoreCategory $except = null): array
    {
        return StoreCategory::topLevel()->ordered()
            ->when($except, fn ($q) => $q->whereKeyNot($except->id))
            ->pluck('name', 'id')
            ->all();
    }

    private function validated(Request $request, ?StoreCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('store_categories', 'slug')->ignore($category?->id)],
            'parent_id' => ['nullable', 'integer', Rule::exists('store_categories', 'id')],
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ]);

        $parentId = $validated['parent_id'] ?? null;

        if ($parentId) {
            $parent = StoreCategory::find($parentId);

            if ($category && (int) $parentId === $category->id) {
                $this->fail('Kategori tidak bisa menjadi induk dirinya sendiri.');
            }
            // Only one level of nesting.
            if ($parent->parent_id !== null) {
                $this->fail('Induk harus kategori utama, bukan subkategori.');
            }
            if ($category && $category->children()->exists()) {
                $this->fail('Kategori ini punya subkategori, jadi tidak bisa dijadikan subkategori.');
            }
        }

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
            'parent_id' => $parentId,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['parent_id' => $message]);
    }
}
