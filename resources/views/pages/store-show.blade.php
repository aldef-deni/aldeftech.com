@extends('layouts.app')

@php
    $images = $product->images;
    $sizes = $product->sizes();
    $colors = $product->colors();
    $label = collect([$product->brandName(), $product->category?->name])->filter()->implode(' · ');
    $productUrl = route('store.show', $product->slug);
    $available = $product->isAvailable();

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
        ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => $productUrl],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<x-page-hero
    :eyebrow="$label ?: 'Aldef Store'"
    :title="$product->name"
    align="left"
    compact
    :breadcrumbs="[
        ['label' => 'Aldef Store', 'url' => route('store')],
        ['label' => $product->name],
    ]" />

<section class="surface-ivory py-12 lg:py-16" x-data="storeProduct(@js($storeConfig))">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">

            {{-- ── Gallery (photos, videos, popup viewer) ──────────────── --}}
            @include('pages.partials.store-gallery')

            {{-- ── Picker ──────────────────────────────────────────────── --}}
            <div class="lg:col-span-5">
                <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="eyebrow">Kode produk</dt>
                        <dd class="mt-1.5 font-semibold text-graphite-900 tabular">{{ $product->sku }}</dd>
                    </div>
                    @if($product->brandName())
                    <div>
                        <dt class="eyebrow">Brand</dt>
                        <dd class="mt-1.5 font-semibold text-graphite-900"><a href="{{ route('store', ['brand' => $product->brand->slug]) }}" class="hover:text-gold-700">{{ $product->brandName() }}</a></dd>
                    </div>
                    @endif
                    @if($product->category)
                    <div>
                        <dt class="eyebrow">Kategori</dt>
                        <dd class="mt-1.5 font-semibold text-graphite-900">
                            @if($product->category->parent)
                                <a href="{{ route('store', ['kategori' => $product->category->parent->slug]) }}" class="hover:text-gold-700">{{ $product->category->parent->name }}</a>
                                <span class="text-graphite-400" aria-hidden="true">›</span>
                            @endif
                            <a href="{{ route('store', ['kategori' => $product->category->slug]) }}" class="hover:text-gold-700">{{ $product->category->name }}</a>
                        </dd>
                    </div>
                    @endif
                    @if($product->material)
                    <div>
                        <dt class="eyebrow">Material</dt>
                        <dd class="mt-1.5 font-semibold text-graphite-900">{{ $product->material }}</dd>
                    </div>
                    @endif
                </dl>

                <p class="mt-6 text-sm" aria-live="polite">
                    @if($available === false)
                        <span class="inline-flex items-center gap-2 text-[#B85C4A] font-medium">
                            <span class="w-2 h-2 rounded-full bg-[#B85C4A]" aria-hidden="true"></span>Semua varian sedang habis
                        </span>
                    @elseif($available === null)
                        <span class="text-graphite-600">Ketersediaan dikonfirmasi melalui WhatsApp.</span>
                    @else
                        <span x-show="problem === null" class="inline-flex items-center gap-2 text-[#3F7A4F] font-medium">
                            <span class="w-2 h-2 rounded-full bg-[#3F7A4F]" aria-hidden="true"></span>
                            <span x-text="(color || size) ? 'Pilihan Anda tersedia' : 'Tersedia'">Tersedia</span>
                        </span>
                        <span x-show="problem === 'unavailable'" x-cloak class="inline-flex items-center gap-2 text-[#B85C4A] font-medium">
                            <span class="w-2 h-2 rounded-full bg-[#B85C4A]" aria-hidden="true"></span>Kombinasi ini habis
                        </span>
                        <span x-show="problem === 'pick-size' || problem === 'pick-color'" x-cloak class="text-graphite-600">
                            Pilih {{ $colors->isNotEmpty() ? 'warna dan ukuran' : 'ukuran' }} untuk melihat ketersediaan.
                        </span>
                    @endif
                </p>

                @if($colors->isNotEmpty())
                <fieldset class="mt-8">
                    <legend class="form-label !mb-3">
                        Warna <span class="text-graphite-500 font-normal" x-show="color" x-text="'— ' + color" x-cloak></span>
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
                <fieldset class="mt-8">
                    <legend class="form-label !mb-3">
                        Ukuran <span class="text-graphite-500 font-normal">({{ $product->size_system }})</span>
                    </legend>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $size)
                            <button type="button" class="store-option"
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
                    <details class="mt-4 rounded-xl border border-line bg-white px-4 py-3 text-sm">
                        <summary class="cursor-pointer font-medium text-graphite-800">Panduan ukuran</summary>
                        <div class="mt-3 text-graphite-600 leading-relaxed">{!! nl2br(e($product->size_guide)) !!}</div>
                    </details>
                    @endif
                </fieldset>
                @endif

                <div class="mt-8 grid grid-cols-1 sm:grid-cols-[auto_minmax(0,1fr)] gap-6">
                    <div>
                        <label for="store-qty" class="form-label">Jumlah (pasang)</label>
                        <div class="flex items-center gap-2">
                            <button type="button" class="store-option !min-w-0 w-11 h-11 !p-0" @click="step(-1)" :disabled="quantity <= 1" aria-label="Kurangi jumlah">−</button>
                            <input id="store-qty" type="number" inputmode="numeric" min="1" max="999" step="1"
                                   x-model="quantity" @blur="fixQuantity()"
                                   class="form-input !w-20 text-center tabular" value="1">
                            <button type="button" class="store-option !min-w-0 w-11 h-11 !p-0" @click="step(1)" aria-label="Tambah jumlah">+</button>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="store-note" class="form-label">Catatan <span class="text-graphite-500 font-normal">(opsional)</span></label>
                    <textarea id="store-note" x-model="note" rows="3" maxlength="500" class="form-input resize-y"
                              placeholder="Misalnya kota pengiriman atau pertanyaan lain"></textarea>
                </div>

                @unless($waNumber)
                    <div class="mt-6 rounded-xl border border-gold-300 bg-gold-100 px-4 py-3 text-sm text-graphite-700">
                        Nomor WhatsApp toko belum diatur, jadi pertanyaan belum bisa dikirim dari halaman ini.
                        Silakan hubungi kami melalui <a href="{{ lroute('contact') }}" class="font-semibold underline">halaman Kontak</a>.
                    </div>
                @endunless

                {{-- Desktop button; on phones the same button sits in the bar below. --}}
                <div class="mt-8 hidden lg:block">
                    @include('pages.partials.store-ask-button')
                </div>

                <p class="mt-3 text-sm text-graphite-600 hidden lg:block" x-show="problem !== null" x-text="problemText" x-cloak></p>
                <p class="mt-3 text-xs text-graphite-500 hidden lg:block">Harga dan ketersediaan akan kami informasikan melalui WhatsApp.</p>
            </div>
        </div>

        @if(filled($product->description))
        <div class="mt-16 lg:mt-20 max-w-3xl">
            <h2 class="text-xl">Deskripsi</h2>
            <div class="prose-lux mt-5">{!! nl2br(e($product->description)) !!}</div>
        </div>
        @endif
    </div>

    {{-- Phones: the button stays within thumb reach; the body padding pushed
         above keeps the bar from covering the end of the page. --}}
    <div class="store-ask-bar lg:hidden">
        <p class="mb-2 text-xs text-graphite-600 text-center" x-show="problem !== null" x-text="problemText" x-cloak></p>
        @include('pages.partials.store-ask-button')
    </div>
</section>

@if($related->isNotEmpty())
<section class="surface-ivory-deep border-t border-line py-14 lg:py-20">
    <div class="shell">
        <div class="flex items-end justify-between gap-4">
            <h2 class="text-xl lg:text-2xl">Sepatu lainnya</h2>
            <a href="{{ route('store') }}" class="link-arrow text-sm">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-x-3 gap-y-8 sm:gap-x-5 lg:gap-x-6">
            @foreach($related as $item)
                @php $photo = $item->primaryImage(); @endphp
                <a href="{{ route('store.show', $item->slug) }}" class="store-card group block">
                    <div class="store-photo">
                        @if($photo && $photo->url)
                            <img src="{{ $photo->url }}" alt="{{ $photo->alt ?: $item->name }}" loading="lazy" decoding="async" width="600" height="600">
                        @endif
                    </div>
                    <p class="mt-3 text-[0.9375rem] font-semibold text-graphite-900 leading-snug">{{ $item->name }}</p>
                    @if($item->brandName())<p class="mt-1 text-xs text-graphite-500">{{ $item->brandName() }}</p>@endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
