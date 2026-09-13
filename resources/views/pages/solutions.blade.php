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
        <a href="{{ lroute('services') }}" class="btn btn-ghost w-full sm:w-auto"><span>{{ __('pages.solutions.cta_services') }}</span></a>
    </div>
</x-page-hero>

{{-- ── Solutions grid ───────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="cards-swipe md:grid md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="70">
            @foreach($solutions as $i => $solution)
            <article id="{{ $solution->slug ?? \Illuminate\Support\Str::slug($solution->title) }}"
                     class="card-lux solution-premium-card reveal group scroll-mt-28 p-7 lg:p-8">

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

@push('styles')
<style>
    .solution-premium-card {
        color: #f4eff0;
        border-color: rgb(196 151 69 / 20%);
        background:
            radial-gradient(90% 75% at 100% 100%, rgb(139 17 34 / 24%) 0%, transparent 68%),
            linear-gradient(137deg, #080a0d 0%, #101014 48%, #241014 100%);
        box-shadow:
            inset 0 1px 0 rgb(255 255 255 / 4%),
            0 20px 46px -32px rgb(22 4 8 / 75%);
        transition:
            transform 620ms var(--e-soft),
            border-color 420ms var(--e-glide),
            box-shadow 620ms var(--e-soft),
            background 420ms var(--e-glide);
    }
    .solution-premium-card:nth-child(3n + 2) {
        background:
            radial-gradient(80% 90% at 0% 0%, rgb(160 22 40 / 15%) 0%, transparent 62%),
            linear-gradient(142deg, #111014 0%, #080a0d 52%, #2d0e15 100%);
    }
    .solution-premium-card:nth-child(3n + 3) {
        background:
            radial-gradient(75% 85% at 86% 12%, rgb(177 25 44 / 16%) 0%, transparent 60%),
            linear-gradient(132deg, #07090c 0%, #111014 58%, #251015 100%);
    }
    .solution-premium-card::before {
        opacity: 1;
        background: linear-gradient(118deg, transparent 22%, rgb(255 255 255 / 2.5%) 50%, transparent 76%);
    }
    .solution-premium-card::after {
        right: 14%;
        left: 14%;
        opacity: .72;
        transform: none;
        background: linear-gradient(90deg, transparent, rgb(215 173 94 / 72%), rgb(206 61 78 / 44%), transparent);
    }
    .solution-premium-card h2 { color: #faf6f6; }
    .solution-premium-card p,
    .solution-premium-card .feature-row { color: #bbb7bc; }
    .solution-premium-card .rule-fade {
        background: linear-gradient(90deg, transparent, rgb(217 184 124 / 24%) 18%, rgb(190 51 68 / 23%) 82%, transparent);
    }
    .solution-premium-card .icon-plate {
        color: #d9b87c;
        border-color: rgb(217 184 124 / 24%);
        background: linear-gradient(145deg, rgb(217 184 124 / 14%), rgb(116 17 31 / 19%));
    }
    .solution-premium-card .font-serif-accent { color: #d9b87c; }
    .solution-premium-card .feature-row .tick {
        color: #e8d3a7;
        background: rgb(217 184 124 / 13%);
    }
    .solution-premium-card .link-arrow { color: #d9b87c; }

    @media (hover: hover) {
        .solution-premium-card:hover {
            transform: translateY(-4px);
            border-color: rgb(217 184 124 / 36%);
            background:
                radial-gradient(95% 85% at 100% 100%, rgb(155 20 39 / 29%) 0%, transparent 68%),
                linear-gradient(137deg, #0a0c10 0%, #131116 48%, #2b1117 100%);
            box-shadow:
                inset 0 1px 0 rgb(255 255 255 / 6%),
                0 26px 54px -32px rgb(89 5 18 / 56%),
                0 0 28px rgb(142 17 32 / 9%);
        }
        .solution-premium-card:hover::before { opacity: 1; }
        .solution-premium-card:hover::after { opacity: 1; transform: none; }
        .solution-premium-card:hover .icon-plate {
            color: #f5e8cc;
            border-color: rgb(232 211 167 / 42%);
            background: linear-gradient(145deg, rgb(217 184 124 / 22%), rgb(119 15 31 / 28%));
            box-shadow: 0 12px 28px -16px rgb(217 184 124 / 52%);
        }
        .solution-premium-card:hover .font-serif-accent,
        .solution-premium-card:hover .link-arrow { color: #f0d69f; }
        .solution-premium-card:hover .feature-row .tick {
            color: #101014;
            background: #d9b87c;
        }
    }

    @media (max-width: 640px) {
        .solution-premium-card { border-radius: 1rem; }
    }
</style>
@endpush
