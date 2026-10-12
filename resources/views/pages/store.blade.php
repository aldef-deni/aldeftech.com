@extends('layouts.app')

@php
    $pageTitle = 'Aldef Store — Koleksi Sepatu Pilihan | Aldef Tech';
    $metaDescription = 'Temukan sepatu pilihan untuk aktivitas Anda. Pilih model dan ukuran, lalu hubungi kami melalui WhatsApp untuk informasi harga dan ketersediaan.';
    $analyticsPageType = 'store';

    // The catalogue exists in Indonesian only.
    $hreflangLocales = ['id'];

    // Search and filter results are views of the same catalogue: keep them out
    // of the index and point them at /store. Plain pages 2+ keep their number
    // so crawlers can still walk the catalogue.
    $isFiltered = collect($filters)->filter(fn ($v) => $v !== '')->isNotEmpty();
    $noindex = $isFiltered;
    $canonical = route('store') . (! $isFiltered && $products->currentPage() > 1 ? '?page=' . $products->currentPage() : '');
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
    lead="Temukan sepatu pilihan untuk aktivitas Anda. Pilih model dan ukuran, lalu hubungi kami melalui WhatsApp untuk informasi harga dan ketersediaan."
    :breadcrumbs="[['label' => 'Aldef Store']]" />

<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">

        @if($hasAnyProduct)
        {{-- Search & filters: a plain GET form, so every result has a shareable URL. --}}
        <form method="GET" action="{{ route('store') }}" role="search"
              class="grid grid-cols-2 lg:grid-cols-[minmax(0,1fr)_14rem_11rem_auto] gap-3 pb-10 items-end">
            <div class="col-span-2 lg:col-span-1">
                <label for="store-q" class="form-label">Cari sepatu</label>
                <input id="store-q" type="search" name="q" value="{{ $filters['q'] }}" maxlength="80"
                       class="form-input h-12" placeholder="Nama model atau merek" autocomplete="off">
            </div>

            <div>
                <label for="store-kategori" class="form-label">Kategori</label>
                <select id="store-kategori" name="kategori" class="form-input h-12">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected($filters['kategori'] === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="store-ukuran" class="form-label">Ukuran</label>
                <select id="store-ukuran" name="ukuran" class="form-input h-12">
                    <option value="">Semua ukuran</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size }}" @selected($filters['ukuran'] === (string) $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 lg:col-span-1 flex gap-2">
                <button type="submit" class="btn btn-primary flex-1 lg:flex-none justify-center">Terapkan</button>
                @if($isFiltered)
                    <a href="{{ route('store') }}" class="btn btn-outline justify-center">Reset</a>
                @endif
            </div>
        </form>
        @endif

        @if($products->isNotEmpty())
            @if($isFiltered)
                <p class="pb-6 text-sm text-graphite-600" aria-live="polite">
                    {{ $products->total() }} sepatu ditemukan
                </p>
            @endif

            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-3 gap-y-8 sm:gap-x-5 lg:gap-x-6 lg:gap-y-10">
                @foreach($products as $product)
                    @php
                        $photo = $product->primaryImage();
                        $label = collect([$product->brand, $product->category?->name])->filter()->implode(' · ');
                        $sizeSummary = $product->sizeSummary();
                    @endphp
                    <article class="store-card group flex flex-col">
                        <a href="{{ route('store.show', $product->slug) }}" class="block"
                           aria-label="Lihat detail {{ $product->name }}">
                            <div class="store-photo">
                                @if($photo && $photo->url)
                                    <img src="{{ $photo->url }}"
                                         alt="{{ $photo->alt ?: $product->name . ($product->brand ? ' — ' . $product->brand : '') }}"
                                         loading="lazy" decoding="async" width="600" height="600">
                                @else
                                    <span class="absolute inset-0 flex items-center justify-center text-gold-400/60" aria-hidden="true">
                                        <x-lux-icon name="layers" class="w-10 h-10" />
                                    </span>
                                @endif
                                @if($product->isAvailable() === false)
                                    <span class="absolute top-2.5 left-2.5 rounded-full bg-graphite-900/85 px-2.5 py-1 text-[0.625rem] font-semibold uppercase tracking-[0.1em] text-ivory-100">Stok habis</span>
                                @endif
                            </div>
                        </a>

                        <div class="pt-4 flex-1 flex flex-col">
                            @if($label)
                                <p class="text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700 truncate">{{ $label }}</p>
                            @endif
                            <h2 class="mt-1.5 text-[0.9375rem] sm:text-base leading-snug font-semibold text-graphite-900">
                                <a href="{{ route('store.show', $product->slug) }}" class="hover:text-gold-700 transition-colors">{{ $product->name }}</a>
                            </h2>
                            @if($sizeSummary)
                                <p class="mt-1.5 text-xs text-graphite-500">Ukuran {{ $sizeSummary }}</p>
                            @endif
                            <a href="{{ route('store.show', $product->slug) }}" class="mt-auto pt-4 link-arrow text-sm" tabindex="-1">
                                <span>Lihat Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="mt-14">
                    {{ $products->onEachSide(1)->links('vendor.pagination.lux') }}
                </div>
            @endif

        @elseif($hasAnyProduct)
            <div class="max-w-lg mx-auto text-center py-16">
                <h2 class="text-xl">Tidak ada sepatu yang cocok</h2>
                <p class="mt-3 text-graphite-600">Coba kata kunci lain, atau tampilkan semua ukuran dan kategori.</p>
                <a href="{{ route('store') }}" class="btn btn-outline mt-7">Tampilkan semua sepatu</a>
            </div>
        @else
            <div class="max-w-lg mx-auto text-center py-16">
                <h2 class="text-xl">Koleksi sedang kami siapkan</h2>
                <p class="mt-3 text-graphite-600">Belum ada sepatu yang ditampilkan saat ini. Silakan kembali lagi nanti, atau hubungi kami untuk menanyakan model tertentu.</p>
                <a href="{{ lroute('contact') }}" class="btn btn-outline mt-7">Hubungi kami</a>
            </div>
        @endif
    </div>
</section>

@endsection
