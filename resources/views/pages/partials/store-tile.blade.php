{{-- A brand to open. $tile: name, url, count, logo, image, caption. --}}
<a href="{{ $tile['url'] }}" class="store-card store-tile group">
    <div class="store-photo" @if($tile['image']) style="--photo: url('{{ $tile['image'] }}')" @endif>
        @if($tile['image'])
            <img src="{{ $tile['image'] }}" alt="" loading="lazy" decoding="async" width="600" height="450">
        @else
            <span class="absolute inset-0 flex items-center justify-center text-[#FF5A64]/50" aria-hidden="true">
                <x-lux-icon name="layers" class="w-10 h-10" />
            </span>
        @endif
        @if($tile['logo'])
            <span class="store-tile-logo"><img src="{{ $tile['logo'] }}" alt="" loading="lazy" decoding="async" width="56" height="56"></span>
        @endif
    </div>
    <div class="store-card-body"><div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <h3 class="store-card-title text-base sm:text-lg leading-snug font-semibold">{{ $tile['name'] }}</h3>
            <p class="store-card-meta mt-1 text-xs">{{ $tile['count'] }} model</p>
            @if($tile['caption'])
                <p class="store-card-meta mt-2 text-sm line-clamp-2">{{ $tile['caption'] }}</p>
            @endif
        </div>
        <span class="store-card-link mt-1 shrink-0" aria-hidden="true">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </span>
    </div></div>
</a>
