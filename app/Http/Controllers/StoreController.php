<?php

namespace App\Http\Controllers;

use App\Models\StoreBrand;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\StoreProductVariant;
use App\Services\StoreWhatsApp;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Aldef Store: a shoe catalogue without prices. Only published products are
 * ever visible; a draft answers 404 even on its direct URL.
 *
 * Browsing goes brand first:
 *   /store                               brands
 *   /store/brand/{brand}                 its categories, or its products when it has none
 *   /store/brand/{brand}/{category}      its subcategories, or the products
 *   /store/brand/{brand}/{category}/{sub} products
 * Search and filters (?q, ?kategori, ?ukuran) list products across brands.
 */
class StoreController extends Controller
{
    private const PER_PAGE = 12;

    /** Products shown under the tiles, e.g. a brand's uncategorised shoes. */
    private const LOOSE_LIMIT = 48;

    public function index(Request $request)
    {
        // ?page=1 is /store under a second address.
        if ((string) $request->query('page') === '1') {
            return redirect()->to(route('store', $request->except('page')), 301);
        }

        $search = trim(mb_substr((string) $request->query('q', ''), 0, 80));
        $categorySlug = (string) $request->query('kategori', '');
        $size = trim(mb_substr((string) $request->query('ukuran', ''), 0, 20));
        $brandSlug = (string) $request->query('brand', '');

        // The old brand filter address now has a page of its own.
        if ($brandSlug !== '' && $search === '' && $categorySlug === '' && $size === '') {
            $brand = StoreBrand::where('slug', $brandSlug)->first();

            return $brand
                ? redirect()->route('store.brand', $brand->slug, 301)
                : redirect()->route('store', [], 301);
        }

        if ($search === '' && $categorySlug === '' && $size === '' && $brandSlug === '') {
            // The brand list is a single page; old catalogue pages land on it.
            if ($request->query->has('page')) {
                return redirect()->route('store', [], 301);
            }

            return $this->brandLanding();
        }

        // A top-level category also matches the products in its subcategories.
        $category = $categorySlug !== '' ? StoreCategory::where('slug', $categorySlug)->first() : null;

        $products = StoreProduct::published()
            ->with(['brand', 'category', 'images', 'variants'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(fn ($q) => $q->where('name', 'like', $like)
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', $like)));
            })
            ->when($categorySlug !== '', fn ($query) => $query->whereIn(
                'store_category_id', $category ? $category->familyIds() : [0]
            ))
            ->when($brandSlug !== '', fn ($query) => $query->whereHas(
                'brand', fn ($q) => $q->where('slug', $brandSlug)
            ))
            ->when($size !== '', fn ($query) => $query->whereHas(
                'variants', fn ($q) => $q->where('size', $size)->where('is_available', true)
            ))
            ->ordered()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // A page past the end would render an empty grid with status 200.
        abort_if($products->currentPage() > 1 && $products->isEmpty(), 404);

        return view('pages.store', [
            'mode' => 'results',
            'products' => $products,
            'categories' => $this->categoryFilterOptions(),
            'sizes' => $this->sizeOptions(),
            'brands' => StoreBrand::ordered()->whereHas('products', fn ($q) => $q->published())->get(['name', 'slug']),
            'filters' => ['q' => $search, 'brand' => $brandSlug, 'kategori' => $categorySlug, 'ukuran' => $size],
            'hasAnyProduct' => true,
        ]);
    }

    /** /store: one tile per brand that has published products. */
    private function brandLanding()
    {
        $brands = StoreBrand::ordered()
            ->whereHas('products', fn ($q) => $q->published())
            ->withCount(['products as published_count' => fn ($q) => $q->published()])
            ->get();

        $tiles = $brands->map(fn (StoreBrand $brand) => [
            'name' => $brand->name,
            'url' => route('store.brand', $brand->slug),
            'count' => $brand->published_count,
            'logo' => $brand->logo ? media_url($brand->logo) : null,
            'image' => $this->coverImage(StoreProduct::published()->where('store_brand_id', $brand->id)),
            'caption' => null,
        ]);

        // Shoes without a brand would otherwise be unreachable from here.
        $loose = StoreProduct::published()->whereNull('store_brand_id')
            ->with(['brand', 'category', 'images', 'variants'])
            ->ordered()->limit(self::LOOSE_LIMIT)->get();

        return view('pages.store', [
            'mode' => 'brands',
            'tiles' => $tiles,
            'loose' => $loose,
            'sizes' => $this->sizeOptions(),
            'categories' => $this->categoryFilterOptions(),
            'brands' => $brands,
            'filters' => ['q' => '', 'brand' => '', 'kategori' => '', 'ukuran' => ''],
            'hasAnyProduct' => $tiles->isNotEmpty() || $loose->isNotEmpty(),
        ]);
    }

    /**
     * A brand, then a category, then a subcategory. Each level shows the next
     * level's tiles when there are any, and the products otherwise.
     */
    public function brand(Request $request, StoreBrand $brand, ?string $category = null, ?string $subcategory = null)
    {
        if ((string) $request->query('page') === '1') {
            return redirect()->to($request->url(), 301);
        }

        $base = fn () => StoreProduct::published()->where('store_brand_id', $brand->id);
        abort_unless($base()->exists(), 404);

        $parent = $category ? StoreCategory::topLevel()->where('slug', $category)->firstOrFail() : null;
        $child = $subcategory ? $parent->children()->where('slug', $subcategory)->firstOrFail() : null;

        $used = $base()->whereNotNull('store_category_id')->distinct()->pluck('store_category_id');
        $usedCategories = StoreCategory::whereIn('id', $used)->get();

        $tiles = collect();
        $loose = collect();
        $products = null;

        if (! $parent) {
            // Brand level: top-level categories this brand's shoes sit in.
            $topIds = $usedCategories->map(fn ($c) => $c->parent_id ?? $c->id)->unique();
            $tiles = StoreCategory::whereIn('id', $topIds)->ordered()->get()
                ->map(fn ($c) => $this->categoryTile($base, $brand, $c));

            if ($tiles->isEmpty()) {
                $products = $base();
            } else {
                $loose = $base()->whereNull('store_category_id');
            }
        } elseif (! $child) {
            abort_unless($base()->whereIn('store_category_id', $parent->familyIds())->exists(), 404);

            $tiles = $parent->children()->whereIn('id', $used)->ordered()->get()
                ->map(fn ($c) => $this->categoryTile($base, $brand, $c, $parent));

            if ($tiles->isEmpty()) {
                $products = $base()->whereIn('store_category_id', $parent->familyIds());
            } else {
                // Shoes filed under the category itself, not a subcategory.
                $loose = $base()->where('store_category_id', $parent->id);
            }
        } else {
            $products = $base()->where('store_category_id', $child->id);
        }

        if ($products) {
            $products = $products->with(['brand', 'category', 'images', 'variants'])
                ->ordered()->paginate(self::PER_PAGE)->withQueryString();
            abort_if($products->currentPage() > 1 && $products->isEmpty(), 404);
            abort_if($products->total() === 0, 404);
        }
        if ($loose instanceof \Illuminate\Database\Eloquent\Builder) {
            $loose = $loose->with(['brand', 'category', 'images', 'variants'])->ordered()->limit(self::LOOSE_LIMIT)->get();
        }

        return view('pages.store-brand', [
            'brand' => $brand,
            'category' => $parent,
            'subcategory' => $child,
            'tiles' => $tiles,
            'loose' => $loose,
            'products' => $products,
        ]);
    }

    private function categoryTile(\Closure $base, StoreBrand $brand, StoreCategory $category, ?StoreCategory $parent = null): array
    {
        $ids = $parent ? [$category->id] : $category->familyIds();
        $count = $base()->whereIn('store_category_id', $ids)->count();

        return [
            'name' => $category->name,
            'url' => $parent
                ? route('store.brand', [$brand->slug, $parent->slug, $category->slug])
                : route('store.brand', [$brand->slug, $category->slug]),
            'count' => $count,
            'logo' => null,
            'image' => $this->coverImage($base()->whereIn('store_category_id', $ids)),
            'caption' => $category->description,
        ];
    }

    /** The main photo of the first product in a query, for a tile. */
    private function coverImage($query): ?string
    {
        return $query->with('images')->ordered()->first()?->primaryImage()?->url;
    }

    /**
     * Categories for the filter, subcategories under their parent. Only ones
     * with published products are offered; a parent stays when only its
     * subcategories have products, since filtering on it still finds them.
     *
     * @return list<array{slug: string, label: string, child: bool}>
     */
    private function categoryFilterOptions(): array
    {
        $used = StoreProduct::published()->whereNotNull('store_category_id')
            ->distinct()->pluck('store_category_id')->all();

        $options = [];
        foreach (StoreCategory::tree() as $parent) {
            $children = $parent->children->filter(fn ($c) => in_array($c->id, $used));

            if (! in_array($parent->id, $used) && $children->isEmpty()) {
                continue;
            }

            $options[] = ['slug' => $parent->slug, 'label' => $parent->name, 'child' => false];
            foreach ($children as $child) {
                $options[] = ['slug' => $child->slug, 'label' => $child->name, 'child' => true];
            }
        }

        return $options;
    }

    private function sizeOptions(): Collection
    {
        return StoreProductVariant::query()
            ->where('is_available', true)
            ->where('size', '!=', '')
            ->whereHas('product', fn ($q) => $q->published())
            ->distinct()
            ->pluck('size')
            ->sort(fn ($a, $b) => is_numeric($a) && is_numeric($b) ? (float) $a <=> (float) $b : strnatcmp($a, $b))
            ->values();
    }

    public function show(string $slug)
    {
        $product = StoreProduct::published()
            ->where('slug', $slug)
            ->with(['brand', 'category.parent', 'images', 'variants'])
            ->firstOrFail();

        $related = StoreProduct::published()
            ->whereKeyNot($product->id)
            ->when($product->store_category_id, fn ($q) => $q->where('store_category_id', $product->store_category_id))
            ->with(['brand', 'category', 'images', 'variants'])
            ->ordered()
            ->limit(4)
            ->get();

        return view('pages.store-show', [
            'product' => $product,
            'related' => $related,
            'waNumber' => StoreWhatsApp::number(),
        ]);
    }
}
