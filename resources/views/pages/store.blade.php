@extends('layouts.app')

@php
    $pageTitle = 'Aldef Store — Koleksi Sepatu Pilihan | Aldef Tech';
    $metaDescription = 'Temukan sepatu pilihan untuk aktivitas Anda. Pilih brand, model, dan ukuran, lalu hubungi kami melalui WhatsApp untuk informasi harga dan ketersediaan.';
    $analyticsPageType = 'store';

    // The catalogue exists in Indonesian only.
    $hreflangLocales = ['id'];

    // /store lists the brands. Search and filter results are views of the
    // same catalogue: keep them out of the index and point them at /store.
    $isFiltered = $mode === 'results';
    $noindex = $isFiltered;
    $canonical = route('store');
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => route('home') . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Aldef Store', 'item' => route('store')],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<x-page-hero
    eyebrow="Aldef Store"
    title="Koleksi Sepatu Pilihan"
    lead="Pilih brand favorit Anda, lalu temukan model dan ukurannya. Hubungi kami melalui WhatsApp untuk informasi harga dan ketersediaan."
    :breadcrumbs="[['label' => 'Aldef Store']]" />

<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">

        @if($hasAnyProduct)
        {{-- Search & filters: a plain GET form, so every result has a shareable URL. --}}
        <form method="GET" action="{{ route('store') }}" role="search"
              class="grid grid-cols-2 {{ $isFiltered ? 'lg:grid-cols-[minmax(0,1fr)_11rem_13rem_9rem_auto]' : 'lg:grid-cols-[minmax(0,1fr)_9rem_auto]' }} gap-3 pb-10 items-end">
            <div class="col-span-2 lg:col-span-1">
                <label for="store-q" class="form-label">Cari sepatu</label>
                <input id="store-q" type="search" name="q" value="{{ $filters['q'] }}" maxlength="80"
                       class="form-input h-12" placeholder="Nama model atau merek" autocomplete="off">
            </div>

            @if($isFiltered)
            <div>
                <label for="store-brand" class="form-label">Brand</label>
                <select id="store-brand" name="brand" class="form-input h-12">
                    <option value="">Semua brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->slug }}" @selected($filters['brand'] === $brand->slug)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="store-kategori" class="form-label">Kategori</label>
                <select id="store-kategori" name="kategori" class="form-input h-12">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $option)
                        <option value="{{ $option['slug'] }}" @selected($filters['kategori'] === $option['slug'])>{{ $option['child'] ? '— ' : '' }}{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label for="store-ukuran" class="form-label">Ukuran</label>
                <select id="store-ukuran" name="ukuran" class="form-input h-12">
                    <option value="">Semua ukuran</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size }}" @selected($filters['ukuran'] === (string) $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

            <div class="{{ $isFiltered ? 'col-span-2 lg:col-span-1' : '' }} flex gap-2">
                <button type="submit" class="btn btn-primary flex-1 lg:flex-none justify-center">{{ $isFiltered ? 'Terapkan' : 'Cari' }}</button>
                @if($isFiltered)
                    <a href="{{ route('store') }}" class="btn btn-outline justify-center">Reset</a>
                @endif
            </div>
        </form>
        @endif

        @if($mode === 'brands')
            @if($tiles->isNotEmpty())
                <h2 class="text-2xl lg:text-3xl">Pilih Brand</h2>
                <div class="mt-8 grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-3 gap-y-8 sm:gap-x-5 lg:gap-x-6 lg:gap-y-10">
                    @foreach($tiles as $tile)
                        @include('pages.partials.store-tile')
                    @endforeach
                </div>
            @endif

            @if($loose->isNotEmpty())
                <h2 class="text-2xl lg:text-3xl {{ $tiles->isNotEmpty() ? 'mt-16' : '' }}">{{ $tiles->isNotEmpty() ? 'Produk Lainnya' : 'Semua Sepatu' }}</h2>
                <div class="mt-8 grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-3 gap-y-8 sm:gap-x-5 lg:gap-x-6 lg:gap-y-10">
                    @foreach($loose as $product)
                        @include('pages.partials.store-product-card')
                    @endforeach
                </div>
            @endif

            @unless($hasAnyProduct)
                <div class="max-w-lg mx-auto text-center py-16">
                    <h2 class="text-xl">Koleksi sedang kami siapkan</h2>
                    <p class="mt-3 text-graphite-600">Belum ada sepatu yang ditampilkan saat ini. Silakan kembali lagi nanti, atau hubungi kami untuk menanyakan model tertentu.</p>
                    <a href="{{ lroute('contact') }}" class="btn btn-outline mt-7">Hubungi kami</a>
                </div>
            @endunless

        @elseif($products->isNotEmpty())
            <p class="pb-6 text-sm text-graphite-600" aria-live="polite">
                {{ $products->total() }} sepatu ditemukan
            </p>

            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-3 gap-y-8 sm:gap-x-5 lg:gap-x-6 lg:gap-y-10">
                @foreach($products as $product)
                    @include('pages.partials.store-product-card')
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="mt-14">
                    {{ $products->onEachSide(1)->links('vendor.pagination.lux') }}
                </div>
            @endif

        @else
            <div class="max-w-lg mx-auto text-center py-16">
                <h2 class="text-xl">Tidak ada sepatu yang cocok</h2>
                <p class="mt-3 text-graphite-600">Coba kata kunci lain, atau tampilkan semua ukuran dan kategori.</p>
                <a href="{{ route('store') }}" class="btn btn-outline mt-7">Lihat semua brand</a>
            </div>
        @endif
    </div>
</section>

@endsection
