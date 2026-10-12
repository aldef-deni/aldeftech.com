<?php

namespace App\Http\Controllers;

use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\StoreProductVariant;
use App\Services\StoreWhatsApp;
use Illuminate\Http\Request;

/**
 * Aldef Store: a shoe catalogue without prices. Only published products are
 * ever visible; a draft answers 404 even on its direct URL.
 */
class StoreController extends Controller
{
    private const PER_PAGE = 12;

    public function index(Request $request)
    {
        // ?page=1 is /store under a second address.
        if ((string) $request->query('page') === '1') {
            return redirect()->to(route('store', $request->except('page')), 301);
        }

        $search = trim(mb_substr((string) $request->query('q', ''), 0, 80));
        $categorySlug = (string) $request->query('kategori', '');
        $size = trim(mb_substr((string) $request->query('ukuran', ''), 0, 20));

        $products = StoreProduct::published()
            ->with(['category', 'images', 'variants'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';
                $query->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('brand', 'like', $like));
            })
            ->when($categorySlug !== '', fn ($query) => $query->whereHas(
                'category', fn ($q) => $q->where('slug', $categorySlug)
            ))
            ->when($size !== '', fn ($query) => $query->whereHas(
                'variants', fn ($q) => $q->where('size', $size)->where('is_available', true)
            ))
            ->ordered()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // A page past the end would render an empty grid with status 200.
        abort_if($products->currentPage() > 1 && $products->isEmpty(), 404);

        $categories = StoreCategory::ordered()
            ->whereHas('products', fn ($q) => $q->published())
            ->get();

        $sizes = StoreProductVariant::query()
            ->where('is_available', true)
            ->where('size', '!=', '')
            ->whereHas('product', fn ($q) => $q->published())
            ->distinct()
            ->pluck('size')
            ->sort(fn ($a, $b) => is_numeric($a) && is_numeric($b) ? (float) $a <=> (float) $b : strnatcmp($a, $b))
            ->values();

        return view('pages.store', [
            'products' => $products,
            'categories' => $categories,
            'sizes' => $sizes,
            'filters' => ['q' => $search, 'kategori' => $categorySlug, 'ukuran' => $size],
            'hasAnyProduct' => $products->total() > 0 || StoreProduct::published()->exists(),
        ]);
    }

    public function show(string $slug)
    {
        $product = StoreProduct::published()
            ->where('slug', $slug)
            ->with(['category', 'images', 'variants'])
            ->firstOrFail();

        $related = StoreProduct::published()
            ->whereKeyNot($product->id)
            ->when($product->store_category_id, fn ($q) => $q->where('store_category_id', $product->store_category_id))
            ->with(['category', 'images', 'variants'])
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
