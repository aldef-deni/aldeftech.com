@extends('layouts.app')

@php
    use App\Services\WhatsAppService;
    use App\Models\SiteSetting;

    // Site settings hold one language only; fall back to the translated copy so
    // the English visitor never lands on Indonesian meta text.
    $pageTitle = app()->isLocale('id')
        ? SiteSetting::get('seo_default_title', __('home.meta.title'))
        : __('home.meta.title');
    $metaDescription = app()->isLocale('id')
        ? SiteSetting::get('seo_default_description', __('home.meta.description'))
        : __('home.meta.description');

    $waUrl = WhatsAppService::getUrl();

    $stack = [
        'Laravel 13', 'PHP 8.3', 'Python', 'OpenAI & AI Agents', 'Vue.js 3', 'React',
        'Tailwind CSS', 'PostgreSQL', 'MySQL', 'Redis', 'Docker', 'REST & GraphQL',
        'AWS', 'Kubernetes', 'Nginx', 'CI/CD',
    ];

    $pillars = [
        ['icon' => 'blueprint', 'key' => 'architecture'],
        ['icon' => 'lock',      'key' => 'ownership'],
        ['icon' => 'chart',     'key' => 'outcome'],
        ['icon' => 'lifebuoy',  'key' => 'support'],
    ];
@endphp

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════
     HERO
     ══════════════════════════════════════════════════════════════════ --}}
<section class="home-video-hero spectrum-edge" aria-label="{{ __('home.video.title') }}">
    <h1 class="home-video-heading">Aldef Tech</h1>
    <div class="shell">
        <figure class="home-video-frame">
            <img class="home-video-poster" src="{{ asset('images/aldef-tech-banner.webp') }}"
                 alt="{{ __('home.hero.banner_alt') }}" width="1376" height="768" fetchpriority="high">
            @if($previewVideoUrl)
                <video id="home-banner-video" src="{{ $previewVideoUrl }}"
                       poster="{{ asset('images/aldef-tech-banner.webp') }}"
                       autoplay loop playsinline controls preload="auto"
                       aria-label="{{ __('home.video.title') }}"></video>
                <div id="home-video-actions" class="home-video-actions" hidden>
                    <button id="home-video-toggle" type="button" aria-label="{{ __('home.video.pause') }}"
                            data-play="{{ __('home.video.play') }}" data-pause="{{ __('home.video.pause') }}">
                        <svg data-icon="pause" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 4h4v16H6zm8 0h4v16h-4z" /></svg>
                        <svg data-icon="play" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" hidden><path d="M8 5v14l11-7z" /></svg>
                    </button>
                    <button id="home-video-sound" type="button" aria-label="{{ __('home.video.mute') }}"
                            data-unmute="{{ __('home.video.unmute') }}" data-mute="{{ __('home.video.mute') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path d="M11 5 6 9H3v6h3l5 4z" />
                            <path data-icon="sound" d="M15 8a6 6 0 0 1 0 8m3-11a10 10 0 0 1 0 14" />
                            <path data-icon="muted" d="m16 9 6 6m0-6-6 6" hidden />
                        </svg>
                    </button>
                </div>
            @endif
        </figure>

        {{-- Trust metrics --}}
        <div class="max-w-4xl mx-auto mt-14 lg:mt-16 grid grid-cols-2 sm:grid-cols-4 gap-y-9 gap-x-6 pb-16 lg:pb-20"
             data-reveal-group="90">
            @foreach([
                ['v' => '10', 'suffix' => '+',  'l' => __('home.stats.years')],
                ['v' => '40', 'suffix' => '+',  'l' => __('home.stats.systems')],
                ['v' => '99.9', 'suffix' => '%', 'l' => __('home.stats.uptime'), 'dec' => 1],
                ['v' => '100', 'suffix' => '%', 'l' => __('home.stats.ownership')],
            ] as $m)
            <div class="text-center reveal">
                <p class="stat-value stat-value-light tabular">
                    <span data-counter="{{ $m['v'] }}" data-counter-suffix="{{ $m['suffix'] }}" data-counter-decimals="{{ $m['dec'] ?? 0 }}">0</span>
                </p>
                <p class="stat-label">{{ $m['l'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════
     TECH STACK MARQUEE
     ══════════════════════════════════════════════════════════════════ --}}
<section class="surface-ivory-deep border-y border-line py-6 lg:py-7" aria-label="{{ __('home.stack_label') }}">
    <div class="marquee">
        <div class="marquee-track">
            @foreach(array_merge($stack, $stack) as $tech)
                <span class="marquee-item">{{ $tech }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════
     SERVICES
     ══════════════════════════════════════════════════════════════════ --}}
@if($services->isNotEmpty())
<section id="layanan" class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="max-w-2xl reveal">
            <p class="eyebrow">{{ __('home.services.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                {{ __('home.services.title') }}
                <span class="accent-serif accent-gold">{{ __('home.services.accent') }}</span> {{ __('home.services.title_after') }}
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-600">
                {{ __('home.services.lead') }}
            </p>
        </header>

        <div class="mt-12 lg:mt-16 cards-swipe md:grid md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6"
             data-reveal-group="70">
            @foreach($services->take(3) as $i => $service)
            <article class="card-lux home-dark-red-card reveal group p-7 lg:p-8">
                <div class="flex items-start justify-between gap-4">
                    <span class="icon-plate">
                        <x-lux-icon :name="$service->icon" />
                    </span>
                    <span class="font-serif-accent italic text-2xl text-line leading-none pt-1 transition-colors duration-500 group-hover:text-gold-300">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <h3 class="mt-6 text-lg lg:text-xl">{{ $service->title }}</h3>

                <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                    {{ excerpt_text($service->short_description, 155) }}
                </p>

                @if(!empty($service->features))
                <ul class="mt-6 space-y-2.5">
                    @foreach(array_slice((array) $service->features, 0, 4) as $feature)
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
                    <a href="{{ isset(config('service_landings.pages')[$service->slug]) ? lroute('services.show', $service->slug) : lroute('services') }}"
                       class="link-arrow" data-analytics-event="cta_click"
                       data-analytics-cta-location="service_card" data-analytics-service="{{ $service->title }}"
                       data-analytics-destination="service_detail">
                        <span>{{ __('site.common.read_more') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        <div class="mt-12 text-center reveal">
            <a href="{{ lroute('services') }}" class="btn btn-outline">
                <span>{{ __('home.services.all') }}</span>
                <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     DIFFERENTIATORS
     ══════════════════════════════════════════════════════════════════ --}}
<section class="home-pillars-dark section-padding relative overflow-hidden">
    <div class="home-pillars-glow home-pillars-glow-left" aria-hidden="true"></div>
    <div class="home-pillars-glow home-pillars-glow-right" aria-hidden="true"></div>
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">

            <div class="lg:col-span-5 lg:sticky lg:top-32 lg:self-start reveal-left">
                <p class="eyebrow home-pillars-eyebrow">{{ __('home.pillars.eyebrow') }}</p>
                <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem] text-white">
                    {{ __('home.pillars.title') }}
                    <span class="accent-serif home-pillars-accent">{{ __('home.pillars.accent') }}</span>{{ __('home.pillars.title_after') }}
                </h2>
                <p class="mt-6 text-base leading-relaxed text-graphite-300">
                    {{ __('home.pillars.lead') }}
                </p>

                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="{{ lroute('about') }}" class="btn btn-ghost home-pillars-cta">
                        <span>{{ __('home.pillars.cta') }}</span>
                        <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7 cards-swipe md:space-y-4" data-reveal-group="90">
                @foreach($pillars as $i => $pillar)
                <article class="card-obsidian home-pillar-card reveal group p-6 lg:p-8 !flex-row items-start gap-5">
                    <span class="icon-plate icon-plate-dark home-pillar-icon">
                        <x-lux-icon :name="$pillar['icon']" />
                    </span>
                    <div class="min-w-0">
                        <h3 class="text-base lg:text-lg text-white">{{ __('home.pillars.' . $pillar['key'] . '.title') }}</h3>
                        <p class="mt-2.5 text-sm leading-relaxed text-graphite-300">{{ __('home.pillars.' . $pillar['key'] . '.body') }}</p>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════
     PROCESS
     ══════════════════════════════════════════════════════════════════ --}}
@if($processSteps->isNotEmpty())
<section id="proses" class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">{{ __('home.process.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                {{ __('home.process.title') }} <span class="accent-serif accent-gold">{{ __('home.process.accent') }}</span>{{ __('home.process.title_after') }}
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-600">
                {{ __('home.process.lead') }}
            </p>
        </header>

        <ol class="mt-14 lg:mt-20 cards-swipe cards-swipe-tight md:grid md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10"
            data-reveal-group="70">
            @foreach($processSteps as $step)
            <li class="home-dark-red-card home-process-card reveal group relative p-6 lg:p-7">
                <span class="absolute top-0 left-0 right-0 h-px bg-line transition-colors duration-700 group-hover:bg-gold-400" aria-hidden="true"></span>

                <span class="step-numeral block">{{ str_pad($step->step_number ?? $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                <h3 class="mt-4 text-base lg:text-lg">{{ $step->title }}</h3>
                <p class="mt-2.5 text-sm leading-relaxed text-graphite-600">
                    {{ excerpt_text($step->description, 130) }}
                </p>
            </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     PORTFOLIO
     ══════════════════════════════════════════════════════════════════ --}}
@if($portfolios->isNotEmpty())
<section id="portofolio" class="section-padding surface-obsidian relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-aurora w-[36rem] h-[36rem] -top-32 -right-40 opacity-40" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 reveal">
            <div class="max-w-2xl">
                <p class="eyebrow eyebrow-light">{{ __('home.portfolio.eyebrow') }}</p>
                <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem] text-white">
                    {{ __('home.portfolio.title') }} <span class="accent-serif accent-champagne">{{ __('home.portfolio.accent') }}</span> {{ __('home.portfolio.title_after') }}
                </h2>
            </div>
            <a href="{{ lroute('portfolio') }}" class="link-arrow link-arrow-light shrink-0">
                <span>{{ __('home.portfolio.all') }}</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </header>

        <div class="mt-12 lg:mt-16 cards-swipe md:grid md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6"
             data-reveal-group="90">
            @foreach($portfolios as $item)
            <a href="{{ lroute('portfolio.show', $item->slug) }}" class="card-obsidian reveal group overflow-hidden">
                <div class="frame-lux !rounded-none !border-0 !border-b !border-white/10 aspect-[16/10] bg-ink-800">
                    @if($src = media_url($item->featured_image))
                        <img src="{{ $src }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-gold-500/30">
                            <x-lux-icon name="layers" class="w-12 h-12" />
                        </span>
                    @endif
                </div>

                <div class="p-6 lg:p-7 flex-1 flex flex-col">
                    <div class="flex items-center gap-2.5 text-[0.6875rem] uppercase tracking-[0.14em] text-gold-400">
                        <span>{{ $item->category->name ?? __('site.common.project') }}</span>
                        @if($item->year)
                            <span class="w-1 h-1 rounded-full bg-gold-600" aria-hidden="true"></span>
                            <span class="tabular">{{ $item->year }}</span>
                        @endif
                    </div>

                    <h3 class="mt-3.5 text-lg text-white">{{ $item->title }}</h3>

                    <p class="mt-2.5 text-sm leading-relaxed text-graphite-400">
                        {{ excerpt_text($item->short_description, 120) }}
                    </p>

                    @if(!empty($item->technologies))
                    <div class="mt-5 flex flex-wrap gap-1.5">
                        @foreach(array_slice((array) $item->technologies, 0, 4) as $tech)
                            <span class="px-2.5 py-1 rounded-md text-[0.6875rem] font-medium bg-white/5 border border-white/10 text-graphite-300">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif

                    <span class="mt-auto pt-6 link-arrow link-arrow-light">
                        <span>{{ __('site.common.case_study') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     KLIEN ALDEF TECH

     Sits between the work and the offer: the portfolio shows what was built,
     this band shows who it was built for, and the marquee keeps moving so the
     strip reads as a list rather than as a banner to be read once.
     ══════════════════════════════════════════════════════════════════ --}}
@if($clients->isNotEmpty())
<section id="klien" class="client-band section-padding-sm relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="client-band-glow" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="max-w-2xl reveal">
            <p class="eyebrow client-band-eyebrow">{{ __('home.clients.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem] text-white">
                {{ __('home.clients.title') }} <span class="accent-serif client-band-accent">{{ __('home.clients.accent') }}</span>{{ __('home.clients.title_after') }}
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-300">{{ __('home.clients.lead') }}</p>
        </header>
    </div>

    {{-- Duration scales with the number of logos, so more clients means the same
         slow speed rather than a faster blur. --}}
    <div class="client-marquee mt-12 lg:mt-14" style="--client-marquee-duration: {{ max(46, $clients->count() * 9) }}s">
        <div class="client-marquee-track">
            @foreach($clients as $client)
            <div class="client-marquee-item"><x-client-logo :client="$client" /></div>
            @endforeach

            {{-- Second pass, marked so it can be dropped for anyone who asked
                 for less motion. Decorative: the logos above are the content. --}}
            @foreach($clients as $client)
            <div class="client-marquee-item" data-client-duplicate>
                <x-client-logo :client="$client" :decorative="true" />
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     SOLUTIONS
     ══════════════════════════════════════════════════════════════════ --}}
@if($solutions->isNotEmpty())
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">{{ __('home.solutions.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                {{ __('home.solutions.title') }} <span class="accent-serif accent-gold">{{ __('home.solutions.accent') }}</span>{{ __('home.solutions.title_after') }}
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-600">
                {{ __('home.solutions.lead') }}
            </p>
        </header>

        <div class="mt-12 lg:mt-16 cards-swipe cards-swipe-tight md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4"
             data-reveal-group="50">
            @foreach($solutions as $solution)
            <a href="{{ lroute('solutions') }}" class="card-quiet home-dark-red-card reveal group p-6 flex flex-col">
                <span class="icon-plate icon-plate-sm">
                    <x-lux-icon :name="$solution->icon" />
                </span>
                <h3 class="mt-4 text-[0.9375rem] leading-snug">{{ $solution->title }}</h3>
                <p class="mt-2 text-[0.8125rem] leading-relaxed text-graphite-500">
                    {{ excerpt_text($solution->short_description, 78) }}
                </p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     LEADERSHIP
     ══════════════════════════════════════════════════════════════════ --}}
@if($ceoProfile)
<section class="home-pillars-dark section-padding relative overflow-hidden">
    <div class="home-pillars-glow home-pillars-glow-left" aria-hidden="true"></div>
    <div class="home-pillars-glow home-pillars-glow-right" aria-hidden="true"></div>
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">

            <div class="lg:col-span-5 reveal-left">
                <figure class="frame-lux home-leadership-frame aspect-[4/5] max-w-sm mx-auto lg:max-w-none">
                    @if($src = media_url($ceoProfile->profile_photo, 'images/deni-afrizal.jpg'))
                        <img src="{{ $src }}" alt="{{ $ceoProfile->name }}" loading="lazy" decoding="async">
                    @endif
                </figure>
            </div>

            <div class="lg:col-span-7 reveal-right">
                <p class="eyebrow home-pillars-eyebrow">{{ __('home.leadership.eyebrow') }}</p>

                <blockquote class="mt-6 font-serif-accent italic text-2xl sm:text-3xl lg:text-[2.125rem] leading-[1.35] text-white">
                    “{{ excerpt_text($ceoProfile->short_bio, 210) ?: __('home.leadership.fallback_quote') }}”
                </blockquote>

                <div class="mt-8 flex items-center gap-4">
                    <span class="w-10 h-px bg-gold-500" aria-hidden="true"></span>
                    <div>
                        <p class="font-display text-base font-semibold text-white">{{ $ceoProfile->name }}</p>
                        <p class="text-sm text-graphite-300 mt-0.5">{{ $ceoProfile->position }}</p>
                    </div>
                </div>

                @if(!empty($ceoProfile->skills))
                <div class="mt-8 chip-strip flex gap-2 sm:flex-wrap">
                    @foreach(array_slice((array) $ceoProfile->skills, 0, 8) as $skill)
                        <span class="chip chip-dark">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif

                <div class="mt-9">
                    <a href="{{ lroute('about') }}" class="link-arrow link-arrow-light">
                        <span>{{ __('home.leadership.full_profile') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        @if($commissionerProfile)
        @php $commissionerPhoto = media_url($commissionerProfile->profile_photo); @endphp
        {{-- Commissioner profile: same section, mirrored. Mobile stays photo
             first; from lg up the row flips so the bio sits left and the
             portrait right. The .home-leadership-mirror rules below enforce
             that order in CSS, so it cannot be undone by a stale build or by a
             parent's ordering. Any field the editor left blank is simply not
             rendered. --}}
        <div class="mt-16 lg:mt-24 pt-14 lg:pt-20 border-t border-white/10">
            <div class="home-leadership-mirror grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">

                @if($commissionerPhoto)
                <div class="home-leadership-media order-1 lg:order-2 lg:col-span-5 reveal-right">
                    <figure class="frame-lux home-leadership-frame aspect-[4/5] max-w-sm mx-auto lg:max-w-none">
                        <img src="{{ $commissionerPhoto }}" alt="{{ $commissionerProfile->name }}" loading="lazy" decoding="async">
                    </figure>
                </div>
                @endif

                <div class="home-leadership-body order-2 lg:order-1 {{ $commissionerPhoto ? 'lg:col-span-7' : 'lg:col-span-12' }} reveal-left">
                    @if($commissionerQuote = excerpt_text($commissionerProfile->short_bio, 210))
                    <blockquote class="font-serif-accent italic text-2xl sm:text-3xl lg:text-[2.125rem] leading-[1.35] text-white">
                        “{{ $commissionerQuote }}”
                    </blockquote>
                    @endif

                    <div class="{{ $commissionerQuote ? 'mt-8' : '' }} flex items-center gap-4">
                        <span class="w-10 h-px bg-gold-500" aria-hidden="true"></span>
                        <div>
                            <p class="font-display text-base font-semibold text-white">{{ $commissionerProfile->name }}</p>
                            <p class="text-sm text-graphite-300 mt-0.5">{{ $commissionerProfile->position }}</p>
                        </div>
                    </div>

                    @if(!empty($commissionerProfile->skills))
                    <div class="mt-8 chip-strip flex gap-2 sm:flex-wrap">
                        @foreach(array_slice((array) $commissionerProfile->skills, 0, 8) as $skill)
                            <span class="chip chip-dark">{{ $skill }}</span>
                        @endforeach
                    </div>
                    @endif

                    @php
                        $commissionerLinks = array_filter([
                            'LinkedIn'  => $commissionerProfile->linkedin,
                            'GitHub'    => $commissionerProfile->github,
                            'Instagram' => $commissionerProfile->instagram,
                            'Email'     => $commissionerProfile->email ? 'mailto:' . $commissionerProfile->email : null,
                        ]);
                    @endphp
                    @if($commissionerLinks)
                    <div class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-3">
                        @foreach($commissionerLinks as $label => $href)
                        <a href="{{ $href }}"@if(! str_starts_with($href, 'mailto:')) target="_blank" rel="noopener"@endif class="link-arrow link-arrow-light">
                            <span>{{ $label }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     TESTIMONIALS
     ══════════════════════════════════════════════════════════════════ --}}
@if($testimonials->isNotEmpty())
@php $testimonialSlider = $testimonials->count() > 3; @endphp
<section id="testimoni" class="home-testimonials section-padding relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="home-testimonials-glow home-testimonials-glow-left" aria-hidden="true"></div>
    <div class="home-testimonials-glow home-testimonials-glow-right" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="max-w-2xl reveal">
            <p class="eyebrow home-testimonials-eyebrow">{{ __('home.testimonials.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem] text-white">
                {{ __('home.testimonials.title') }} <span class="accent-serif home-testimonials-accent">{{ __('home.testimonials.accent') }}</span>{{ __('home.testimonials.title_after') }}
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-300">{{ __('home.testimonials.lead') }}</p>
        </header>

        <div class="mt-12 lg:mt-16"
             @if($testimonialSlider) data-testimonial-slider @endif>

            @if($testimonialSlider)
            <div class="flex justify-end gap-2 mb-6">
                <button type="button" class="testimonial-nav" data-testimonial-prev
                        aria-label="{{ __('home.testimonials.prev') }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="testimonial-nav" data-testimonial-next
                        aria-label="{{ __('home.testimonials.next') }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            @endif

            <ul class="testimonial-rail{{ $testimonialSlider ? ' is-slider' : '' }}" data-testimonial-rail
                role="list" aria-label="{{ __('home.testimonials.slides') }}"
                @if($testimonialSlider) tabindex="0" @endif>
                @foreach($testimonials as $t)
                <li class="testimonial-slide">
                    <figure class="card-obsidian testimonial-card h-full{{ $t->is_featured ? ' is-featured' : '' }}">
                        <span class="testimonial-mark" aria-hidden="true">&ldquo;</span>

                        <div class="flex items-center justify-between gap-3">
                            @if($t->rating)
                            <div class="flex gap-1 text-gold-500" aria-label="{{ __('home.testimonials.rating', ['rating' => $t->rating]) }}">
                                @for($s = 1; $s <= 5; $s++)
                                    <svg class="w-3.5 h-3.5 {{ $s <= $t->rating ? 'opacity-100' : 'opacity-25' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L1.5 7.7l5.9-.9z"/></svg>
                                @endfor
                            </div>
                            @else
                            <span></span>
                            @endif

                            @if($t->is_featured)
                            <span class="testimonial-badge">{{ __('home.testimonials.featured') }}</span>
                            @endif
                        </div>

                        <blockquote class="testimonial-quote mt-5 flex-1 text-[0.9375rem] leading-relaxed">
                            “{{ excerpt_text($t->testimonial, 300) }}”
                        </blockquote>

                        <figcaption class="mt-7 pt-6 border-t border-white/10 flex items-center gap-3.5">
                            @if($src = media_url($t->photo))
                                <img src="{{ $src }}" alt="{{ $t->client_name }}" width="44" height="44"
                                     class="testimonial-avatar" loading="lazy" decoding="async">
                            @else
                                <span class="testimonial-avatar-fallback" aria-hidden="true">{{ initials_of($t->client_name) }}</span>
                            @endif
                            <span class="min-w-0">
                                <span class="block font-display text-sm font-semibold text-white truncate">{{ $t->client_name }}</span>
                                <span class="block text-xs text-graphite-400 truncate">{{ $t->roleLine() ?: '—' }}</span>
                            </span>
                        </figcaption>
                    </figure>
                </li>
                @endforeach
            </ul>

            @if($testimonialSlider)
            <div class="testimonial-dots mt-8" data-testimonial-dots role="group"
                 data-testimonial-label="{{ __('home.testimonials.slides') }}"
                 aria-label="{{ __('home.testimonials.slides') }}"></div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     INSIGHTS
     ══════════════════════════════════════════════════════════════════ --}}
@if($latestPosts->isNotEmpty())
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 reveal">
            <div class="max-w-2xl">
                <p class="eyebrow">{{ __('home.insights.eyebrow') }}</p>
                <h2 class="mt-5 text-3xl sm:text-4xl">{{ __('home.insights.title') }}</h2>
            </div>
            <a href="{{ lroute('blog') }}" class="link-arrow shrink-0">
                <span>{{ __('home.insights.all') }}</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </header>

        <div class="mt-12 cards-swipe md:grid md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="80">
            @foreach($latestPosts as $post)
            <a href="{{ lroute('blog.show', $post->slug) }}" class="card-lux reveal group overflow-hidden">
                <div class="frame-lux !rounded-none !border-0 !border-b !border-line aspect-[16/10]">
                    @if($src = media_url($post->featured_image))
                        <img src="{{ $src }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-gold-400/40">
                            <x-lux-icon name="code" class="w-10 h-10" />
                        </span>
                    @endif
                </div>
                <div class="p-6 lg:p-7 flex-1 flex flex-col">
                    <div class="flex items-center gap-2.5 text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700">
                        <span>{{ $post->category->name ?? __('site.common.insight') }}</span>
                        @if($post->published_at)
                            <span class="w-1 h-1 rounded-full bg-gold-400" aria-hidden="true"></span>
                            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d M Y') }}</time>
                        @endif
                    </div>
                    <h3 class="mt-3.5 text-lg leading-snug">{{ $post->title }}</h3>
                    <p class="mt-2.5 text-sm leading-relaxed text-graphite-600">{{ excerpt_text($post->excerpt, 120) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     CLOSING CTA
     ══════════════════════════════════════════════════════════════════ --}}
<section id="kontak" class="surface-spectrum-deep relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-ember w-[26rem] h-[26rem] -bottom-60 left-[2%] opacity-28" aria-hidden="true"></div>
    <div class="bloom bloom-magenta w-[28rem] h-[28rem] -bottom-68 left-[32%] opacity-28" aria-hidden="true"></div>
    <div class="bloom bloom-violet w-[32rem] h-[32rem] -bottom-72 right-[20%] opacity-36" aria-hidden="true"></div>
    <div class="bloom bloom-azure w-[26rem] h-[26rem] -bottom-56 -right-32 opacity-25" aria-hidden="true"></div>

    <div class="shell relative z-10 py-20 lg:py-28">
        <div class="max-w-3xl mx-auto text-center">
            <p class="eyebrow eyebrow-center eyebrow-spectrum reveal">{{ __('home.cta.eyebrow') }}</p>

            <h2 class="mt-7 text-3xl sm:text-4xl lg:text-[3rem] leading-[1.12] text-white reveal reveal-d1">
                {{ __('home.cta.title') }}
                <span class="accent-serif accent-spectrum">{{ __('home.cta.accent') }}</span> {{ __('home.cta.title_after') }}
            </h2>

            <p class="mt-6 text-base lg:text-lg leading-relaxed text-graphite-300 reveal reveal-d2">
                {{ __('home.cta.lead') }}
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3.5 reveal reveal-d3">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg w-full sm:w-auto magnetic" data-magnetic="0.1">
                    <span>{{ __('site.cta.via_whatsapp') }}</span>
                    <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ lroute('contact') }}" class="btn btn-ghost btn-lg w-full sm:w-auto">
                    <span>{{ __('site.cta.send_brief') }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .home-pillars-dark {
        isolation: isolate;
        color: #e8eaee;
        background:
            radial-gradient(85% 110% at 102% 54%, rgb(123 12 24 / 42%) 0%, rgb(85 7 16 / 17%) 42%, transparent 72%),
            radial-gradient(64% 90% at -8% 4%, rgb(195 30 48 / 18%) 0%, transparent 67%),
            linear-gradient(135deg, #050506 0%, #090709 38%, #12070a 69%, #21070b 100%);
        border-block: 1px solid rgb(255 255 255 / 7%);
    }
    .home-pillars-dark::after {
        content: '';
        position: absolute;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background: linear-gradient(112deg, transparent 18%, rgb(255 255 255 / 2.5%) 49%, transparent 76%);
    }
    .home-pillars-glow {
        position: absolute;
        z-index: -1;
        border-radius: 999px;
        filter: blur(90px);
        pointer-events: none;
    }
    .home-pillars-glow-left {
        width: 24rem;
        height: 24rem;
        left: -15rem;
        top: -8rem;
        background: rgb(225 38 58 / 18%);
    }
    .home-pillars-glow-right {
        width: 32rem;
        height: 32rem;
        right: -19rem;
        bottom: -15rem;
        background: rgb(159 13 31 / 25%);
    }
    .home-pillars-eyebrow { color: #ffb1b9; }
    .home-pillars-eyebrow::before {
        background: linear-gradient(90deg, transparent, #e23c4f);
    }
    .home-pillars-accent {
        background: linear-gradient(105deg, #f3d8c7 0%, #ff9ba7 45%, #df3a4d 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .home-pillar-card {
        background: linear-gradient(145deg, rgb(255 255 255 / 5.5%), rgb(114 9 21 / 9%));
        border-color: rgb(255 255 255 / 10%);
        box-shadow: inset 0 1px 0 rgb(255 255 255 / 4%), 0 24px 55px -40px rgb(0 0 0 / 90%);
    }
    .home-pillar-card::after {
        background: linear-gradient(90deg, transparent, rgb(232 62 80 / 80%), rgb(243 216 199 / 65%), transparent);
    }
    .home-pillar-icon {
        color: #ffadb6;
        background: linear-gradient(145deg, rgb(199 30 49 / 18%), rgb(255 255 255 / 4%));
        border-color: rgb(232 62 80 / 25%);
    }
    .home-leadership-frame {
        border-color: rgb(232 62 80 / 30%);
        background: #12070a;
        box-shadow: 0 30px 70px -38px rgb(0 0 0 / 90%), 0 0 0 1px rgb(255 255 255 / 5%);
    }
    /* Leadership — commissioner row is the mirror of the CEO row: portrait
       first on mobile, bio left / portrait right from lg up. Kept here rather
       than relying on order utilities alone so the mirror always holds. */
    .home-leadership-mirror > .home-leadership-media { order: 1; }
    .home-leadership-mirror > .home-leadership-body { order: 2; }
    @media (min-width: 64rem) {
        .home-leadership-mirror > .home-leadership-media { order: 2; }
        .home-leadership-mirror > .home-leadership-body { order: 1; }
    }
    .home-pillars-cta {
        border-color: rgb(232 62 80 / 32%);
        box-shadow: inset 0 1px 0 rgb(255 255 255 / 6%);
    }
    .home-dark-red-card {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        color: #f4eff0;
        border: 1px solid rgb(207 53 72 / 18%);
        border-radius: 1.125rem;
        background:
            radial-gradient(90% 75% at 100% 100%, rgb(139 17 34 / 24%) 0%, transparent 68%),
            linear-gradient(137deg, #080a0d 0%, #101014 48%, #241014 100%);
        box-shadow: inset 0 1px 0 rgb(255 255 255 / 4%), 0 20px 46px -32px rgb(22 4 8 / 75%);
        transition: transform 620ms var(--e-soft), border-color 420ms var(--e-glide), box-shadow 620ms var(--e-soft), background 420ms var(--e-glide);
    }
    .home-dark-red-card:nth-child(3n + 2) {
        background:
            radial-gradient(80% 90% at 0% 0%, rgb(160 22 40 / 15%) 0%, transparent 62%),
            linear-gradient(142deg, #111014 0%, #080a0d 52%, #2d0e15 100%);
    }
    .home-dark-red-card:nth-child(3n + 3) {
        background:
            radial-gradient(75% 85% at 86% 12%, rgb(177 25 44 / 16%) 0%, transparent 60%),
            linear-gradient(132deg, #07090c 0%, #111014 58%, #251015 100%);
    }
    .home-dark-red-card::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        opacity: 1;
        background: linear-gradient(118deg, transparent 22%, rgb(255 255 255 / 2.5%) 50%, transparent 76%);
    }
    .home-dark-red-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 14%;
        left: 14%;
        height: 1px;
        opacity: .7;
        transform: none;
        background: linear-gradient(90deg, transparent, rgb(230 74 91 / 58%), transparent);
    }
    .home-dark-red-card h3 { color: #faf6f6; }
    .home-dark-red-card p,
    .home-dark-red-card .feature-row { color: #b9b5ba; }
    .home-dark-red-card .link-arrow { color: #efc1c6; }
    .home-dark-red-card .icon-plate {
        color: #ffadb6;
        border-color: rgb(224 68 86 / 23%);
        background: linear-gradient(145deg, rgb(173 21 40 / 23%), rgb(255 255 255 / 4%));
    }
    .home-dark-red-card .feature-row .tick {
        color: #f3a8b1;
        background: rgb(177 27 45 / 18%);
    }
    .home-dark-red-card .step-numeral { color: #d98b95; }
    .home-process-card > span:first-child {
        right: 1.5rem;
        left: 1.5rem;
        background: linear-gradient(90deg, transparent, rgb(220 66 84 / 34%), transparent);
    }

    @media (hover: hover) {
        .home-pillar-card:hover {
            background: linear-gradient(145deg, rgb(255 255 255 / 7%), rgb(142 12 28 / 15%));
            border-color: rgb(232 62 80 / 34%);
            box-shadow: 0 30px 64px -35px rgb(109 5 18 / 75%), inset 0 1px 0 rgb(255 255 255 / 6%);
        }
        .home-pillar-card:hover .home-pillar-icon {
            color: #fff5f2;
            background: linear-gradient(145deg, #b3192d, #77101f);
            border-color: rgb(255 143 156 / 48%);
            box-shadow: 0 14px 30px -14px rgb(216 38 59 / 70%);
        }
        .home-pillars-cta:hover {
            background: rgb(173 20 38 / 20%);
            border-color: rgb(255 122 137 / 52%);
        }
        .home-dark-red-card:hover {
            transform: translateY(-4px);
            border-color: rgb(226 72 89 / 32%);
            background:
                radial-gradient(95% 85% at 100% 100%, rgb(155 20 39 / 29%) 0%, transparent 68%),
                linear-gradient(137deg, #0a0c10 0%, #131116 48%, #2b1117 100%);
            box-shadow: inset 0 1px 0 rgb(255 255 255 / 6%), 0 26px 54px -32px rgb(89 5 18 / 56%);
        }
        .home-dark-red-card:hover .icon-plate {
            color: #fff4f4;
            border-color: rgb(235 92 108 / 38%);
            background: linear-gradient(145deg, rgb(183 26 46 / 48%), rgb(92 12 25 / 62%));
            box-shadow: 0 12px 28px -16px rgb(195 28 49 / 65%);
        }
    }
    @supports not ((-webkit-background-clip: text) or (background-clip: text)) {
        .home-pillars-accent {
            background: none;
            -webkit-text-fill-color: currentColor;
            color: #ff9ba7;
        }
    }
    @media (max-width: 640px) {
        .home-dark-red-card { border-radius: 1rem; }
        .home-process-card > span:first-child { right: 1.25rem; left: 1.25rem; }
    }

    .home-video-hero {
        position: relative; overflow: hidden; padding-top: 8.25rem;
        background: radial-gradient(ellipse at 50% 0, #23313a 0, #10171c 48%, #090e13 100%);
    }
    .home-video-heading {
        position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
        overflow: hidden; clip-path: inset(50%); white-space: nowrap; border: 0;
    }
    .home-video-frame {
        position: relative; isolation: isolate; width: 100%; max-width: 80rem; aspect-ratio: 16 / 9;
        margin: 0 auto; overflow: hidden; border: 1px solid rgb(206 174 114 / 45%); border-radius: 1.5rem;
        background: #090e13; box-shadow: 0 28px 70px rgb(0 0 0 / 35%), 0 0 0 5px rgb(206 174 114 / 3%);
    }
    .home-video-poster, #home-banner-video {
        position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain;
    }
    #home-banner-video { z-index: 1; background: #090e13; }
    #home-banner-video:focus-visible { outline: 2px solid #f1d8a8; outline-offset: -4px; }
    .home-video-actions:not([hidden]) {
        position: absolute; right: 1rem; bottom: 1rem; z-index: 2; display: flex; gap: .5rem;
    }
    .home-video-actions button {
        display: grid; place-items: center; width: 44px; height: 44px; cursor: pointer;
        color: #f1d8a8; background: rgb(9 14 19 / 80%); backdrop-filter: blur(12px);
        border: 1px solid rgb(224 197 142 / 40%); border-radius: 50%;
        box-shadow: 0 4px 16px rgb(0 0 0 / 20%);
    }
    .home-video-actions button:hover { background: #283038; }
    .home-video-actions button:focus-visible { outline: 2px solid #f1d8a8; outline-offset: 3px; }
    .home-video-actions [hidden] { display: none; }
    @media (min-width: 1024px) {
        .home-video-hero { padding-top: 0; }
        .home-video-hero > .shell { width: 100%; max-width: none; padding-inline: 0; }
        .home-video-frame { max-width: none; border: 0; border-radius: 0; box-shadow: none; }
    }
    @media (prefers-reduced-motion: no-preference) {
        .home-video-actions:not([hidden]) { animation: home-video-controls-in .2s ease-out; }
        @keyframes home-video-controls-in {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: none; }
        }
    }
    @media (max-width: 1023px) {
        .home-video-hero { padding-top: 6.75rem; }
    }
    @media (max-width: 640px) {
        .home-video-hero > .shell { padding-inline: .75rem; }
        .home-video-frame { border-radius: 1rem; }
        .home-video-actions:not([hidden]) { right: .5rem; bottom: .5rem; gap: .375rem; }
    }

    /* ========================================================================
       TESTIMONI
       ------------------------------------------------------------------------
       Same obsidian-and-red band as the differentiators, so the proof sits in
       the same visual family, with the gold of the design system carrying the
       quotes. Cards reuse .card-obsidian; only the tint and the rail are local.
       ========================================================================= */
    .home-testimonials {
        isolation: isolate;
        color: #e8eaee;
        background:
            radial-gradient(72% 88% at 106% 6%, rgb(123 12 24 / 34%) 0%, rgb(85 7 16 / 12%) 44%, transparent 70%),
            radial-gradient(58% 78% at -8% 104%, rgb(195 30 48 / 16%) 0%, transparent 66%),
            linear-gradient(158deg, #050506 0%, #080708 40%, #140609 72%, #1c060a 100%);
        border-block: 1px solid rgb(255 255 255 / 7%);
    }
    .home-testimonials-glow {
        position: absolute;
        z-index: -1;
        border-radius: 999px;
        filter: blur(90px);
        pointer-events: none;
    }
    .home-testimonials-glow-left {
        width: 22rem;
        height: 22rem;
        left: -13rem;
        bottom: -11rem;
        background: rgb(195 30 48 / 17%);
    }
    .home-testimonials-glow-right {
        width: 30rem;
        height: 30rem;
        right: -18rem;
        top: -15rem;
        background: rgb(159 13 31 / 22%);
    }
    .home-testimonials-eyebrow { color: #ffb1b9; }
    .home-testimonials-eyebrow::before {
        background: linear-gradient(90deg, transparent, #e23c4f);
    }
    .home-testimonials-accent {
        background: linear-gradient(105deg, #f3d8c7 0%, #e8d3a7 45%, #df3a4d 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }

    /* A 3/2/1 grid while everything fits, a snap rail once it does not. */
    .testimonial-rail {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .testimonial-slide { min-width: 0; }
    @media (min-width: 768px) {
        .testimonial-rail { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        .testimonial-rail { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    .testimonial-rail.is-slider {
        display: flex;
        flex-wrap: nowrap;
        gap: 1.25rem;
        overflow-x: auto;
        overscroll-behavior-x: contain;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }
    .testimonial-rail.is-slider::-webkit-scrollbar { display: none; }
    /* Scroll reveals are viewport-based; a slide parked off-canvas would stay
       invisible, so reveal is disabled inside the rail. */
    .testimonial-rail.is-slider > .testimonial-slide {
        flex: 0 0 100%;
        scroll-snap-align: start;
    }
    @media (min-width: 768px) {
        .testimonial-rail.is-slider > .testimonial-slide { flex-basis: calc((100% - 1.25rem) / 2); }
    }
    @media (min-width: 1024px) {
        .testimonial-rail.is-slider > .testimonial-slide { flex-basis: calc((100% - 2.5rem) / 3); }
    }
    .testimonial-rail:focus-visible {
        outline: 2px solid rgb(232 211 167 / 55%);
        outline-offset: 4px;
        border-radius: 1rem;
    }

    .testimonial-card {
        position: relative;
        padding: 1.75rem;
        background-image: linear-gradient(152deg, rgb(255 255 255 / 5%) 0%, rgb(114 9 21 / 10%) 62%, rgb(28 6 10 / 26%) 100%);
    }
    @media (min-width: 1024px) {
        .testimonial-card { padding: 2rem; }
    }
    /* Featured is a tone change, never a size change — the grid must not break. */
    .testimonial-card.is-featured {
        border-color: rgb(232 211 167 / 26%);
        background-image: linear-gradient(152deg, rgb(255 255 255 / 7%) 0%, rgb(217 184 124 / 9%) 58%, rgb(114 9 21 / 12%) 100%);
    }
    .testimonial-card.is-featured::after { opacity: 1; transform: scaleX(1); }
    .testimonial-mark {
        position: absolute;
        top: 0.25rem;
        right: 1.25rem;
        z-index: -1;
        font-family: var(--font-serif);
        font-size: 5rem;
        line-height: 1;
        color: rgb(217 184 124 / 14%);
        pointer-events: none;
    }
    .testimonial-quote {
        color: #d8dce3;
        /* A long unbroken string in a quote must wrap, not widen the card. */
        overflow-wrap: break-word;
    }
    .testimonial-badge {
        flex-shrink: 0;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        border: 1px solid rgb(232 211 167 / 24%);
        background: rgb(217 184 124 / 10%);
        color: #e8d3a7;
        font-size: 0.625rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }
    .testimonial-avatar {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 999px;
        object-fit: cover;
        border: 1px solid rgb(255 255 255 / 14%);
        flex-shrink: 0;
    }
    .testimonial-avatar-fallback {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        flex-shrink: 0;
        border-radius: 999px;
        background: linear-gradient(145deg, rgb(199 30 49 / 22%), rgb(255 255 255 / 5%));
        border: 1px solid rgb(232 62 80 / 24%);
        color: #f3d8c7;
        font-size: 0.8125rem;
        font-weight: 600;
        letter-spacing: 0.02em;
    }
    .testimonial-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 999px;
        border: 1px solid rgb(255 255 255 / 14%);
        background: rgb(255 255 255 / 4%);
        color: #e8eaee;
        transition: border-color var(--t-mid) var(--e-glide), background-color var(--t-mid) var(--e-glide);
    }
    @media (hover: hover) {
        .testimonial-nav:hover {
            border-color: rgb(232 211 167 / 34%);
            background: rgb(255 255 255 / 8%);
        }
    }
    .testimonial-nav:focus-visible {
        outline: 2px solid #e8d3a7;
        outline-offset: 2px;
    }
    .testimonial-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .testimonial-dot {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 999px;
        background: rgb(255 255 255 / 20%);
        transition: width var(--t-mid) var(--e-soft), background-color var(--t-mid) var(--e-glide);
    }
    .testimonial-dot.is-active {
        width: 1.5rem;
        background: #dfc28e;
    }

    /* ========================================================================
       KLIEN ALDEF TECH
       ------------------------------------------------------------------------
       A marquee, not a carousel: logos are proof, so they should keep moving
       rather than ask to be clicked. The track holds the logos twice and slides
       by exactly half its width, which is why the gap is a margin on each item
       — a flex gap would leave one short space at the seam and the loop would
       visibly jump.
       ========================================================================= */
    .client-marquee {
        position: relative;
        overflow: hidden;
        -webkit-mask-image: linear-gradient(90deg, transparent 0, #000 7%, #000 93%, transparent 100%);
        mask-image: linear-gradient(90deg, transparent 0, #000 7%, #000 93%, transparent 100%);
    }

    .client-marquee-track {
        display: flex;
        align-items: stretch;
        width: max-content;
        animation: clientMarquee var(--client-marquee-duration, 60s) linear infinite;
    }

    .client-marquee-item {
        display: flex;
        flex: 0 0 auto;
        margin-inline-end: 1rem;
    }

    /* Reading a logo should not be a race. */
    .client-marquee:hover .client-marquee-track,
    .client-marquee:focus-within .client-marquee-track { animation-play-state: paused; }

    @keyframes clientMarquee {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
    }

    /* Without the loop the strip is shown as one static, wrapped row instead:
       every logo stays readable, and nothing moves. */
    @media (prefers-reduced-motion: reduce) {
        .client-marquee {
            -webkit-mask-image: none;
            mask-image: none;
        }
        .client-marquee-track {
            animation: none;
            width: 100%;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.875rem;
        }
        .client-marquee-item { margin-inline-end: 0; }
        .client-marquee-item[data-client-duplicate] { display: none; }
    }
</style>
@include('_partials.client-logos-styles')
@endpush

@push('scripts')
<script>
(() => {
    const video = document.getElementById('home-banner-video');
    if (!video) return;
    const actions = document.getElementById('home-video-actions');
    const toggle = document.getElementById('home-video-toggle');
    const sound = document.getElementById('home-video-sound');
    let pageActive = true;
    let controlsTimer;

    const hideControls = () => {
        clearTimeout(controlsTimer);
        actions.hidden = true;
    };
    const showControls = () => {
        if (video.hidden || !pageActive) return;
        clearTimeout(controlsTimer);
        actions.hidden = false;
        controlsTimer = setTimeout(() => {
            // Keep keyboard controls available while a button has keyboard focus.
            if (!actions.querySelector(':focus-visible')) hideControls();
        }, 3000);
    };
    video.addEventListener('click', showControls);
    video.addEventListener('focus', () => { if (video.matches(':focus-visible')) showControls(); });
    video.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            showControls();
        } else if (event.key === 'Escape') hideControls();
    });
    actions.addEventListener('click', showControls);
    actions.addEventListener('focusin', showControls);
    actions.addEventListener('focusout', showControls);
    actions.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            video.focus({ preventScroll: true });
            hideControls();
        }
    });

    const updateControls = () => {
        toggle.setAttribute('aria-label', video.paused ? toggle.dataset.play : toggle.dataset.pause);
        toggle.querySelector('[data-icon="play"]').toggleAttribute('hidden', !video.paused);
        toggle.querySelector('[data-icon="pause"]').toggleAttribute('hidden', video.paused);
        const muted = video.muted || video.volume === 0;
        sound.setAttribute('aria-label', muted ? sound.dataset.unmute : sound.dataset.mute);
        sound.querySelector('[data-icon="sound"]').toggleAttribute('hidden', muted);
        sound.querySelector('[data-icon="muted"]').toggleAttribute('hidden', !muted);
    };

    const playVideo = async () => {
        if (!pageActive) return;
        try {
            await video.play();
        } catch (failure) {
            if (pageActive && failure.name === 'NotAllowedError' && !video.muted) {
                // Keep the banner moving when the browser blocks audible autoplay.
                video.muted = true;
                try { await video.play(); } catch { /* The play control remains available. */ }
            }
        }
        updateControls();
    };

    toggle.addEventListener('click', () => video.paused ? playVideo() : video.pause());
    sound.addEventListener('click', () => {
        video.muted = !(video.muted || video.volume === 0);
        if (!video.muted) video.volume = 1;
        if (video.paused) playVideo();
    });
    video.addEventListener('play', updateControls);
    video.addEventListener('pause', updateControls);
    video.addEventListener('volumechange', updateControls);
    video.addEventListener('playing', () => { if (!pageActive) video.pause(); });
    const showPoster = () => {
        video.hidden = true;
        hideControls();
    };
    video.addEventListener('error', showPoster);
    window.addEventListener('pagehide', () => {
        pageActive = false;
        hideControls();
        video.pause();
    });
    window.addEventListener('pageshow', (event) => {
        pageActive = true;
        if (event.persisted) playVideo();
    });

    video.controls = false;
    video.tabIndex = 0;
    video.setAttribute('aria-controls', 'home-video-actions');
    updateControls();
    if (video.error) showPoster();
    else playVideo();
})();

/* ---------------------------------------------------------------------------
   Testimonial slider
   The rail is CSS scroll-snap, so touch swipe and keyboard scrolling work with
   this script absent — it only adds arrows, position dots and a slow autoplay.
   No new dependency: a carousel of quotes does not justify one.
   ------------------------------------------------------------------------- */
(() => {
    const root = document.querySelector('[data-testimonial-slider]');
    if (!root) return;

    const rail = root.querySelector('[data-testimonial-rail]');
    const dotsBox = root.querySelector('[data-testimonial-dots]');
    const prev = root.querySelector('[data-testimonial-prev]');
    const next = root.querySelector('[data-testimonial-next]');
    const slides = rail ? Array.from(rail.children) : [];
    if (!rail || !dotsBox || !prev || !next || slides.length < 2) return;

    const calm = window.matchMedia('(prefers-reduced-motion: reduce)');
    let dots = [];
    let timer = null;

    const perView = () => {
        const slide = rail.firstElementChild;
        if (!slide) return 1;
        const gap = parseFloat(getComputedStyle(rail).columnGap || '0') || 0;
        const step = slide.getBoundingClientRect().width + gap;
        return step > 0 ? Math.max(1, Math.round((rail.clientWidth + gap) / step)) : 1;
    };

    const pageCount = () => Math.max(1, Math.ceil(slides.length / perView()));
    const pageWidth = () => rail.clientWidth;
    const activePage = () => Math.round(rail.scrollLeft / Math.max(1, pageWidth()));

    const build = () => {
        const pages = pageCount();
        dots.forEach((dot) => dot.remove());
        const label = dotsBox.dataset.testimonialLabel || '';
        dots = Array.from({ length: pages }, (_, i) => {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'testimonial-dot';
            dot.setAttribute('aria-label', `${label} ${i + 1} / ${pages}`.trim());
            dot.addEventListener('click', () => goTo(i));
            dotsBox.appendChild(dot);
            return dot;
        });
        sync();
    };

    const sync = () => {
        const current = activePage();
        dots.forEach((dot, i) => {
            dot.classList.toggle('is-active', i === current);
            if (i === current) dot.setAttribute('aria-current', 'true');
            else dot.removeAttribute('aria-current');
        });
    };

    const goTo = (page) => {
        const pages = pageCount();
        const target = ((page % pages) + pages) % pages;
        rail.scrollTo({
            left: target * pageWidth(),
            behavior: calm.matches ? 'auto' : 'smooth',
        });
        sync();
    };

    const stop = () => {
        if (timer) clearInterval(timer);
        timer = null;
    };

    const start = () => {
        stop();
        if (calm.matches) return;
        timer = setInterval(() => goTo(activePage() + 1), 7000);
    };

    prev.addEventListener('click', () => { goTo(activePage() - 1); start(); });
    next.addEventListener('click', () => { goTo(activePage() + 1); start(); });

    // Keyboard use keeps the rail's own scrolling disabled in favour of paging.
    rail.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight') { event.preventDefault(); goTo(activePage() + 1); start(); }
        if (event.key === 'ArrowLeft') { event.preventDefault(); goTo(activePage() - 1); start(); }
        if (event.key === 'Home') { event.preventDefault(); goTo(0); start(); }
        if (event.key === 'End') { event.preventDefault(); goTo(pageCount() - 1); start(); }
    });

    let scrollFrame = null;
    rail.addEventListener('scroll', () => {
        if (scrollFrame) return;
        scrollFrame = requestAnimationFrame(() => {
            scrollFrame = null;
            sync();
        });
    }, { passive: true });

    // Pause while a visitor is reading, hovering or tabbing through the cards.
    ['pointerenter', 'focusin', 'pointerdown'].forEach((event) => root.addEventListener(event, stop));
    ['pointerleave', 'focusout'].forEach((event) => root.addEventListener(event, start));
    root.addEventListener('pointerup', start);
    document.addEventListener('visibilitychange', () => (document.hidden ? stop() : start()));

    let resizeTimer = null;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => { build(); goTo(0); }, 180);
    });

    build();
    start();
})();
</script>
@endpush
