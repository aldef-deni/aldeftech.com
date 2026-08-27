@props([
    'eyebrow' => null,
    'title' => null,
    'accent' => null,
    'lead' => null,
    'primaryLabel' => null,
    'secondaryLabel' => null,
])

@php
    // Defaults resolve at render time so they follow the active locale.
    $eyebrow ??= __('pages.cta_band.eyebrow');
    $title   ??= __('pages.cta_band.title');
    $accent  ??= __('pages.cta_band.accent');
    $lead    ??= __('pages.cta_band.lead');
    $primaryLabel   ??= __('site.cta.via_whatsapp');
    $secondaryLabel ??= __('site.cta.send_brief');
@endphp

<section class="surface-spectrum-deep relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>

    {{-- The closing band lights from below, so the eye lands on the buttons. --}}
    <div class="bloom bloom-ember w-[26rem] h-[26rem] -bottom-60 left-[2%] opacity-28" aria-hidden="true"></div>
    <div class="bloom bloom-magenta w-[28rem] h-[28rem] -bottom-68 left-[32%] opacity-28" aria-hidden="true"></div>
    <div class="bloom bloom-violet w-[32rem] h-[32rem] -bottom-72 right-[22%] opacity-36" aria-hidden="true"></div>
    <div class="bloom bloom-azure w-[26rem] h-[26rem] -bottom-56 -right-32 opacity-25" aria-hidden="true"></div>

    <div class="shell relative z-10 py-20 lg:py-28">
        <div class="max-w-3xl mx-auto text-center">
            <p class="eyebrow eyebrow-center eyebrow-spectrum reveal">{{ $eyebrow }}</p>

            <h2 class="mt-7 text-3xl sm:text-4xl lg:text-[3rem] leading-[1.12] text-white reveal reveal-d1">
                {{ $title }} <span class="accent-serif accent-spectrum">{{ $accent }}</span>
            </h2>

            @if($lead)
            <p class="mt-6 text-base lg:text-lg leading-relaxed text-graphite-300 reveal reveal-d2">{{ $lead }}</p>
            @endif

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3.5 reveal reveal-d3">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                   class="btn btn-primary btn-lg w-full sm:w-auto magnetic" data-magnetic="0.1">
                    <span>{{ $primaryLabel }}</span>
                    <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="btn btn-ghost btn-lg w-full sm:w-auto">
                    <span>{{ $secondaryLabel }}</span>
                </a>
            </div>
        </div>
    </div>
</section>
