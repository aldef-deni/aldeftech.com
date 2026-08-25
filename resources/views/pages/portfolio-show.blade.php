@extends('layouts.app')
@section('content')
@php
$pageTitle = $portfolio->meta_title ?? $portfolio->title . ' — Portfolio';
$metaDescription = $portfolio->meta_description ?? $portfolio->short_description;
@endphp

{{-- Hero --}}
<section class="section-padding pt-20 relative">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10">
        <a href="{{ route('portfolio') }}" class="btn-link mb-10 reveal">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/></svg>
            Back to Portfolio
        </a>

        <div class="reveal reveal-delay-1">
            @if($portfolio->category)
            <span class="section-eyebrow">{{ $portfolio->category->name }}</span>
            @endif
            <h1 class="text-display-sm font-display text-text-primary mb-5">{{ $portfolio->title }}</h1>
            <p class="text-text-secondary text-body-lg">{{ $portfolio->short_description }}</p>
        </div>

        {{-- Meta Info --}}
        <div class="flex flex-wrap gap-8 mt-10 py-7 border-t border-b border-brand-border reveal reveal-delay-2">
            @if($portfolio->client)
            <div>
                <span class="text-text-dark text-[0.6875rem] uppercase tracking-[0.15em] font-medium">Client</span>
                <div class="text-text-primary text-sm font-medium mt-1.5">{{ $portfolio->client }}</div>
            </div>
            @endif
            @if($portfolio->year)
            <div>
                <span class="text-text-dark text-[0.6875rem] uppercase tracking-[0.15em] font-medium">Year</span>
                <div class="text-text-primary text-sm font-medium mt-1.5">{{ $portfolio->year }}</div>
            </div>
            @endif
            @if($portfolio->technologies && count($portfolio->technologies))
            <div>
                <span class="text-text-dark text-[0.6875rem] uppercase tracking-[0.15em] font-medium">Technologies</span>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    @foreach($portfolio->technologies as $tech)
                    <span class="tag">{{ $tech }}</span>
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
        <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full rounded-2xl border border-brand-border">
    </div>
</section>
@endif

{{-- Content --}}
<section class="section-padding pt-0">
    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        @if($portfolio->description)
        <div class="mb-14 reveal">
            <h2 class="text-heading-sm font-display text-text-primary mb-4">Project Overview</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->description)) !!}</div>
        </div>
        @endif

        @if($portfolio->challenge)
        <div class="mb-14 reveal">
            <h2 class="text-heading-sm font-display text-text-primary mb-4">The Challenge</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->challenge)) !!}</div>
        </div>
        @endif

        @if($portfolio->approach)
        <div class="mb-14 reveal">
            <h2 class="text-heading-sm font-display text-text-primary mb-4">The Approach</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->approach)) !!}</div>
        </div>
        @endif

        @if($portfolio->solution)
        <div class="mb-14 reveal">
            <h2 class="text-heading-sm font-display text-text-primary mb-4">The Solution</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->solution)) !!}</div>
        </div>
        @endif

        @if($portfolio->results)
        <div class="mb-14 reveal">
            <h2 class="text-heading-sm font-display text-text-primary mb-4">The Result</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->results)) !!}</div>
        </div>
        @endif

        {{-- Gallery --}}
        @if($portfolio->images->count())
        <div class="mb-14 reveal">
            <h2 class="text-heading-sm font-display text-text-primary mb-6">Gallery</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($portfolio->images as $image)
                <div class="rounded-xl overflow-hidden border border-brand-border">
                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption ?? $portfolio->title }}" class="w-full">
                    @if($image->caption)
                    <div class="px-4 py-3 bg-brand-surface text-xs text-text-muted">{{ $image->caption }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- CTA --}}
        <div class="bg-brand-surface border border-brand-border rounded-2xl p-8 lg:p-10 text-center reveal relative overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[150px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.06)_0%,transparent_70%)] pointer-events-none"></div>
            <h3 class="text-heading-sm font-display text-text-primary mb-3 relative z-10">Saya Membutuhkan Sistem Seperti Ini</h3>
            <p class="text-text-muted text-sm mb-6 relative z-10">Diskusikan project Anda bersama kami.</p>
            <a href="{{ \App\Services\WhatsAppService::getProjectUrl($portfolio->category->name ?? 'Project') }}" target="_blank" class="btn-primary magnetic relative z-10">
                Hubungi Kami
                <svg class="w-4 h-4 ml-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Related --}}
@if($relatedPortfolios->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <h2 class="text-heading font-display text-text-primary mb-8">Related Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($relatedPortfolios as $related)
            <a href="{{ route('portfolio.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-[16/10] bg-brand-surface-2">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @endif
                </div>
                <div class="p-5">
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-display font-semibold text-text-primary mt-2 group-hover:text-accent-light transition-colors duration-300">{{ $related->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
