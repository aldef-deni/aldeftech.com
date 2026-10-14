{{-- One shoe in a catalogue grid. Expects $product with brand, category, images and variants loaded. --}}
@php
    $photo = $product->primaryImage();
    $label = collect([$product->brandName(), $product->category?->name])->filter()->implode(' · ');
    $sizeSummary = $product->sizeSummary();
@endphp
<article class="store-card group">
    <a href="{{ route('store.show', $product->slug) }}" class="block"
       aria-label="Lihat detail {{ $product->name }}">
        <div class="store-photo" @if($photo && $photo->url) style="--photo: url('{{ $photo->url }}')" @endif>
            @if($photo && $photo->url)
                <img src="{{ $photo->url }}"
                     alt="{{ $photo->alt ?: $product->name . ($product->brandName() ? ' — ' . $product->brandName() : '') }}"
                     loading="lazy" decoding="async" width="600" height="600">
            @else
                <span class="absolute inset-0 flex items-center justify-center text-[#FF5A64]/50" aria-hidden="true">
                    <x-lux-icon name="layers" class="w-10 h-10" />
                </span>
            @endif
            @if($product->isAvailable() === false)
                <span class="store-badge-out">Stok habis</span>
            @endif
        </div>
    </a>

    <div class="store-card-body">
        @if($label)
            <p class="store-card-label truncate">{{ $label }}</p>
        @endif
        <h3 class="store-card-title mt-1.5 text-[0.9375rem] sm:text-base leading-snug font-semibold">
            <a href="{{ route('store.show', $product->slug) }}" class="transition-colors">{{ $product->name }}</a>
        </h3>
        @if($sizeSummary)
            <p class="store-card-meta mt-1.5 text-xs">Ukuran {{ $sizeSummary }}</p>
        @endif
        <a href="{{ route('store.show', $product->slug) }}" class="store-card-link mt-auto pt-4" tabindex="-1">
            <span>Lihat Detail</span>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</article>
