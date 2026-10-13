{{-- Aldef Store product gallery: photos and videos. Clicking an item opens it
     in a full-screen popup (photo enlarged, or the video with its player).
     Lives inside the product page's x-data="storeProduct(…)". --}}
@php
    $playIcon = '<svg class="w-6 h-6 ml-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.14v13.72a1 1 0 001.52.85l10.9-6.86a1 1 0 000-1.7L9.52 4.29A1 1 0 008 5.14z"/></svg>';
@endphp

<div class="lg:col-span-7">
    <div class="store-photo">
        @forelse($images as $index => $item)
            <button type="button" class="absolute inset-0 w-full h-full cursor-zoom-in"
                    x-show="image === {{ $index }}" @if($index > 0) x-cloak @endif
                    @click="openViewer({{ $index }})"
                    aria-label="{{ $item->isVideo() ? 'Putar video' : 'Perbesar foto' }} {{ $index + 1 }}">
                @if($item->isVideo())
                    {{-- First frame as the preview; the real player opens in the popup. --}}
                    <video src="{{ $item->url }}#t=0.1" preload="metadata" muted playsinline
                           class="w-full h-full object-contain p-[6%]" aria-hidden="true" tabindex="-1"></video>
                    <span class="absolute inset-0 flex items-center justify-center">
                        <span class="w-16 h-16 rounded-full bg-graphite-900/80 text-white flex items-center justify-center shadow-lg">{!! $playIcon !!}</span>
                    </span>
                @else
                    <img src="{{ $item->url }}" alt="{{ $imageAlt($item, $index) }}"
                         width="900" height="900" decoding="async"
                         @if($index === 0) fetchpriority="high" @else loading="lazy" @endif
                         class="w-full h-full object-contain p-[6%]">
                @endif
            </button>
        @empty
            <span class="absolute inset-0 flex items-center justify-center text-gold-400/60" aria-hidden="true">
                <x-lux-icon name="layers" class="w-14 h-14" />
            </span>
        @endforelse

        @if($images->count() > 1)
            <button type="button" @click="show(image - 1)" aria-label="Media sebelumnya"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/90 border border-line flex items-center justify-center text-graphite-700 hover:border-gold-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" @click="show(image + 1)" aria-label="Media berikutnya"
                    class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/90 border border-line flex items-center justify-center text-graphite-700 hover:border-gold-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        @endif
    </div>

    @if($images->count() > 1)
        <div class="mt-3 grid grid-cols-5 sm:grid-cols-6 gap-2.5" role="group" aria-label="Pilih foto atau video">
            @foreach($images as $index => $item)
                <button type="button" class="store-thumb relative" @click="show({{ $index }})"
                        :class="image === {{ $index }} && 'is-active'"
                        :aria-current="image === {{ $index }} ? 'true' : 'false'"
                        aria-label="Tampilkan {{ $item->isVideo() ? 'video' : 'foto' }} {{ $index + 1 }}">
                    @if($item->isVideo())
                        <video src="{{ $item->url }}#t=0.1" preload="metadata" muted playsinline
                               class="w-full h-full object-contain p-[8%]" aria-hidden="true" tabindex="-1"></video>
                        <span class="absolute inset-0 flex items-center justify-center">
                            <span class="w-7 h-7 rounded-full bg-graphite-900/80 text-white flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 ml-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.14v13.72a1 1 0 001.52.85l10.9-6.86a1 1 0 000-1.7L9.52 4.29A1 1 0 008 5.14z"/></svg>
                            </span>
                        </span>
                    @else
                        <img src="{{ $item->url }}" alt="" loading="lazy" decoding="async" width="160" height="160">
                    @endif
                </button>
            @endforeach
        </div>
    @endif

    @if($images->isNotEmpty())
        <p class="mt-2 text-xs text-graphite-500">Klik foto atau video untuk memperbesar.</p>
    @endif
</div>

{{-- ── Popup ─────────────────────────────────────────────────────────── --}}
@if($images->isNotEmpty())
<div x-show="viewer !== null" x-cloak
     x-transition.opacity.duration.200ms
     class="fixed inset-0 z-[100] bg-[#05080d]/95 flex items-center justify-center"
     role="dialog" aria-modal="true" aria-label="Foto dan video {{ $product->name }}"
     @click.self="closeViewer()"
     @keydown.escape.window="closeViewer()"
     @keydown.arrow-left.window="viewerStep(-1)"
     @keydown.arrow-right.window="viewerStep(1)">

    <p class="absolute top-4 left-4 text-sm text-white/70 tabular" aria-live="polite">
        <span x-text="(viewer ?? 0) + 1"></span> / {{ $images->count() }}
    </p>

    <button type="button" x-ref="viewerClose" @click="closeViewer()" aria-label="Tutup"
            class="absolute top-3 right-3 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    @foreach($images as $index => $item)
        @if($item->isVideo())
            {{-- Mounted only while open: closing or moving away stops playback. --}}
            <template x-if="viewer === {{ $index }}">
                <video controls autoplay playsinline preload="auto"
                       class="max-h-[85vh] max-w-[92vw] rounded-lg bg-black"
                       aria-label="{{ $imageAlt($item, $index) }}">
                    <source src="{{ $item->url }}" type="{{ $item->mime }}">
                    Browser Anda tidak dapat memutar video ini.
                </video>
            </template>
        @else
            <img x-show="viewer === {{ $index }}" src="{{ $item->url }}" alt="{{ $imageAlt($item, $index) }}"
                 loading="lazy" decoding="async"
                 class="max-h-[85vh] max-w-[92vw] object-contain rounded-lg bg-white">
        @endif
    @endforeach

    @if($images->count() > 1)
        <button type="button" @click="viewerStep(-1)" aria-label="Sebelumnya"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" @click="viewerStep(1)" aria-label="Berikutnya"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    @endif
</div>
@endif
