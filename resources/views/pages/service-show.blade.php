@extends('layouts.app')

@php
    use App\Services\WhatsAppService;

    $pageTitle = $page['meta_title'];
    $metaDescription = $page['meta_description'];
    $serviceName = $service?->title ?: $page['eyebrow'];
    $waUrl = WhatsAppService::getProjectUrl($serviceName);
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => $serviceName,
    'serviceType' => $page['eyebrow'],
    'description' => $metaDescription,
    'url' => $canonical,
    'provider' => [
        '@type' => 'Organization',
        'name' => 'Aldef Tech',
        'url' => config('app.url'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.services'), 'item' => lroute('services')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $serviceName, 'item' => $canonical],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<x-page-hero
    :eyebrow="$page['eyebrow']"
    :title="$page['title']"
    :accent="$page['accent']"
    :lead="$page['lead']"
    align="left"
    :breadcrumbs="[
        ['label' => __('site.nav.services'), 'url' => lroute('services')],
        ['label' => $serviceName],
    ]">
    <div class="flex flex-col sm:flex-row items-start gap-3.5">
        <a href="{{ $waUrl }}" target="_blank" rel="noopener"
           class="btn btn-primary btn-lg w-full sm:w-auto"
           data-analytics-event="whatsapp_click"
           data-analytics-cta-location="hero"
           data-analytics-service="{{ $serviceName }}"
           data-analytics-destination="whatsapp">
            <span>{{ app()->isLocale('id') ? 'Konsultasi Proyek Gratis' : 'Free project consultation' }}</span>
            <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        <a href="{{ lroute('contact') }}" class="btn btn-ghost btn-lg w-full sm:w-auto"
           data-analytics-event="cta_click" data-analytics-cta-location="hero"
           data-analytics-service="{{ $serviceName }}" data-analytics-destination="contact">
            {{ app()->isLocale('id') ? 'Kirim Brief Proyek' : 'Send a project brief' }}
        </a>
    </div>
</x-page-hero>

<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>
    <div class="shell relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
            <article class="lg:col-span-7">
                <p class="eyebrow reveal">{{ app()->isLocale('id') ? 'Masalah yang sering dihadapi' : 'Common pain points' }}</p>
                <h2 class="mt-5 text-3xl sm:text-4xl reveal">{{ app()->isLocale('id') ? 'Mulai dari hambatan yang paling nyata.' : 'Start with the constraint that is most real.' }}</h2>
                <ul class="mt-8 space-y-4" data-reveal-group="80">
                    @foreach($page['pain_points'] as $point)
                    <li class="card-lux reveal p-5 flex items-start gap-4">
                        <span class="tick mt-0.5 shrink-0"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg></span>
                        <span class="text-[0.9375rem] leading-relaxed text-graphite-700">{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>
            <aside class="lg:col-span-5">
                <div class="card-lux card-lux-featured reveal p-7 lg:p-8">
                    <p class="eyebrow">{{ app()->isLocale('id') ? 'Cara kami membantu' : 'How we help' }}</p>
                    <p class="mt-4 text-sm leading-relaxed text-graphite-600">{{ $page['lead'] }}</p>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                       class="btn btn-primary btn-block mt-7"
                       data-analytics-event="whatsapp_click"
                       data-analytics-cta-location="service_card"
                       data-analytics-service="{{ $serviceName }}"
                       data-analytics-destination="whatsapp">
                        {{ app()->isLocale('id') ? 'Diskusikan Sistem yang Anda Butuhkan' : 'Discuss the system you need' }}
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-6">
            <div class="card-lux p-7 lg:p-8 reveal">
                <p class="eyebrow">{{ app()->isLocale('id') ? 'Kapabilitas' : 'Capabilities' }}</p>
                <ul class="mt-6 space-y-3.5">
                    @foreach($page['capabilities'] as $item)
                    <li class="feature-row"><span class="tick"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg></span><span>{{ $item }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="card-lux p-7 lg:p-8 reveal">
                <p class="eyebrow">{{ app()->isLocale('id') ? 'Dampak bisnis yang dituju' : 'Business outcomes to pursue' }}</p>
                <ul class="mt-6 space-y-3.5">
                    @foreach($page['benefits'] as $item)
                    <li class="feature-row"><span class="tick"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg></span><span>{{ $item }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section-padding surface-ivory">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
            <div class="lg:col-span-7">
                <p class="eyebrow reveal">{{ app()->isLocale('id') ? 'Development process' : 'Development process' }}</p>
                <h2 class="mt-5 text-3xl sm:text-4xl reveal">{{ app()->isLocale('id') ? 'Langkah yang dapat ditinjau bersama.' : 'A process you can review with us.' }}</h2>
                <ol class="mt-9 space-y-6" data-reveal-group="90">
                    @foreach($page['process'] as $index => $step)
                    <li class="flex items-start gap-5 reveal">
                        <span class="font-serif-accent italic text-2xl text-gold-600 leading-none">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="text-[0.9375rem] leading-relaxed text-graphite-700">{{ $step }}</p>
                    </li>
                    @endforeach
                </ol>
            </div>
            <aside class="lg:col-span-5">
                <div class="card-quiet reveal p-7">
                    <p class="eyebrow">{{ app()->isLocale('id') ? 'Teknologi relevan' : 'Relevant technologies' }}</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach($page['technologies'] as $technology)
                            <span class="chip chip-neutral">{{ $technology }}</span>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@if($portfolios->isNotEmpty())
<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <p class="eyebrow reveal">{{ app()->isLocale('id') ? 'Portofolio relevan' : 'Relevant portfolio' }}</p>
        <h2 class="mt-5 text-3xl sm:text-4xl reveal">{{ app()->isLocale('id') ? 'Lihat konteks pekerjaan yang pernah kami bangun.' : 'See the context of work we have built.' }}</h2>
        <div class="mt-9 cards-swipe md:grid md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="80">
            @foreach($portfolios as $item)
            <a href="{{ lroute('portfolio.show', $item->slug) }}" class="card-lux reveal group overflow-hidden"
               data-analytics-event="cta_click" data-analytics-cta-location="service_portfolio"
               data-analytics-service="{{ $serviceName }}" data-analytics-portfolio-slug="{{ $item->slug }}"
               data-analytics-destination="portfolio">
                <div class="frame-lux !rounded-none !border-0 !border-b !border-line aspect-[16/10] bg-ivory-200">
                    @if($src = media_url($item->featured_image))
                        <img src="{{ $src }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-gold-400/50"><x-lux-icon name="layers" class="w-10 h-10" /></span>
                    @endif
                </div>
                <div class="p-6"><p class="text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700">{{ $item->category->name ?? __('site.common.project') }}</p><h3 class="mt-3 text-base leading-snug">{{ $item->title }}</h3></div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section-padding surface-ivory">
    <div class="shell max-w-4xl">
        <p class="eyebrow reveal">{{ app()->isLocale('id') ? 'Pertanyaan umum' : 'Frequently asked questions' }}</p>
        <h2 class="mt-5 text-3xl sm:text-4xl reveal">{{ app()->isLocale('id') ? 'Sebelum memulai percakapan.' : 'Before you start the conversation.' }}</h2>
        <div class="mt-8 space-y-3" data-reveal-group="70">
            @foreach($page['faq'] as $faq)
            <details class="card-lux reveal p-5 group">
                <summary class="cursor-pointer list-none font-display font-semibold text-graphite-900 flex items-center justify-between gap-4">
                    <span>{{ $faq['q'] }}</span><span class="text-gold-600 text-xl" aria-hidden="true">+</span>
                </summary>
                <p class="mt-4 text-sm leading-relaxed text-graphite-600">{{ $faq['a'] }}</p>
            </details>
            @endforeach
        </div>
    </div>
</section>

<section class="surface-spectrum-deep relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="shell relative z-10 py-20 lg:py-24 text-center">
        <p class="eyebrow eyebrow-center eyebrow-spectrum reveal">{{ app()->isLocale('id') ? 'Langkah berikutnya' : 'Next step' }}</p>
        <h2 class="mt-6 text-3xl sm:text-4xl text-white reveal">{{ app()->isLocale('id') ? 'Punya proses yang ingin dibenahi?' : 'Have a process you want to improve?' }}</h2>
        <div class="mt-9 flex flex-col sm:flex-row items-center justify-center gap-3.5 reveal">
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg w-full sm:w-auto"
               data-analytics-event="whatsapp_click" data-analytics-cta-location="final_cta"
               data-analytics-service="{{ $serviceName }}" data-analytics-destination="whatsapp">
                {{ app()->isLocale('id') ? 'Konsultasi Proyek Gratis' : 'Free project consultation' }}
            </a>
            <a href="{{ lroute('contact') }}" class="btn btn-ghost btn-lg w-full sm:w-auto"
               data-analytics-event="cta_click" data-analytics-cta-location="final_cta"
               data-analytics-service="{{ $serviceName }}" data-analytics-destination="contact">
                {{ app()->isLocale('id') ? 'Kirim Brief Proyek' : 'Send a project brief' }}
            </a>
        </div>
    </div>
</section>

@endsection
