@extends('layouts.app')
@section('content')
@php
$pageTitle = $portfolio->meta_title ?? $portfolio->title . ' — Portfolio';
$metaDescription = $portfolio->meta_description ?? $portfolio->short_description;
@endphp

{{-- Hero --}}
<section class="section-padding pt-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('portfolio') }}" class="inline-flex items-center gap-1 text-sm text-accent hover:text-accent-light mb-8">
            ← Back to Portfolio
        </a>
        <div class="reveal">
            @if($portfolio->category)
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">{{ $portfolio->category->name }}</span>
            @endif
            <h1 class="text-display-sm text-text-primary mb-4">{{ $portfolio->title }}</h1>
            <p class="text-text-secondary text-body-lg">{{ $portfolio->short_description }}</p>
        </div>

        {{-- Meta Info --}}
        <div class="flex flex-wrap gap-6 mt-8 py-6 border-t border-b border-brand-border reveal reveal-delay-1">
            @if($portfolio->client)
            <div><span class="text-text-dark text-xs uppercase tracking-wider">Client</span><div class="text-text-primary text-sm mt-1">{{ $portfolio->client }}</div></div>
            @endif
            @if($portfolio->year)
            <div><span class="text-text-dark text-xs uppercase tracking-wider">Year</span><div class="text-text-primary text-sm mt-1">{{ $portfolio->year }}</div></div>
            @endif
            @if($portfolio->technologies && count($portfolio->technologies))
            <div>
                <span class="text-text-dark text-xs uppercase tracking-wider">Technologies</span>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    @foreach($portfolio->technologies as $tech)
                    <span class="text-xs px-2.5 py-0.5 bg-brand-surface border border-brand-border rounded-full text-text-muted">{{ $tech }}</span>
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
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 reveal">
        <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full rounded-2xl border border-brand-border">
    </div>
</section>
@endif

{{-- Content --}}
<section class="section-padding pt-0">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($portfolio->description)
        <div class="mb-12 reveal">
            <h2 class="text-heading-sm text-text-primary mb-4">Project Overview</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->description)) !!}</div>
        </div>
        @endif

        @if($portfolio->challenge)
        <div class="mb-12 reveal">
            <h2 class="text-heading-sm text-text-primary mb-4">The Challenge</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->challenge)) !!}</div>
        </div>
        @endif

        @if($portfolio->approach)
        <div class="mb-12 reveal">
            <h2 class="text-heading-sm text-text-primary mb-4">The Approach</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->approach)) !!}</div>
        </div>
        @endif

        @if($portfolio->solution)
        <div class="mb-12 reveal">
            <h2 class="text-heading-sm text-text-primary mb-4">The Solution</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->solution)) !!}</div>
        </div>
        @endif

        @if($portfolio->results)
        <div class="mb-12 reveal">
            <h2 class="text-heading-sm text-text-primary mb-4">The Result</h2>
            <div class="text-text-secondary leading-relaxed prose-dark">{!! nl2br(e($portfolio->results)) !!}</div>
        </div>
        @endif

        {{-- Gallery --}}
        @if($portfolio->images->count())
        <div class="mb-12 reveal">
            <h2 class="text-heading-sm text-text-primary mb-6">Gallery</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($portfolio->images as $image)
                <div class="rounded-xl overflow-hidden border border-brand-border">
                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption ?? $portfolio->title }}" class="w-full">
                    @if($image->caption)
                    <div class="px-4 py-2 bg-brand-surface text-xs text-text-muted">{{ $image->caption }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- CTA --}}
        <div class="bg-brand-surface border border-brand-border rounded-2xl p-8 text-center reveal">
            <h3 class="text-heading-sm text-text-primary mb-3">Saya Membutuhkan Sistem Seperti Ini</h3>
            <p class="text-text-muted text-sm mb-6">Diskusikan project Anda bersama kami.</p>
            <a href="{{ \App\Services\WhatsAppService::getProjectUrl($portfolio->category->name ?? 'Project') }}" target="_blank" class="btn-primary">
                Hubungi Kami →
            </a>
        </div>
    </div>
</section>

{{-- Related --}}
@if($relatedPortfolios->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-heading text-text-primary mb-8">Related Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPortfolios as $related)
            <a href="{{ route('portfolio.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-video bg-brand-surface-2">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                </div>
                <div class="p-5">
                    <span class="text-xs text-accent">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-semibold text-text-primary mt-1 group-hover:text-accent transition-colors">{{ $related->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
