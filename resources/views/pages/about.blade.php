@extends('layouts.app')

@php
    use App\Models\SiteSetting;

    $pageTitle = __('pages.about.meta_title');
    $metaDescription = app()->isLocale('id')
        ? SiteSetting::get('about_subtitle', __('pages.about.meta_description'))
        : __('pages.about.meta_description');

    $aboutTitle    = SiteSetting::get('about_title', __('pages.about.title_fallback'));
    $aboutSubtitle = SiteSetting::get('about_subtitle');
    $mission       = SiteSetting::get('about_mission');
    $vision        = SiteSetting::get('about_vision');

    $values = [
        ['icon' => 'compass',   'key' => 'scope'],
        ['icon' => 'blueprint', 'key' => 'simple'],
        ['icon' => 'lock',      'key' => 'nolock'],
        ['icon' => 'clock',     'key' => 'time'],
    ];

    // Journey entries live in the language files.
@endphp

@section('content')

{{-- The title is editable from the admin, so no accent clause is appended here:
     it would run on into whatever the admin typed. --}}
<x-page-hero
    :eyebrow="__('pages.about.eyebrow')"
    :title="$aboutTitle"
    :lead="$aboutSubtitle"
    :edge="false"
    :breadcrumbs="[['label' => __('site.nav.about')]]">
    <p class="font-serif-accent italic text-xl sm:text-2xl accent-spectrum">
        {{ __('site.footer.tagline') }}
    </p>
</x-page-hero>

{{-- ── Brand plate ──────────────────────────────────────────────────────── --}}
<section class="bg-hero-ground spectrum-edge relative overflow-hidden pb-16 lg:pb-24">
    <div class="shell relative z-10">
        <figure class="max-w-4xl mx-auto frame-banner reveal-scale" data-tilt="2.5">
            <img src="{{ asset('images/aldef-tech-banner.webp') }}"
                 alt="Aldef Tech — pengembangan sistem, aplikasi, kecerdasan buatan, dan solusi IT"
                 width="1376" height="768" loading="lazy" decoding="async">
        </figure>
    </div>
</section>

{{-- ── Mission & vision ─────────────────────────────────────────────────── --}}
@if($mission || $vision)
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6" data-reveal-group="120">
            @if($mission)
            <article class="card-lux about-premium-card reveal group p-8 lg:p-10">
                <span class="icon-plate"><x-lux-icon name="target" /></span>
                <h2 class="mt-6 text-2xl">{{ __('pages.about.mission') }}</h2>
                <p class="mt-4 text-[0.9375rem] leading-[1.8] text-graphite-600">{{ $mission }}</p>
            </article>
            @endif

            @if($vision)
            <article class="card-lux about-premium-card reveal group p-8 lg:p-10">
                <span class="icon-plate"><x-lux-icon name="rocket" /></span>
                <h2 class="mt-6 text-2xl">{{ __('pages.about.vision') }}</h2>
                <p class="mt-4 text-[0.9375rem] leading-[1.8] text-graphite-600">{{ $vision }}</p>
            </article>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ── Founder ──────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

            <div class="lg:col-span-5 reveal-left">
                <figure class="frame-lux aspect-[4/5] max-w-sm mx-auto lg:max-w-none lg:sticky lg:top-28">
                    <img src="{{ media_url($ceoProfile->profile_photo ?? null, 'images/deni-afrizal.jpg') }}"
                         alt="{{ $ceoProfile->name }}" loading="lazy" decoding="async">
                </figure>
                @if($founderVideoUrl)
                    <x-video-popup :src="$founderVideoUrl"
                        :title="__('pages.about.founder_video.title')"
                        :watch="__('pages.about.founder_video.watch')"
                        :poster="media_url($ceoProfile->profile_photo ?? null, 'images/deni-afrizal.jpg')" />
                @endif
            </div>

            <div class="lg:col-span-7 reveal-right">
                <p class="eyebrow">{{ __('pages.about.founder') }}</p>

                <h2 class="mt-5 text-3xl sm:text-4xl">{{ $ceoProfile->name }}</h2>
                <p class="mt-2 text-sm text-gold-700 font-display font-semibold tracking-wide">{{ $ceoProfile->position }}</p>

                @if(!empty($ceoProfile->short_bio))
                <blockquote class="mt-8 font-serif-accent italic text-xl sm:text-2xl leading-[1.45] text-graphite-900 border-l-2 border-gold-500 pl-6">
                    {{ $ceoProfile->short_bio }}
                </blockquote>
                @endif

                @if(!empty($ceoProfile->full_bio))
                <p class="mt-8 text-[0.9375rem] leading-[1.85] text-graphite-700">{{ $ceoProfile->full_bio }}</p>
                @endif

                @if(!empty($ceoProfile->skills))
                <div class="mt-9">
                    <p class="eyebrow mb-4">{{ __('pages.about.skills') }}</p>
                    <div class="chip-strip flex gap-2 sm:flex-wrap">
                        @foreach((array) $ceoProfile->skills as $skill)
                            <span class="chip chip-neutral">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($ceoProfile->experience))
                <div class="mt-9">
                    <p class="eyebrow mb-5">{{ __('pages.about.experience') }}</p>
                    <ul class="space-y-3">
                        @foreach((array) $ceoProfile->experience as $exp)
                        <li class="feature-row">
                            <span class="tick">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ is_array($exp) ? ($exp['title'] ?? reset($exp)) : $exp }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    @if(!empty($ceoProfile->linkedin))
                        <a href="{{ $ceoProfile->linkedin }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><span>LinkedIn</span></a>
                    @endif
                    @if(!empty($ceoProfile->github))
                        <a href="{{ $ceoProfile->github }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><span>GitHub</span></a>
                    @endif
                    @if(!empty($ceoProfile->email))
                        <a href="mailto:{{ $ceoProfile->email }}" class="btn btn-outline btn-sm"><span>Email</span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Commissioner ───────────────────────────────────────────────────────
     The full commissioner profile, mirroring the founder block above: bio and
     links left, portrait right from lg up, portrait first on mobile. Both rows
     read from the same table, so the dashboard is the only place either is
     edited. No field is invented — anything the editor left blank is skipped. --}}
@if($commissionerProfile)
@php $commissionerPhoto = media_url($commissionerProfile->profile_photo); @endphp
<section class="section-padding surface-parchment border-b border-line">
    <div class="shell">
        <div class="leadership-mirror grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

            <div class="leadership-body order-2 lg:order-1 {{ $commissionerPhoto ? 'lg:col-span-7' : 'lg:col-span-12' }} reveal-left">
                <p class="eyebrow">{{ __('pages.about.commissioner') }}</p>

                <h2 class="mt-5 text-3xl sm:text-4xl">{{ $commissionerProfile->name }}</h2>
                <p class="mt-2 text-sm text-gold-700 font-display font-semibold tracking-wide">{{ $commissionerProfile->position }}</p>

                @if(!empty($commissionerProfile->short_bio))
                <blockquote class="mt-8 font-serif-accent italic text-xl sm:text-2xl leading-[1.45] text-graphite-900 border-l-2 border-gold-500 pl-6">
                    {{ $commissionerProfile->short_bio }}
                </blockquote>
                @endif

                @if(!empty($commissionerProfile->full_bio))
                <p class="mt-8 text-[0.9375rem] leading-[1.85] text-graphite-700">{{ $commissionerProfile->full_bio }}</p>
                @endif

                @if(!empty($commissionerProfile->skills))
                <div class="mt-9">
                    <p class="eyebrow mb-4">{{ __('pages.about.skills') }}</p>
                    <div class="chip-strip flex gap-2 sm:flex-wrap">
                        @foreach((array) $commissionerProfile->skills as $skill)
                            <span class="chip chip-neutral">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($commissionerProfile->experience))
                <div class="mt-9">
                    <p class="eyebrow mb-5">{{ __('pages.about.experience') }}</p>
                    <ul class="space-y-3">
                        @foreach((array) $commissionerProfile->experience as $exp)
                        <li class="feature-row">
                            <span class="tick">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ is_array($exp) ? ($exp['title'] ?? reset($exp)) : $exp }}</span>
                        </li>
                        @endforeach
                    </ul>
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
                <div class="mt-9 flex flex-wrap items-center gap-3">
                    @foreach($commissionerLinks as $label => $href)
                    <a href="{{ $href }}"@if(! str_starts_with($href, 'mailto:')) target="_blank" rel="noopener"@endif class="btn btn-outline btn-sm"><span>{{ $label }}</span></a>
                    @endforeach
                </div>
                @endif
            </div>

            @if($commissionerPhoto)
            <div class="leadership-media order-1 lg:order-2 lg:col-span-5 reveal-right">
                <figure class="frame-lux aspect-[4/5] max-w-sm mx-auto lg:max-w-none">
                    <img src="{{ $commissionerPhoto }}" alt="{{ $commissionerProfile->name }}" loading="lazy" decoding="async">
                </figure>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ── Values ───────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">{{ __('pages.about.values.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl">
                {{ __('pages.about.values.title') }} <span class="accent-serif accent-gold">{{ __('pages.about.values.accent') }}</span>{{ __('pages.about.values.title_after') }}
            </h2>
        </header>

        <div class="mt-12 lg:mt-16 cards-swipe md:grid md:grid-cols-2 gap-5 lg:gap-6" data-reveal-group="90">
            @foreach($values as $value)
            <article class="card-lux about-premium-card reveal group p-7 lg:p-8 !flex-row items-start gap-5">
                <span class="icon-plate"><x-lux-icon :name="$value['icon']" /></span>
                <div class="min-w-0">
                    <h3 class="text-lg">{{ __('pages.about.values.' . $value['key'] . '.title') }}</h3>
                    <p class="mt-2.5 text-sm leading-relaxed text-graphite-600">{{ __('pages.about.values.' . $value['key'] . '.body') }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Journey ──────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-obsidian relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-gold w-[34rem] h-[34rem] -top-48 right-0 opacity-40" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="max-w-2xl reveal">
            <p class="eyebrow eyebrow-light">{{ __('pages.about.journey.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl text-white">
                {{ __('pages.about.journey.title') }} <span class="accent-serif accent-champagne">{{ __('pages.about.journey.accent') }}</span>
            </h2>
        </header>

        <ol class="mt-12 lg:mt-16 cards-swipe md:grid md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="100">
            @foreach((array) __('pages.about.journey.items') as $item)
            <li class="card-obsidian reveal group p-7 lg:p-8">
                <span class="chip chip-dark">{{ $item['year'] }}</span>
                <h3 class="mt-5 text-lg text-white">{{ $item['title'] }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-graphite-400">{{ $item['body'] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ── Klien Aldef Tech ─────────────────────────────────────────────────── --}}
@if($clients->isNotEmpty())
<section id="klien" class="client-band section-padding relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="client-band-glow" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="max-w-2xl reveal">
            <p class="eyebrow eyebrow-light">{{ __('pages.about.clients.eyebrow') }}</p>
            <h2 class="mt-5 text-3xl sm:text-4xl text-white">
                {{ __('pages.about.clients.title') }} <span class="accent-serif accent-champagne">{{ __('pages.about.clients.accent') }}</span>
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-400">{{ __('pages.about.clients.lead') }}</p>
        </header>

        {{-- A grid, not the homepage marquee: this page is read slowly, so the
             logos are simply there, in the same order the editor set. --}}
        <div class="client-grid mt-12 lg:mt-14">
            @foreach($clients as $client)
            <x-client-logo :client="$client" />
            @endforeach
        </div>
    </div>
</section>
@endif

<x-cta-band
    :eyebrow="__('pages.about.closing.eyebrow')"
    :title="__('pages.about.closing.title')"
    :accent="__('pages.about.closing.accent')"
    :lead="__('pages.about.closing.lead')" />

@endsection

@push('styles')
<style>
    .about-premium-card {
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
    .about-premium-card:nth-child(3n + 2) {
        background:
            radial-gradient(80% 90% at 0% 0%, rgb(160 22 40 / 15%) 0%, transparent 62%),
            linear-gradient(142deg, #111014 0%, #080a0d 52%, #2d0e15 100%);
    }
    .about-premium-card:nth-child(3n + 3) {
        background:
            radial-gradient(75% 85% at 86% 12%, rgb(177 25 44 / 16%) 0%, transparent 60%),
            linear-gradient(132deg, #07090c 0%, #111014 58%, #251015 100%);
    }
    .about-premium-card::before {
        opacity: 1;
        background: linear-gradient(118deg, transparent 22%, rgb(255 255 255 / 2.5%) 50%, transparent 76%);
    }
    .about-premium-card::after {
        right: 14%;
        left: 14%;
        opacity: .72;
        transform: none;
        background: linear-gradient(90deg, transparent, rgb(215 173 94 / 72%), rgb(206 61 78 / 44%), transparent);
    }
    .about-premium-card h2,
    .about-premium-card h3 { color: #faf6f6; }
    .about-premium-card p { color: #bbb7bc; }
    .about-premium-card .icon-plate {
        color: #d9b87c;
        border-color: rgb(217 184 124 / 24%);
        background: linear-gradient(145deg, rgb(217 184 124 / 14%), rgb(116 17 31 / 19%));
    }

    @media (hover: hover) {
        .about-premium-card:hover {
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
        .about-premium-card:hover::before { opacity: 1; }
        .about-premium-card:hover::after { opacity: 1; transform: none; }
        .about-premium-card:hover .icon-plate {
            color: #f5e8cc;
            border-color: rgb(232 211 167 / 42%);
            background: linear-gradient(145deg, rgb(217 184 124 / 22%), rgb(119 15 31 / 28%));
            box-shadow: 0 12px 28px -16px rgb(217 184 124 / 52%);
        }
    }

    @media (max-width: 640px) {
        .about-premium-card { border-radius: 1rem; }
    }

    /* Commissioner row is the mirror of the founder row: portrait first on
       mobile, bio left / portrait right from lg up. Declared here so the
       ordering cannot be lost to a stylesheet that lags behind the markup. */
    .leadership-mirror > .leadership-media { order: 1; }
    .leadership-mirror > .leadership-body { order: 2; }
    @media (min-width: 64rem) {
        .leadership-mirror > .leadership-media { order: 2; }
        .leadership-mirror > .leadership-body { order: 1; }
    }
</style>
@include('_partials.client-logos-styles')
@endpush
