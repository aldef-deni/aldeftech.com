{{-- A brand or category to open. $tile: name, url, count, logo, image, caption. --}}
<a href="{{ $tile['url'] }}" class="store-tile group">
    <div class="store-tile-photo">
        @if($tile['image'])
            <img src="{{ $tile['image'] }}" alt="" loading="lazy" decoding="async" width="600" height="450">
        @else
            <span class="absolute inset-0 flex items-center justify-center text-gold-400/60" aria-hidden="true">
                <x-lux-icon name="layers" class="w-10 h-10" />
            </span>
        @endif
        @if($tile['logo'])
            <span class="store-tile-logo"><img src="{{ $tile['logo'] }}" alt="" loading="lazy" decoding="async" width="56" height="56"></span>
        @endif
    </div>
    <div class="pt-4 flex items-start justify-between gap-3">
        <div class="min-w-0">
            <h3 class="text-base sm:text-lg leading-snug font-semibold text-graphite-900 group-hover:text-gold-700 transition-colors">{{ $tile['name'] }}</h3>
            <p class="mt-1 text-xs text-graphite-500">{{ $tile['count'] }} model</p>
            @if($tile['caption'])
                <p class="mt-2 text-sm text-graphite-600 line-clamp-2">{{ $tile['caption'] }}</p>
            @endif
        </div>
        <svg class="mt-1 w-4 h-4 shrink-0 text-gold-700 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </div>
</a>
