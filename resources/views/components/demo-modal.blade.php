@props(['portfolio'])

@if($portfolio->hasDemo())
@php
    $hasCredentials = filled($portfolio->demo_username) || filled($portfolio->demo_password);
@endphp

{{-- Behaviour lives in Alpine.data('demoModal') in resources/js/app.js. --}}
<div x-data="demoModal()"
     @keydown.escape.window="open = false">

    {{-- Trigger. Deliberately a button the visitor chooses to press: a modal
         that opens by itself interrupts reading, and Google treats intrusive
         interstitials on mobile as a ranking problem. --}}
    <button type="button" @click="open = true" class="btn btn-primary magnetic btn-pulse">
        <span class="relative flex h-2 w-2 mr-2.5" aria-hidden="true">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-ink-900/40"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-ink-900"></span>
        </span>
        <span>{{ __('pages.portfolio.detail.demo_cta') }}</span>
        <svg class="btn-arrow w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
    </button>

    {{-- Overlay, teleported to <body>.
         Any ancestor with a transform becomes the containing block for
         position:fixed, and this page is full of them (.reveal, [data-tilt]).
         Teleporting is what guarantees the backdrop covers the viewport. --}}
    <template x-teleport="body">
    <div x-show="open" x-cloak
         class="fixed inset-0 z-[90] flex items-end sm:items-center justify-center p-0 sm:p-6"
         role="dialog" aria-modal="true" aria-labelledby="demo-title-{{ $portfolio->id }}">

        <div x-show="open" x-transition.opacity.duration.400ms
             @click="open = false"
             class="absolute inset-0 bg-ink-950/70 backdrop-blur-sm" aria-hidden="true"></div>

        <div x-show="open"
             x-transition:enter="transition ease-[cubic-bezier(.22,1,.36,1)] duration-500"
             x-transition:enter-start="opacity-0 translate-y-8 sm:scale-[0.97] sm:translate-y-0"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-6 sm:scale-[0.97] sm:translate-y-0"
             class="relative w-full sm:max-w-lg max-h-[90vh] overflow-y-auto
                    rounded-t-3xl sm:rounded-3xl border border-line bg-ivory-50
                    shadow-[0_50px_100px_-30px_rgba(13,20,32,0.55)]">

            {{-- Header --}}
            <div class="surface-obsidian px-6 py-6 sm:px-7 relative overflow-hidden">
                <button type="button" @click="open = false"
                        class="absolute top-5 right-5 text-graphite-400 hover:text-white transition-colors duration-300 p-1.5 -m-1.5 rounded-lg"
                        aria-label="{{ __('pages.portfolio.detail.demo_close') }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <p class="eyebrow !text-gold-300">{{ $portfolio->title }}</p>
                <h2 id="demo-title-{{ $portfolio->id }}"
                    class="mt-3 font-display text-xl sm:text-2xl font-semibold text-white leading-snug pr-8">
                    {{ __('pages.portfolio.detail.demo_title') }}
                </h2>
                <p class="mt-3 text-sm text-graphite-300 leading-relaxed">
                    {{ __('pages.portfolio.detail.demo_lead') }}
                </p>
            </div>

            {{-- Body --}}
            <div class="p-6 sm:p-7 space-y-5">

                @if($hasCredentials)
                <div class="rounded-2xl border border-line bg-white p-5">
                    <p class="eyebrow mb-4">{{ __('pages.portfolio.detail.demo_credentials') }}</p>

                    <div class="space-y-3">
                        @foreach([
                            'demo_username' => $portfolio->demo_username,
                            'demo_password' => $portfolio->demo_password,
                        ] as $key => $value)
                            @if(filled($value))
                            <div class="flex items-center gap-3" x-data="{ copied: false }">
                                <span class="w-20 shrink-0 text-xs text-graphite-500">
                                    {{ __('pages.portfolio.detail.' . $key) }}
                                </span>
                                <code class="flex-1 min-w-0 truncate font-mono text-sm text-graphite-900 bg-ivory-200 rounded-lg px-3 py-2">{{ $value }}</code>
                                <button type="button"
                                        @click="navigator.clipboard.writeText(@js($value)); copied = true; setTimeout(() => copied = false, 1600)"
                                        class="shrink-0 text-xs font-semibold text-gold-700 hover:text-gold-600 transition-colors duration-300 px-2 py-1">
                                    <span x-show="!copied">{{ __('pages.portfolio.detail.demo_copy') }}</span>
                                    <span x-show="copied" x-cloak>{{ __('pages.portfolio.detail.demo_copied') }}</span>
                                </button>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @else
                <p class="text-sm text-graphite-600 leading-relaxed">
                    {{ __('pages.portfolio.detail.demo_open_note') }}
                </p>
                @endif

                @if(filled($portfolio->demo_note))
                <div class="flex gap-3 rounded-2xl bg-gold-100 border border-gold-200 p-4">
                    <svg class="w-4 h-4 text-gold-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-graphite-700 leading-relaxed">{{ $portfolio->demo_note }}</p>
                </div>
                @endif

                <a href="{{ $portfolio->demo_url }}" target="_blank" rel="noopener noreferrer"
                   class="btn btn-primary w-full justify-center">
                    <span>{{ __('pages.portfolio.detail.demo_open') }}</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                <p class="text-[0.6875rem] text-graphite-400 text-center leading-relaxed">
                    {{ __('pages.portfolio.detail.demo_disclaimer') }}
                </p>
            </div>
        </div>
    </div>
    </template>
</div>
@endif
