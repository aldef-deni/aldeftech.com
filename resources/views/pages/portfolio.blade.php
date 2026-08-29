@extends('layouts.app')

@php
    $pageTitle = __('pages.portfolio.meta_title');
    $metaDescription = __('pages.portfolio.meta_description');

    // Fallback entries carry a plain object category; database rows carry a relation.
    $filters = collect($portfolios)
        ->map(fn ($p) => $p->category->name ?? null)
        ->filter()
        ->unique()
        ->values();
@endphp

@section('content')

<x-page-hero
    :eyebrow="__('pages.portfolio.eyebrow')"
    :title="__('pages.portfolio.title')"
    :accent="__('pages.portfolio.accent')"
    :lead="__('pages.portfolio.lead')"
    :breadcrumbs="[['label' => __('site.nav.portfolio')]]" />

{{-- ── Grid ─────────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10"
         x-data="{ filter: 'all' }">

        @if($filters->count() > 1)
        <div class="flex flex-wrap items-center gap-2 pb-10 reveal" role="group" aria-label="{{ __('pages.portfolio.filter_label') }}">
            <button type="button" @click="filter = 'all'"
                    class="chip transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)]"
                    :class="filter === 'all' ? 'bg-graphite-900 border-graphite-900 text-ivory-100' : 'bg-ivory-100 border-line text-graphite-600 hover:border-gold-300'">
                {{ __('site.common.all') }}
            </button>
            @foreach($filters as $name)
            <button type="button" @click="filter = @js($name)"
                    class="chip transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)]"
                    :class="filter === @js($name) ? 'bg-graphite-900 border-graphite-900 text-ivory-100' : 'bg-ivory-100 border-line text-graphite-600 hover:border-gold-300'">
                {{ $name }}
            </button>
            @endforeach
        </div>
        @endif

        <div class="cards-swipe md:grid md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="80">
            @forelse($portfolios as $item)
            @php $catName = $item->category->name ?? null; @endphp

            <article class="reveal"
                     @if($catName) x-show="filter === 'all' || filter === @js($catName)" x-transition.opacity.duration.400ms @endif>
                <a href="{{ lroute('portfolio.show', $item->slug) }}" class="card-lux group h-full overflow-hidden">

                    <div class="frame-lux !rounded-none !border-0 !border-b !border-line aspect-[16/10] bg-ivory-200">
                        @if($src = media_url($item->featured_image))
                            <img src="{{ $src }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                        @else
                            <span class="absolute inset-0 flex items-center justify-center text-gold-400/50">
                                <x-lux-icon name="layers" class="w-12 h-12" />
                            </span>
                        @endif
                    </div>

                    <div class="p-6 lg:p-7 flex-1 flex flex-col">
                        <div class="flex items-center gap-2.5 text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700">
                            <span>{{ $catName ?? __('site.common.project') }}</span>
                            @if(!empty($item->year))
                                <span class="w-1 h-1 rounded-full bg-gold-400" aria-hidden="true"></span>
                                <span class="tabular">{{ $item->year }}</span>
                            @endif
                        </div>

                        <h2 class="mt-3.5 text-lg leading-snug">{{ $item->title }}</h2>

                        @if(!empty($item->client))
                        <p class="mt-1.5 text-xs text-graphite-500">{{ $item->client }}</p>
                        @endif

                        <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                            {{ excerpt_text($item->short_description, 150) }}
                        </p>

                        @if(!empty($item->technologies))
                        <div class="mt-5 flex flex-wrap gap-1.5">
                            @foreach(array_slice((array) $item->technologies, 0, 4) as $tech)
                                <span class="px-2.5 py-1 rounded-md text-[0.6875rem] font-medium bg-ivory-100 border border-line-soft text-graphite-600">{{ $tech }}</span>
                            @endforeach
                        </div>
                        @endif

                        <span class="mt-auto pt-6 link-arrow">
                            <span>{{ __('site.common.case_study') }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </div>
                </a>
            </article>
            @empty
            <p class="col-span-full text-center text-graphite-500 py-12">{{ __('pages.portfolio.empty') }}</p>
            @endforelse
        </div>
    </div>
</section>

<x-cta-band
    :eyebrow="__('pages.portfolio.closing.eyebrow')"
    :title="__('pages.portfolio.closing.title')"
    :accent="__('pages.portfolio.closing.accent')"
    :lead="__('pages.portfolio.closing.lead')" />

@endsection
