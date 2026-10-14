@extends('layouts.app')

@php
    $images = $product->images;
    $sizes = $product->sizes();
    $colors = $product->colors();
    $label = collect([$product->brandName(), $product->category?->name])->filter()->implode(' · ');
    $productUrl = route('store.show', $product->slug);
    $available = $product->isAvailable();

    // Brand and category lead back into the brand-first catalogue; a shoe
    // without a brand falls back to the category filter.
    $brandUrl = $product->brand ? route('store.brand', $product->brand->slug) : null;
    $categoryUrl = function ($category) use ($product) {
        if (! $product->brand) {
            return route('store', ['kategori' => $category->slug]);
        }

        return route('store.brand', array_values(array_filter([
            $product->brand->slug, $category->parent?->slug, $category->slug,
        ])));
    };

    $pageTitle = $product->meta_title
        ?: $product->name . ($product->brandName() ? ' — ' . $product->brandName() : '') . ' | Aldef Store';
    $metaDescription = $product->meta_description
        ?: (filled($product->description)
            ? excerpt_text($product->description, 158)
            : 'Lihat detail ' . $product->name . ' di Aldef Store. Pilih ukuran, lalu tanyakan harga dan ketersediaan melalui WhatsApp.');
    $ogImage = $product->primaryImage()?->url;
    $ogType = 'product';
    $hreflangLocales = ['id'];
    $canonical = $productUrl;
    $analyticsPageType = 'store-product';
    $analyticsItem = $product->sku;
    // The page has its own WhatsApp button; the floating widget would sit on it.
    $hideWhatsAppWidget = true;
    // The page is dark from top to bottom; so is the navbar once scrolled.
    $bodyClass = 'nav-dark-page';

    $imageAlt = fn ($image, $index) => $image->alt
        ?: $product->name . ($product->brandName() ? ' — ' . $product->brandName() : '')
            . ($images->count() > 1 ? ($image->isVideo() ? ' (video ' : ' (foto ') . ($index + 1) . ')' : '');

    $storeConfig = [
        'name' => $product->name,
        'sku' => $product->sku,
        'url' => $productUrl,
        'sizeSystem' => $product->size_system,
        'number' => $waNumber,
        'variants' => $product->variantMatrix(),
        'sizes' => $sizes->all(),
        'colors' => $colors->all(),
        'imageCount' => $images->count(),
        'messages' => [
            'no-number' => 'Nomor WhatsApp toko belum diatur. Silakan hubungi kami melalui halaman Kontak.',
            'pick-color' => 'Pilih warna terlebih dahulu.',
            'pick-size' => 'Pilih ukuran terlebih dahulu.',
            'quantity' => 'Jumlah minimal 1 pasang.',
            'unavailable' => 'Kombinasi ukuran dan warna ini sedang habis. Silakan pilih yang lain.',
        ],
    ];
@endphp

@push('styles')
{{-- Room under the page for the phone WhatsApp bar, so it never covers the footer. --}}
<style>@media (max-width: 1023.98px) { body { padding-bottom: calc(6.5rem + env(safe-area-inset-bottom)); } }</style>
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => route('home') . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Aldef Store', 'item' => route('store')],
        ...($brandUrl ? [['@type' => 'ListItem', 'position' => 3, 'name' => $product->brandName(), 'item' => $brandUrl]] : []),
        ['@type' => 'ListItem', 'position' => $brandUrl ? 4 : 3, 'name' => $product->name, 'item' => $productUrl],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<section class="store-pdp pt-28 pb-14 lg:pt-36 lg:pb-24" x-data="storeProduct(@js($storeConfig))">
    <div class="shell">
        <nav class="mb-6 lg:mb-10 flex flex-wrap items-center gap-2 text-xs text-zinc-500" aria-label="Breadcrumb">
            <a href="{{ lroute('home') }}" class="hover:text-white transition-colors">{{ __('site.nav.home') }}</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('store') }}" class="hover:text-white transition-colors">Aldef Store</a>
            @if($brandUrl)
                <span aria-hidden="true">/</span>
                <a href="{{ $brandUrl }}" class="hover:text-white transition-colors">{{ $product->brandName() }}</a>
            @endif
            <span aria-hidden="true">/</span>
            <span class="text-zinc-300 truncate max-w-[14rem]">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14">

            {{-- ── Gallery (swipeable, popup viewer) ───────────────────── --}}
            @include('pages.partials.store-gallery')

            {{-- ── Product and picker ──────────────────────────────────── --}}
            <div class="lg:col-span-5">
                @if($label)
                    <p class="eyebrow">
                        @if($brandUrl)<a href="{{ $brandUrl }}" class="hover:text-white transition-colors">{{ $product->brandName() }}</a>@endif
                        @if($brandUrl && $product->category)<span class="text-zinc-600" aria-hidden="true">·</span>@endif
                        @if($product->category)<a href="{{ $categoryUrl($product->category) }}" class="hover:text-white transition-colors">{{ $product->category->name }}</a>@endif
                    </p>
                @endif

                <h1 class="mt-4 text-[2rem] leading-[1.08] sm:text-[2.5rem] lg:text-[2.875rem] tracking-tight">{{ $product->name }}</h1>

                <div class="mt-5 flex flex-wrap items-center gap-2" aria-live="polite">
                    <span class="store-pill tabular">Kode {{ $product->sku }}</span>
                    @if($available === false)
                        <span class="store-pill store-pill-out"><span class="w-1.5 h-1.5 rounded-full bg-current" aria-hidden="true"></span>Semua varian habis</span>
                    @elseif($available === null)
                        <span class="store-pill">Stok dikonfirmasi via WhatsApp</span>
                    @else
                        <span x-show="problem !== 'unavailable'" class="store-pill store-pill-ok">
                            <span class="w-1.5 h-1.5 rounded-full bg-current" aria-hidden="true"></span>
                            <span x-text="problem === null ? 'Pilihan Anda tersedia' : 'Tersedia'">Tersedia</span>
                        </span>
                        <span x-show="problem === 'unavailable'" x-cloak class="store-pill store-pill-out">
                            <span class="w-1.5 h-1.5 rounded-full bg-current" aria-hidden="true"></span>Kombinasi ini habis
                        </span>
                    @endif
                    @if($product->is_featured)
                        <span class="store-pill">
                            <svg class="w-3.5 h-3.5 text-[#FF5A64]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.5l2.9 6.1 6.6.8-4.9 4.5 1.3 6.6L12 17.2l-5.9 3.3 1.3-6.6-4.9-4.5 6.6-.8z"/></svg>Pilihan
                        </span>
                    @endif
                </div>

                <p class="mt-6 text-[0.9375rem] leading-relaxed text-zinc-400">
                    Pilih {{ $colors->isNotEmpty() ? 'warna dan ukuran' : 'ukuran' }}, lalu kirim pertanyaan Anda. Harga dan stok terbaru kami informasikan langsung melalui WhatsApp.
                </p>

                <div class="store-panel mt-8 p-5 sm:p-7">
                    @if($colors->isNotEmpty())
                    <fieldset>
                        <legend class="form-label !mb-3">
                            Warna <span class="text-zinc-400 font-normal" x-show="color" x-text="'— ' + color" x-cloak></span>
                        </legend>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <button type="button" class="store-option"
                                        @click="pickColor(@js($color))"
                                        :class="color === @js($color) && 'is-selected'"
                                        :aria-pressed="(color === @js($color)).toString()"
                                        :disabled="!colorSelectable(@js($color))"
                                        @disabled(! $product->variants->where('color', $color)->contains('is_available', true))>
                                    {{ $color }}
                                </button>
                            @endforeach
                        </div>
                    </fieldset>
                    @endif

                    @if($sizes->isNotEmpty())
                    <fieldset @class(['mt-7' => $colors->isNotEmpty()])>
                        <legend class="form-label !mb-3 flex w-full items-center justify-between gap-3">
                            <span>Ukuran <span class="text-zinc-400 font-normal">({{ $product->size_system }})</span></span>
                        </legend>
                        <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                            @foreach($sizes as $size)
                                <button type="button" class="store-option !min-w-0"
                                        @click="pickSize(@js($size))"
                                        :class="size === @js($size) && 'is-selected'"
                                        :aria-pressed="(size === @js($size)).toString()"
                                        :disabled="!sizeSelectable(@js($size))"
                                        @disabled(! $product->variants->where('size', $size)->contains('is_available', true))>
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>

                        @if(filled($product->size_guide))
                        <details class="mt-4 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3 text-sm">
                            <summary class="cursor-pointer font-medium text-zinc-200">Panduan ukuran</summary>
                            <div class="mt-3 text-zinc-400 leading-relaxed">{!! nl2br(e($product->size_guide)) !!}</div>
                        </details>
                        @endif
                    </fieldset>
                    @endif

                    <div @class(['mt-7' => $colors->isNotEmpty() || $sizes->isNotEmpty()])>
                        <label for="store-qty" class="form-label">Jumlah (pasang)</label>
                        <div class="inline-flex items-center gap-2">
                            <button type="button" class="store-option !min-w-0 w-11 h-11 !p-0" @click="step(-1)" :disabled="quantity <= 1" aria-label="Kurangi jumlah">−</button>
                            <input id="store-qty" type="number" inputmode="numeric" min="1" max="999" step="1"
                                   x-model="quantity" @blur="fixQuantity()"
                                   class="form-input !w-20 h-11 !py-0 text-center tabular" value="1">
                            <button type="button" class="store-option !min-w-0 w-11 h-11 !p-0" @click="step(1)" aria-label="Tambah jumlah">+</button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="store-note" class="form-label">Catatan <span class="text-zinc-400 font-normal">(opsional)</span></label>
                        <textarea id="store-note" x-model="note" rows="2" maxlength="500" class="form-input resize-y"
                                  placeholder="Misalnya kota pengiriman atau pertanyaan lain"></textarea>
                    </div>

                    @unless($waNumber)
                        <div class="mt-6 rounded-xl border border-[#FF5A64]/30 bg-[#E11D2A]/10 px-4 py-3 text-sm text-zinc-200">
                            Nomor WhatsApp toko belum diatur, jadi pertanyaan belum bisa dikirim dari halaman ini.
                            Silakan hubungi kami melalui <a href="{{ lroute('contact') }}" class="font-semibold underline">halaman Kontak</a>.
                        </div>
                    @endunless

                    {{-- Desktop button; on phones the same button sits in the bar below. --}}
                    <div class="mt-7 hidden lg:block">
                        @include('pages.partials.store-ask-button')
                        <p class="mt-3 text-sm text-[#FF8A91]" x-show="problem !== null && touched" x-text="problemText" x-cloak></p>
                    </div>
                </div>

                <ul class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                    <li class="store-panel flex sm:flex-col items-center sm:items-start gap-3 p-4">
                        <svg class="w-5 h-5 shrink-0 text-[#FF5A64]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-9 6l2.5-3H18a3 3 0 003-3V7a3 3 0 00-3-3H6a3 3 0 00-3 3v7a3 3 0 001 2.2V20z"/></svg>
                        <span><span class="block font-medium text-zinc-100">Tanya langsung</span><span class="text-xs text-zinc-500">Harga dibalas via WhatsApp</span></span>
                    </li>
                    <li class="store-panel flex sm:flex-col items-center sm:items-start gap-3 p-4">
                        <svg class="w-5 h-5 shrink-0 text-[#FF5A64]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5l13.5-13.5 4.5 4.5L7.5 21H3v-4.5zM14 6l1.5 1.5M11 9l1.5 1.5M8 12l1.5 1.5"/></svg>
                        <span><span class="block font-medium text-zinc-100">Bantu pilih ukuran</span><span class="text-xs text-zinc-500">Konsultasikan ukuran Anda</span></span>
                    </li>
                    <li class="store-panel flex sm:flex-col items-center sm:items-start gap-3 p-4">
                        <svg class="w-5 h-5 shrink-0 text-[#FF5A64]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5l2 2 4-4.5M12 3l7.5 3v5.5c0 4.5-3.2 8.3-7.5 9.5-4.3-1.2-7.5-5-7.5-9.5V6L12 3z"/></svg>
                        <span><span class="block font-medium text-zinc-100">Stok dikonfirmasi</span><span class="text-xs text-zinc-500">Sebelum Anda memesan</span></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Phones: the button stays within thumb reach; the body padding pushed
         above keeps the bar from covering the end of the page. --}}
    <div class="store-ask-bar lg:hidden">
        <p class="mb-2 text-xs text-[#FF8A91] text-center" x-show="problem !== null && touched" x-text="problemText" x-cloak></p>
        @include('pages.partials.store-ask-button')
    </div>
</section>

{{-- ── Details ─────────────────────────────────────────────────────────── --}}
<section class="store-pdp border-t border-white/5 py-14 lg:py-20">
    <div class="shell grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
        <div class="lg:col-span-7">
            <p class="eyebrow">Detail produk</p>
            <h2 class="mt-4 text-2xl lg:text-3xl">Tentang {{ $product->name }}</h2>
            @if(filled($product->description))
                <div class="mt-6 text-[0.9375rem] leading-[1.8] text-zinc-300 max-w-2xl">{!! nl2br(e($product->description)) !!}</div>
            @else
                <p class="mt-6 text-[0.9375rem] leading-[1.8] text-zinc-400 max-w-2xl">Ingin tahu lebih banyak tentang model ini? Tanyakan detail, harga, dan stoknya kepada kami melalui WhatsApp.</p>
            @endif
        </div>

        <div class="lg:col-span-5">
            <dl class="store-panel divide-y divide-white/[0.07] text-sm">
                <div class="flex items-center justify-between gap-6 px-5 py-4">
                    <dt class="text-zinc-500">Kode produk</dt>
                    <dd class="font-medium text-zinc-100 tabular">{{ $product->sku }}</dd>
                </div>
                @if($product->brandName())
                <div class="flex items-center justify-between gap-6 px-5 py-4">
                    <dt class="text-zinc-500">Brand</dt>
                    <dd class="font-medium text-zinc-100"><a href="{{ $brandUrl }}" class="hover:text-[#FF8A91]">{{ $product->brandName() }}</a></dd>
                </div>
                @endif
                @if($product->category)
                <div class="flex items-center justify-between gap-6 px-5 py-4">
                    <dt class="text-zinc-500">Kategori</dt>
                    <dd class="font-medium text-zinc-100 text-right">
                        @if($product->category->parent)
                            <a href="{{ $categoryUrl($product->category->parent) }}" class="hover:text-[#FF8A91]">{{ $product->category->parent->name }}</a>
                            <span class="text-zinc-600" aria-hidden="true">›</span>
                        @endif
                        <a href="{{ $categoryUrl($product->category) }}" class="hover:text-[#FF8A91]">{{ $product->category->name }}</a>
                    </dd>
                </div>
                @endif
                @if($product->material)
                <div class="flex items-center justify-between gap-6 px-5 py-4">
                    <dt class="text-zinc-500">Material</dt>
                    <dd class="font-medium text-zinc-100 text-right">{{ $product->material }}</dd>
                </div>
                @endif
                @if($sizes->isNotEmpty())
                <div class="flex items-center justify-between gap-6 px-5 py-4">
                    <dt class="text-zinc-500">Ukuran</dt>
                    <dd class="font-medium text-zinc-100 tabular">{{ $product->sizeSummary() }}</dd>
                </div>
                @endif
                @if($colors->isNotEmpty())
                <div class="flex items-center justify-between gap-6 px-5 py-4">
                    <dt class="text-zinc-500">Warna</dt>
                    <dd class="font-medium text-zinc-100 text-right">{{ $colors->implode(', ') }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</section>

@if($related->isNotEmpty())
<section class="surface-ivory py-14 lg:py-20">
    <div class="shell">
        <div class="flex items-end justify-between gap-4">
            <h2 class="text-xl lg:text-2xl">Sepatu lainnya</h2>
            <a href="{{ $brandUrl ?? route('store') }}" class="link-arrow text-sm">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 lg:gap-6">
            @foreach($related as $item)
                @include('pages.partials.store-product-card', ['product' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
