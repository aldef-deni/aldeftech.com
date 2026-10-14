{{-- Aldef Store product gallery: photos and videos on a track that a finger
     swipes (scroll snap), with arrows, dots and thumbnails. Tapping an item
     opens it full screen, where the same swipe works. Lives inside the
     product page's x-data="storeProduct(…)". --}}
@php
    $count = $images->count();
    $playIcon = '<svg class="w-6 h-6 ml-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.14v13.72a1 1 0 001.52.85l10.9-6.86a1 1 0 000-1.7L9.52 4.29A1 1 0 008 5.14z"/></svg>';
@endphp

<div class="lg:col-span-7 lg:sticky lg:top-28 self-start">
    <div class="store-stage">
        @if($count > 0)
        <div class="store-track" x-ref="slider" @scroll.debounce.80ms="syncSlider()"
             aria-roledescription="carousel" aria-label="Foto dan video {{ $product->name }}">
            @foreach($images as $index => $item)
                <div class="store-photo" @unless($item->isVideo()) style="--photo: url('{{ $item->url }}')" @endunless
                     role="group" aria-roledescription="slide" aria-label="{{ $index + 1 }} dari {{ $count }}">
                    <button type="button" class="absolute inset-0 w-full h-full cursor-zoom-in"
                            @click="openViewer({{ $index }})"
                            aria-label="{{ $item->isVideo() ? 'Putar video' : 'Perbesar foto' }} {{ $index + 1 }}">
                        @if($item->isVideo())
                            {{-- First frame as the preview; the real player opens in the popup. --}}
                            <video src="{{ $item->url }}#t=0.1" preload="metadata" muted playsinline aria-hidden="true" tabindex="-1"></video>
                            <span class="absolute inset-0 flex items-center justify-center">
                                <span class="w-16 h-16 rounded-full bg-[#A3191F]/90 text-white flex items-center justify-center shadow-lg ring-4 ring-white/10">{!! $playIcon !!}</span>
                            </span>
                        @else
                            <img src="{{ $item->url }}" alt="{{ $imageAlt($item, $index) }}"
                                 width="900" height="900" decoding="async" draggable="false"
                                 @if($index === 0) fetchpriority="high" @else loading="lazy" @endif>
                        @endif
                    </button>
                </div>
            @endforeach
        </div>
        @else
            <div class="store-photo">
                <span class="absolute inset-0 flex items-center justify-center text-[#FF5A64]/50" aria-hidden="true">
                    <x-lux-icon name="layers" class="w-14 h-14" />
                </span>
            </div>
        @endif

        @if($count > 1)
            <button type="button" @click="show(image - 1)" aria-label="Media sebelumnya" class="store-stage-nav left-3 hidden sm:flex">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" @click="show(image + 1)" aria-label="Media berikutnya" class="store-stage-nav right-3 hidden sm:flex">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <span class="store-stage-count" aria-live="polite"><span x-text="image + 1">1</span> / {{ $count }}</span>
        @endif
    </div>

    @if($count > 1)
        @if($count <= 12)
            <div class="store-dots mt-4 sm:hidden" aria-hidden="true">
                @foreach($images as $index => $item)
                    <span :class="image === {{ $index }} && 'is-active'"></span>
                @endforeach
            </div>
        @endif

        <div class="store-thumbs mt-4 hidden sm:flex" role="group" aria-label="Pilih foto atau video">
            @foreach($images as $index => $item)
                <button type="button" class="store-thumb" @click="show({{ $index }})"
                        :class="image === {{ $index }} && 'is-active'"
                        :aria-current="image === {{ $index }} ? 'true' : 'false'"
                        aria-label="Tampilkan {{ $item->isVideo() ? 'video' : 'foto' }} {{ $index + 1 }}">
                    @if($item->isVideo())
                        <video src="{{ $item->url }}#t=0.1" preload="metadata" muted playsinline aria-hidden="true" tabindex="-1"></video>
                        <span class="absolute inset-0 flex items-center justify-center">
                            <span class="w-7 h-7 rounded-full bg-[#A3191F]/90 text-white flex items-center justify-center">
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

    @if($count > 0)
        <p class="mt-3 text-xs text-zinc-500 text-center sm:text-left">
            <span class="sm:hidden">{{ $count > 1 ? 'Geser untuk foto lainnya · ' : '' }}Ketuk untuk memperbesar</span>
            <span class="hidden sm:inline">Klik foto atau video untuk memperbesar</span>
        </p>
    @endif
</div>

{{-- ── Popup: full screen, swipeable ───────────────────────────────────── --}}
@if($count > 0)
<div x-show="viewer !== null" x-cloak
     x-transition.opacity.duration.200ms
     class="fixed inset-0 z-[100] bg-[#050506]/[0.97]"
     role="dialog" aria-modal="true" aria-label="Foto dan video {{ $product->name }}"
     @keydown.escape.window="closeViewer()"
     @keydown.arrow-left.window="viewerStep(-1)"
     @keydown.arrow-right.window="viewerStep(1)">

    <div class="store-track h-full" x-ref="viewerTrack" @scroll.debounce.80ms="syncViewer()">
        @foreach($images as $index => $item)
            <div class="h-full flex items-center justify-center px-3 py-16 sm:px-20" @click.self="closeViewer()">
                @if($item->isVideo())
                    {{-- Mounted only while open: closing or moving away stops playback. --}}
                    <template x-if="viewer === {{ $index }}">
                        <video controls autoplay playsinline preload="auto"
                               class="max-h-full max-w-full rounded-xl bg-black"
                               aria-label="{{ $imageAlt($item, $index) }}">
                            <source src="{{ $item->url }}" type="{{ $item->mime }}">
                            Browser Anda tidak dapat memutar video ini.
                        </video>
                    </template>
                @else
                    <img src="{{ $item->url }}" alt="{{ $imageAlt($item, $index) }}"
                         loading="lazy" decoding="async" draggable="false"
                         class="max-h-full max-w-full object-contain rounded-xl select-none">
                @endif
            </div>
        @endforeach
    </div>

    <p class="absolute top-4 left-4 rounded-full bg-white/10 px-3 py-1 text-sm text-white/80 tabular" aria-live="polite">
        <span x-text="(viewer ?? 0) + 1"></span> / {{ $count }}
    </p>

    <button type="button" x-ref="viewerClose" @click="closeViewer()" aria-label="Tutup"
            class="absolute top-3 right-3 w-11 h-11 rounded-full bg-white/10 hover:bg-[#A3191F] text-white flex items-center justify-center transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    @if($count > 1)
        <button type="button" @click="viewerStep(-1)" aria-label="Sebelumnya"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-[#A3191F] text-white items-center justify-center transition-colors hidden sm:flex">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" @click="viewerStep(1)" aria-label="Berikutnya"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-[#A3191F] text-white items-center justify-center transition-colors hidden sm:flex">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
        <p class="absolute bottom-5 inset-x-0 text-center text-xs text-white/50 sm:hidden">Geser untuk foto lainnya</p>
    @endif
</div>
@endif
