@extends('layouts.app')

@php
    $pageTitle = __('pages.services.meta_title');
    $metaDescription = __('pages.services.meta_description');
@endphp

@section('content')

<x-page-hero
    :eyebrow="__('pages.services.eyebrow')"
    :title="__('pages.services.title')"
    :accent="__('pages.services.accent')"
    :lead="__('pages.services.lead')">
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn btn-primary w-full sm:w-auto">
            <span>{{ __('pages.services.cta_discuss') }}</span>
            <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        <a href="{{ lroute('portfolio') }}" class="btn btn-ghost w-full sm:w-auto"><span>{{ __('pages.services.cta_work') }}</span></a>
    </div>
</x-page-hero>

{{-- ── Service list ─────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10 space-y-5 lg:space-y-6">
        @foreach($services as $i => $service)
        <article id="{{ $service->slug ?? \Illuminate\Support\Str::slug($service->title) }}"
                 class="card-lux reveal group scroll-mt-28 p-7 sm:p-8 lg:p-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 lg:gap-12 items-start">

                {{-- Heading side --}}
                <div class="lg:col-span-5">
                    <div class="flex items-start gap-5">
                        <span class="icon-plate">
                            <x-lux-icon :name="$service->icon" />
                        </span>
                        <span class="font-serif-accent italic text-3xl text-line leading-none pt-1.5 transition-colors duration-700 group-hover:text-gold-300">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <h2 class="mt-6 text-2xl lg:text-[1.75rem] leading-tight">{{ $service->title }}</h2>

                    <p class="mt-4 text-[0.9375rem] leading-relaxed text-graphite-600">
                        {{ $service->short_description }}
                    </p>

                </div>

                {{-- Detail side --}}
                <div class="lg:col-span-7 lg:border-l lg:border-line-soft lg:pl-12">
                    @if(!empty($service->description))
                    <p class="text-[0.9375rem] leading-[1.8] text-graphite-700">
                        {{ $service->description }}
                    </p>
                    @endif

                    @if(!empty($service->features))
                    <p class="eyebrow {{ !empty($service->description) ? 'mt-7' : '' }} mb-5">{{ __('pages.services.benefits') }}</p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5">
                        @foreach((array) $service->features as $feature)
                        <li class="feature-row">
                            <span class="tick">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-line-soft">
                <a href="{{ \App\Services\WhatsAppService::getProjectUrl($service->title) }}"
                   target="_blank" rel="noopener" class="link-arrow">
                    <span>{{ __('pages.services.consult_this') }}</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </article>
        @endforeach
    </div>
</section>

{{-- ── Engagement models ────────────────────────────────────────────────── --}}
<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">{{ __('pages.services.models.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl">
                {{ __('pages.services.models.title') }} <span class="accent-serif accent-gold">{{ __('pages.services.models.accent') }}</span>{{ __('pages.services.models.title_after') }}
            </h2>
        </header>

        <div class="mt-12 lg:mt-16 cards-swipe md:grid md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="90">
            @foreach([
                ['icon' => 'target',  'key' => 'fixed'],
                ['icon' => 'users',   'key' => 'dedicated', 'featured' => true],
                ['icon' => 'compass', 'key' => 'advisory'],
            ] as $model)
            <article class="card-lux reveal group p-7 lg:p-8 {{ !empty($model['featured']) ? 'card-lux-featured' : '' }}">
                @if(!empty($model['featured']))
                    <span class="chip self-start mb-5">{{ __('pages.services.models.popular') }}</span>
                @else
                    <span class="chip chip-neutral self-start mb-5">{{ __('pages.services.models.' . $model['key'] . '.best') }}</span>
                @endif

                <span class="icon-plate">
                    <x-lux-icon :name="$model['icon']" />
                </span>

                <h3 class="mt-5 text-lg lg:text-xl">{{ __('pages.services.models.' . $model['key'] . '.name') }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-graphite-600">{{ __('pages.services.models.' . $model['key'] . '.body') }}</p>

                <ul class="mt-6 space-y-2.5">
                    @foreach((array) __('pages.services.models.' . $model['key'] . '.points') as $point)
                    <li class="feature-row">
                        <span class="tick">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>

        <p class="mt-10 text-center text-sm text-graphite-500 reveal">
            {{ __('pages.services.models.note') }}
        </p>
    </div>
</section>

<x-cta-band
    :eyebrow="__('pages.services.closing.eyebrow')"
    :title="__('pages.services.closing.title')"
    :accent="__('pages.services.closing.accent')"
    :lead="__('pages.services.closing.lead')" />

@endsection
