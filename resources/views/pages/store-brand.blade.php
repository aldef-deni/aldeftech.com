@extends('layouts.app')

@php
    // The deepest level open on this page: brand, category or subcategory.
    $current = $subcategory ?? $category;
    $heading = $current ? $current->name : $brand->name;
    $eyebrow = $current ? $brand->name : 'Aldef Store';

    $levels = array_values(array_filter([
        ['name' => $brand->name, 'url' => route('store.brand', $brand->slug)],
        $category ? ['name' => $category->name, 'url' => route('store.brand', [$brand->slug, $category->slug])] : null,
        $subcategory ? ['name' => $subcategory->name, 'url' => route('store.brand', [$brand->slug, $category->slug, $subcategory->slug])] : null,
    ]));
    $pageUrl = end($levels)['url'];

    $titleParts = collect($levels)->pluck('name')->reverse()->implode(' — ');
    $pageTitle = $titleParts . ' — Aldef Store | Aldef Tech';
    $description = trim((string) ($current?->description ?: $brand->description));
    $metaDescription = $description !== ''
        ? \Illuminate\Support\Str::limit($description, 155)
        : 'Koleksi sepatu ' . collect($levels)->pluck('name')->reverse()->implode(' ') . ' di Aldef Store. Pilih model dan ukuran, lalu hubungi kami melalui WhatsApp untuk harga dan ketersediaan.';
    $analyticsPageType = 'store';

    // The catalogue exists in Indonesian only.
    $hreflangLocales = ['id'];
    $page = $products?->currentPage() ?? 1;
    $canonical = $pageUrl . ($page > 1 ? '?page=' . $page : '');

    $crumbs = [['label' => 'Aldef Store', 'url' => route('store')]];
    foreach ($levels as $i => $level) {
        $crumbs[] = $i === count($levels) - 1 ? ['label' => $level['name']] : ['label' => $level['name'], 'url' => $level['url']];
    }

    $schemaItems = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => route('home') . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Aldef Store', 'item' => route('store')],
    ];
    foreach ($levels as $i => $level) {
        $schemaItems[] = ['@type' => 'ListItem', 'position' => $i + 3, 'name' => $level['name'], 'item' => $level['url']];
    }

    $grid = 'grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-3 gap-y-8 sm:gap-x-5 lg:gap-x-6 lg:gap-y-10';
    $back = count($levels) > 1 ? $levels[count($levels) - 2] : ['name' => 'semua brand', 'url' => route('store')];
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $schemaItems,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<x-page-hero
    :eyebrow="$eyebrow"
    :title="$heading"
    :lead="$description !== '' ? $description : null"
    compact
    :breadcrumbs="$crumbs" />

<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">

        @if($tiles->isNotEmpty())
            <h2 class="text-2xl lg:text-3xl">{{ $category ? 'Pilih Subkategori' : 'Pilih Kategori' }}</h2>
            <div class="mt-8 {{ $grid }}">
                @foreach($tiles as $tile)
                    @include('pages.partials.store-tile')
                @endforeach
            </div>

            @if($loose->isNotEmpty())
                <h2 class="mt-16 text-2xl lg:text-3xl">{{ $category ? 'Lainnya di ' . $category->name : 'Produk Lainnya' }}</h2>
                <div class="mt-8 {{ $grid }}">
                    @foreach($loose as $product)
                        @include('pages.partials.store-product-card')
                    @endforeach
                </div>
            @endif
        @else
            <p class="pb-6 text-sm text-graphite-600">{{ $products->total() }} model</p>

            <div class="{{ $grid }}">
                @foreach($products as $product)
                    @include('pages.partials.store-product-card')
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="mt-14">
                    {{ $products->onEachSide(1)->links('vendor.pagination.lux') }}
                </div>
            @endif
        @endif

        <div class="mt-14">
            <a href="{{ $back['url'] }}" class="link-arrow text-sm">
                <svg class="w-3.5 h-3.5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                <span>Kembali ke {{ $back['name'] }}</span>
            </a>
        </div>
    </div>
</section>

@endsection
