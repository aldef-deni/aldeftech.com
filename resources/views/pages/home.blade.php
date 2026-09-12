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
            <article class="card-lux reveal group p-7 lg:p-8">
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
            <li class="reveal group relative pt-7">
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
            <a href="{{ lroute('solutions') }}" class="card-quiet reveal group p-6 flex flex-col">
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
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     TESTIMONIALS
     ══════════════════════════════════════════════════════════════════ --}}
@if($testimonials->isNotEmpty())
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">{{ __('home.testimonials.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                {{ __('home.testimonials.title') }} <span class="accent-serif accent-gold">{{ __('home.testimonials.accent') }}</span>{{ __('home.testimonials.title_after') }}
            </h2>
        </header>

        <div class="mt-12 lg:mt-16 cards-swipe md:grid md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6"
             data-reveal-group="80">
            @foreach($testimonials->take(6) as $t)
            <figure class="card-lux reveal p-7 lg:p-8">
                @if($t->rating)
                <div class="flex gap-1 text-gold-500" aria-label="{{ __('home.testimonials.rating', ['rating' => $t->rating]) }}">
                    @for($s = 1; $s <= 5; $s++)
                        <svg class="w-3.5 h-3.5 {{ $s <= $t->rating ? 'opacity-100' : 'opacity-25' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L1.5 7.7l5.9-.9z"/></svg>
                    @endfor
                </div>
                @endif

                <blockquote class="mt-5 text-[0.9375rem] leading-relaxed text-graphite-700">
                    “{{ excerpt_text($t->testimonial, 260) }}”
                </blockquote>

                <figcaption class="mt-7 pt-6 border-t border-line-soft flex items-center gap-3.5">
                    @if($src = media_url($t->photo))
                        <img src="{{ $src }}" alt="{{ $t->client_name }}" class="w-11 h-11 rounded-full object-cover border border-line" loading="lazy">
                    @else
                        <span class="w-11 h-11 rounded-full bg-gold-100 border border-gold-200 text-gold-700 font-display text-sm font-semibold flex items-center justify-center shrink-0">
                            {{ initials_of($t->client_name) }}
                        </span>
                    @endif
                    <span class="min-w-0">
                        <span class="block font-display text-sm font-semibold text-graphite-900 truncate">{{ $t->client_name }}</span>
                        <span class="block text-xs text-graphite-500 truncate">{{ trim(($t->position ? $t->position . ' · ' : '') . $t->company, ' ·') }}</span>
                    </span>
                </figcaption>
            </figure>
            @endforeach
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
    .home-pillars-cta {
        border-color: rgb(232 62 80 / 32%);
        box-shadow: inset 0 1px 0 rgb(255 255 255 / 6%);
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
    }
    @supports not ((-webkit-background-clip: text) or (background-clip: text)) {
        .home-pillars-accent {
            background: none;
            -webkit-text-fill-color: currentColor;
            color: #ff9ba7;
        }
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
</style>
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
</script>
@endpush
