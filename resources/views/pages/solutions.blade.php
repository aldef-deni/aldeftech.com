@extends('layouts.app')

@php
    $pageTitle = __('pages.solutions.meta_title');
    $metaDescription = __('pages.solutions.meta_description');
@endphp

@section('content')

<x-page-hero
    :eyebrow="__('pages.solutions.eyebrow')"
    :title="__('pages.solutions.title')"
    :accent="__('pages.solutions.accent')"
    :lead="__('pages.solutions.lead')">
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn btn-primary w-full sm:w-auto">
            <span>{{ __('pages.solutions.cta_find') }}</span>
            <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        <a href="{{ route('services') }}" class="btn btn-ghost w-full sm:w-auto"><span>{{ __('pages.solutions.cta_services') }}</span></a>
    </div>
</x-page-hero>

{{-- ── Solutions grid ───────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="cards-swipe md:grid md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="70">
            @foreach($solutions as $i => $solution)
            <article id="{{ $solution->slug ?? \Illuminate\Support\Str::slug($solution->title) }}"
                     class="card-lux reveal group scroll-mt-28 p-7 lg:p-8">

                <div class="flex items-start justify-between gap-4">
                    <span class="icon-plate">
                        <x-lux-icon :name="$solution->icon" />
                    </span>
                    <span class="font-serif-accent italic text-2xl text-line leading-none pt-1 transition-colors duration-700 group-hover:text-gold-300">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <h2 class="mt-6 text-lg lg:text-xl leading-snug">{{ $solution->title }}</h2>

                <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                    {{ $solution->short_description }}
                </p>

                @if(!empty($solution->features))
                <hr class="rule-fade my-6">
                <ul class="space-y-2.5">
                    @foreach(array_slice((array) $solution->features, 0, 5) as $feature)
                    <li class="feature-row">
                        <span class="tick">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="mt-auto pt-7">
                    <a href="{{ \App\Services\WhatsAppService::getProjectUrl($solution->title) }}"
                       target="_blank" rel="noopener" class="link-arrow">
                        <span>{{ __('pages.solutions.ask_this') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Adaptation note ──────────────────────────────────────────────────── --}}
<section class="section-padding-sm surface-obsidian relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-gold w-[32rem] h-[32rem] -bottom-56 -left-32 opacity-40" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <div class="lg:col-span-5 reveal-left">
                <p class="eyebrow eyebrow-light">{{ __('pages.solutions.adapt.eyebrow') }}</p>
                <h2 class="mt-5 text-3xl sm:text-4xl text-white">
                    {{ __('pages.solutions.adapt.title') }} <span class="accent-serif accent-champagne">{{ __('pages.solutions.adapt.accent') }}</span>
                </h2>
                <p class="mt-5 text-base leading-relaxed text-graphite-300">
                    {{ __('pages.solutions.adapt.lead') }}
                </p>
            </div>

            <div class="lg:col-span-7 cards-swipe cards-swipe-tight md:grid md:grid-cols-3 gap-4" data-reveal-group="90">
                @foreach((array) __('pages.solutions.adapt.steps') as $s)
                <div class="card-obsidian reveal group p-6">
                    <span class="font-serif-accent italic text-3xl text-gold-600 leading-none transition-colors duration-700 group-hover:text-gold-300">{{ $s['n'] }}</span>
                    <h3 class="mt-4 text-base text-white">{{ $s['title'] }}</h3>
                    <p class="mt-2.5 text-sm leading-relaxed text-graphite-400">{{ $s['body'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<x-cta-band
    :eyebrow="__('pages.solutions.closing.eyebrow')"
    :title="__('pages.solutions.closing.title')"
    :accent="__('pages.solutions.closing.accent')"
    :lead="__('pages.solutions.closing.lead')" />

@endsection
