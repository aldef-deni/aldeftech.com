@extends('layouts.app')
@section('content')
@php
$pageTitle = $portfolio->meta_title ?? $portfolio->title . ' — Portfolio';
$metaDescription = $portfolio->meta_description ?? $portfolio->short_description;
@endphp

{{-- Hero --}}
<section class="section-padding pt-24 lg:pt-32 relative overflow-hidden">
    <div class="hero-orb hero-orb-1 opacity-40"></div>
    <div class="hero-orb hero-orb-2 opacity-40"></div>
    <div class="absolute inset-0 hero-grid opacity-25"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <a href="{{ route('portfolio') }}" class="btn-link mb-10 reveal inline-flex items-center gap-2 text-sm text-text-muted hover:text-accent-light">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/></svg>
            Kembali ke Portfolio
        </a>

        <div class="reveal reveal-delay-1">
            @if($portfolio->category)
            <span class="section-eyebrow">{{ $portfolio->category->name }}</span>
            @endif
            <h1 class="text-4xl sm:text-5xl font-display text-gradient-hero mb-6 leading-tight">{{ $portfolio->title }}</h1>
            <p class="text-text-secondary text-lg lg:text-xl leading-relaxed">{{ $portfolio->short_description }}</p>
        </div>

        {{-- Meta Info --}}
        <div class="flex flex-wrap items-center gap-8 mt-10 py-8 border-t border-b border-brand-border reveal reveal-delay-2">
            @if($portfolio->client)
            <div>
                <span class="text-text-dark text-[0.6875rem] uppercase tracking-[0.15em] font-semibold">Client</span>
                <div class="text-text-primary text-sm font-medium mt-1.5">{{ $portfolio->client }}</div>
            </div>
            @endif
            @if($portfolio->year)
            <div>
                <span class="text-text-dark text-[0.6875rem] uppercase tracking-[0.15em] font-semibold">Year</span>
                <div class="text-text-primary text-sm font-medium mt-1.5">{{ $portfolio->year }}</div>
            </div>
            @endif
            @if($portfolio->technologies && count($portfolio->technologies))
            <div>
                <span class="text-text-dark text-[0.6875rem] uppercase tracking-[0.15em] font-semibold">Technologies</span>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    @foreach($portfolio->technologies as $tech)
                    <span class="tag tag-accent text-xs">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Featured Image --}}
@if($portfolio->featured_image)
<section class="pb-16">
    <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10 reveal-scale">
        <div class="rounded-2xl overflow-hidden border border-brand-border shadow-elevated">
            <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full">
        </div>
    </div>
</section>
@endif

{{-- Content --}}
<section class="section-padding pt-0">
    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        @if($portfolio->description)
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-text-primary mb-4">Project Overview</h2>
            <div class="text-text-secondary leading-relaxed prose-dark text-base lg:text-lg">{!! nl2br(e($portfolio->description)) !!}</div>
        </div>
        @endif

        @if($portfolio->challenge)
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-text-primary mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-danger/10 border border-danger/20 flex items-center justify-center text-danger text-sm">⚡</span>
                The Challenge
            </h2>
            <div class="text-text-secondary leading-relaxed prose-dark bg-brand-surface border border-brand-border rounded-xl p-6 lg:p-8">{!! nl2br(e($portfolio->challenge)) !!}</div>
        </div>
        @endif

        @if($portfolio->approach)
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-text-primary mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-brand-orange/10 border border-brand-orange/20 flex items-center justify-center text-brand-orange text-sm">🛠️</span>
                The Approach
            </h2>
            <div class="text-text-secondary leading-relaxed prose-dark bg-brand-surface border border-brand-border rounded-xl p-6 lg:p-8">{!! nl2br(e($portfolio->approach)) !!}</div>
        </div>
        @endif

        @if($portfolio->solution)
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-text-primary mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-accent/10 border border-accent/20 flex items-center justify-center text-accent text-sm">💡</span>
                The Solution
            </h2>
            <div class="text-text-secondary leading-relaxed prose-dark bg-brand-surface border border-brand-border rounded-xl p-6 lg:p-8">{!! nl2br(e($portfolio->solution)) !!}</div>
        </div>
        @endif

        @if($portfolio->results)
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-text-primary mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-success/10 border border-success/20 flex items-center justify-center text-success text-sm">📈</span>
                The Result & Impact
            </h2>
            <div class="text-text-secondary leading-relaxed prose-dark bg-brand-surface border border-brand-border rounded-xl p-6 lg:p-8">{!! nl2br(e($portfolio->results)) !!}</div>
        </div>
        @endif

        {{-- Gallery --}}
        @if($portfolio->images->count())
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-text-primary mb-6">Gallery</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach($portfolio->images as $image)
                <div class="rounded-xl overflow-hidden border border-brand-border bg-brand-surface">
                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption ?? $portfolio->title }}" class="w-full hover:scale-105 transition-transform duration-500">
                    @if($image->caption)
                    <div class="px-4 py-3 bg-brand-surface text-xs text-text-muted border-t border-brand-border">{{ $image->caption }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- CTA --}}
        <div class="bg-brand-surface border border-brand-border rounded-2xl p-8 lg:p-12 text-center reveal relative overflow-hidden shadow-elevated">
            <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-brand-orange via-accent to-brand-cyan opacity-40"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[200px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.08)_0%,transparent_70%)] pointer-events-none"></div>
            <h3 class="text-2xl font-display font-bold text-text-primary mb-3 relative z-10">Tertarik Membangun Sistem Serupa?</h3>
            <p class="text-text-muted text-base mb-8 relative z-10 max-w-lg mx-auto">Kami siap merancang dan mengimplementasikan sistem yang disesuaikan persis untuk alur kerja perusahaan Anda.</p>
            <a href="{{ \App\Services\WhatsAppService::getProjectUrl($portfolio->category->name ?? 'Project') }}" target="_blank" class="btn-gradient btn-lg magnetic relative z-10 px-8 py-3.5">
                Diskusikan Project
                <svg class="w-4 h-4 ml-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Related --}}
@if($relatedPortfolios->count())
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <h2 class="text-2xl lg:text-3xl font-display font-bold text-text-primary mb-8">Related Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPortfolios as $related)
            <a href="{{ route('portfolio.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @endif
                </div>
                <div class="p-6">
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-display font-semibold text-text-primary text-base mt-2 group-hover:text-accent-light transition-colors duration-300">{{ $related->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
