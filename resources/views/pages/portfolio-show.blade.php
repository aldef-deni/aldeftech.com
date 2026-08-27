@extends('layouts.app')

@php
    $pageTitle = $portfolio->meta_title ?: $portfolio->title . ' — Studi Kasus | Aldef Tech';
    $metaDescription = $portfolio->meta_description ?: excerpt_text($portfolio->short_description, 160);
    $ogImage = media_url($portfolio->featured_image, 'images/aldef-tech-banner.png');
    $ogType = 'article';

    $narrative = array_filter([
        'Tantangan'  => $portfolio->challenge,
        'Pendekatan' => $portfolio->approach,
        'Solusi'     => $portfolio->solution,
        'Hasil'      => $portfolio->results,
    ]);
@endphp

@section('content')

<x-page-hero
    :eyebrow="$portfolio->category->name ?? 'Studi Kasus'"
    :title="$portfolio->title"
    :lead="$portfolio->short_description"
    align="left"
    compact
    :breadcrumbs="[
        ['label' => 'Portofolio', 'url' => route('portfolio')],
        ['label' => $portfolio->title],
    ]" />

{{-- ── Fact strip ───────────────────────────────────────────────────────── --}}
<section class="surface-ivory-deep border-b border-line">
    <div class="shell">
        <dl class="grid grid-cols-2 lg:grid-cols-4 gap-y-8 gap-x-6 py-9 lg:py-11" data-reveal-group="70">
            @if($portfolio->client)
            <div class="reveal">
                <dt class="eyebrow">Klien</dt>
                <dd class="mt-3 font-display text-sm font-semibold text-graphite-900">{{ $portfolio->client }}</dd>
            </div>
            @endif

            @if($portfolio->year)
            <div class="reveal">
                <dt class="eyebrow">Tahun</dt>
                <dd class="mt-3 font-display text-sm font-semibold text-graphite-900 tabular">{{ $portfolio->year }}</dd>
            </div>
            @endif

            @if($portfolio->category)
            <div class="reveal">
                <dt class="eyebrow">Kategori</dt>
                <dd class="mt-3 font-display text-sm font-semibold text-graphite-900">{{ $portfolio->category->name }}</dd>
            </div>
            @endif

            @if($portfolio->project_url)
            <div class="reveal">
                <dt class="eyebrow">Tautan</dt>
                <dd class="mt-3">
                    <a href="{{ $portfolio->project_url }}" target="_blank" rel="noopener" class="link-arrow">
                        <span>Kunjungi</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </dd>
            </div>
            @endif
        </dl>

        @if(!empty($portfolio->technologies))
        <div class="pb-9 lg:pb-11 reveal">
            <p class="eyebrow mb-4">Teknologi</p>
            <div class="flex flex-wrap gap-2">
                @foreach((array) $portfolio->technologies as $tech)
                    <span class="chip chip-neutral">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ── Hero image ───────────────────────────────────────────────────────── --}}
@if($src = media_url($portfolio->featured_image))
<section class="surface-ivory pt-12 lg:pt-16">
    <div class="shell">
        <figure class="frame-lux reveal-scale">
            <img src="{{ $src }}" alt="{{ $portfolio->title }}" class="!h-auto" decoding="async">
        </figure>
    </div>
</section>
@endif

{{-- ── Narrative ────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

            <div class="lg:col-span-8">
                @if(!empty($portfolio->description))
                <div class="prose-lux reveal">{!! nl2br(e($portfolio->description)) !!}</div>
                @endif

                @if(!empty($narrative))
                <div class="mt-12 space-y-6" data-reveal-group="80">
                    @foreach($narrative as $label => $body)
                    <article class="card-lux reveal p-7 lg:p-8">
                        <p class="eyebrow">{{ $label }}</p>
                        <div class="mt-4 text-[0.9375rem] leading-[1.8] text-graphite-700">{!! nl2br(e($body)) !!}</div>
                    </article>
                    @endforeach
                </div>
                @endif

                @if($portfolio->images && $portfolio->images->isNotEmpty())
                <div class="mt-12">
                    <p class="eyebrow mb-6 reveal">Galeri</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" data-reveal-group="70">
                        @foreach($portfolio->images as $image)
                        <figure class="frame-lux reveal group aspect-[4/3]">
                            <img src="{{ media_url($image->image) }}"
                                 alt="{{ $image->caption ?? $portfolio->title }}" loading="lazy" decoding="async">
                        </figure>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-28 space-y-5">
                    <div class="card-lux card-lux-featured reveal p-7">
                        <p class="eyebrow">Punya kebutuhan serupa?</p>
                        <h2 class="mt-4 text-xl leading-snug">
                            Kami bisa membangun yang <span class="accent-serif accent-gold">sepadan</span> untuk Anda.
                        </h2>
                        <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                            Diskusi awal gratis. Hasilnya berupa gambaran ruang lingkup dan estimasi yang konkret.
                        </p>
                        <a href="{{ \App\Services\WhatsAppService::getProjectUrl($portfolio->title) }}"
                           target="_blank" rel="noopener" class="btn btn-primary btn-block mt-6">
                            <span>Konsultasi sekarang</span>
                            <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline btn-block mt-2.5">
                            <span>Kirim brief</span>
                        </a>
                    </div>

                    <a href="{{ route('portfolio') }}" class="link-arrow">
                        <svg class="w-3.5 h-3.5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        <span>Semua portofolio</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ── Related ──────────────────────────────────────────────────────────── --}}
@if($relatedPortfolios->isNotEmpty())
<section class="section-padding-sm surface-parchment border-t border-line">
    <div class="shell">
        <p class="eyebrow reveal">Proyek Terkait</p>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="80">
            @foreach($relatedPortfolios as $related)
            <a href="{{ route('portfolio.show', $related->slug) }}" class="card-lux reveal group overflow-hidden">
                <div class="frame-lux !rounded-none !border-0 !border-b !border-line aspect-[16/10] bg-ivory-200">
                    @if($rsrc = media_url($related->featured_image))
                        <img src="{{ $rsrc }}" alt="{{ $related->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-gold-400/50">
                            <x-lux-icon name="layers" class="w-10 h-10" />
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    <p class="text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700">{{ $related->category->name ?? 'Proyek' }}</p>
                    <h3 class="mt-3 text-base leading-snug">{{ $related->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-cta-band />

@endsection
