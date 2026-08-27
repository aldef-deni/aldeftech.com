@props(['variant' => 'desktop'])

@php
    $locales = config('locales.available', []);
    $current = app()->getLocale();
    $currentMeta = $locales[$current] ?? reset($locales);
@endphp

@if(count($locales) > 1)

@if($variant === 'desktop')
<div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
    <button type="button"
            @click="open = !open"
            @click.outside="open = false"
            class="lang-toggle"
            :aria-expanded="open.toString()"
            aria-haspopup="true"
            aria-label="{{ __('site.nav.language') }}">
        <svg class="w-4 h-4 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <circle cx="12" cy="12" r="9" stroke-width="1.5"/>
            <path stroke-width="1.5" d="M3.6 9h16.8M3.6 15h16.8"/>
            <path stroke-width="1.5" d="M12 3a15 15 0 010 18 15 15 0 010-18z"/>
        </svg>
        <span>{{ $currentMeta['short'] }}</span>
        <svg class="lang-caret" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 9l6 6 6-6"/>
        </svg>
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-[cubic-bezier(.22,1,.36,1)] duration-300"
         x-transition:enter-start="opacity-0 -translate-y-1.5 scale-[0.98]"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="lang-menu"
         role="menu">
        @foreach($locales as $code => $meta)
        <a href="{{ route('locale.switch', $code) }}"
           class="lang-option {{ $code === $current ? 'is-active' : '' }}"
           role="menuitem"
           hreflang="{{ $meta['html'] }}"
           aria-label="{{ __('site.nav.switch_to', ['language' => $meta['native']]) }}">
            <span class="lang-flag" aria-hidden="true">{{ $meta['flag'] }}</span>
            <span>{{ $meta['native'] }}</span>
            <svg class="lang-check" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </a>
        @endforeach
    </div>
</div>

@else
{{-- Mobile drawer: no dropdown, both options laid out side by side. --}}
<div>
    <p class="px-1.5 pb-2 text-[0.6875rem] font-semibold uppercase tracking-[0.16em] text-graphite-400">
        {{ __('site.nav.language') }}
    </p>
    <div class="grid grid-cols-2 gap-2">
        @foreach($locales as $code => $meta)
        <a href="{{ route('locale.switch', $code) }}"
           hreflang="{{ $meta['html'] }}"
           class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border text-sm font-medium transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)]
                  {{ $code === $current
                        ? 'border-gold-300 bg-gold-100 text-graphite-900 font-semibold'
                        : 'border-line bg-white text-graphite-600' }}">
            <span aria-hidden="true">{{ $meta['flag'] }}</span>
            <span>{{ $meta['short'] }}</span>
        </a>
        @endforeach
    </div>
</div>
@endif

@endif
