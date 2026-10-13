{{-- One shoe in a catalogue grid. Expects $product with brand, category, images and variants loaded. --}}
@php
    $photo = $product->primaryImage();
    $label = collect([$product->brandName(), $product->category?->name])->filter()->implode(' · ');
    $sizeSummary = $product->sizeSummary();
@endphp
<article class="store-card group flex flex-col">
    <a href="{{ route('store.show', $product->slug) }}" class="block"
       aria-label="Lihat detail {{ $product->name }}">
        <div class="store-photo">
            @if($photo && $photo->url)
                <img src="{{ $photo->url }}"
                     alt="{{ $photo->alt ?: $product->name . ($product->brandName() ? ' — ' . $product->brandName() : '') }}"
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
        <h3 class="mt-1.5 text-[0.9375rem] sm:text-base leading-snug font-semibold text-graphite-900">
            <a href="{{ route('store.show', $product->slug) }}" class="hover:text-gold-700 transition-colors">{{ $product->name }}</a>
        </h3>
        @if($sizeSummary)
            <p class="mt-1.5 text-xs text-graphite-500">Ukuran {{ $sizeSummary }}</p>
        @endif
        <a href="{{ route('store.show', $product->slug) }}" class="mt-auto pt-4 link-arrow text-sm" tabindex="-1">
            <span>Lihat Detail</span>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</article>
