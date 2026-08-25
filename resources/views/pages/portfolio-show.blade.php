@extends('layouts.app')
@section('content')
@php
$pageTitle = $portfolio->meta_title ?? $portfolio->title . ' — Case Study | Aldef Tech';
$metaDescription = $portfolio->meta_description ?? $portfolio->short_description;
@endphp

{{-- Case Study Hero --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <a href="{{ route('portfolio') }}" class="btn-link mb-8 reveal inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/></svg>
            Kembali ke Semua Project
        </a>

        <div class="reveal reveal-delay-1">
            @if($portfolio->category)
            <span class="section-eyebrow mb-3">{{ $portfolio->category->name }}</span>
            @endif
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 mb-6 leading-tight tracking-tight">
                {{ $portfolio->title }}
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed">
                {{ $portfolio->short_description }}
            </p>
        </div>

        {{-- Meta Badges --}}
        <div class="flex flex-wrap items-center gap-8 mt-10 py-7 border-t border-b border-slate-200/80 reveal reveal-delay-2">
            @if($portfolio->client)
            <div>
                <span class="text-slate-400 text-[0.6875rem] uppercase tracking-widest font-bold">Client</span>
                <div class="text-slate-900 text-sm font-semibold mt-1">{{ $portfolio->client }}</div>
            </div>
            @endif
            @if($portfolio->year)
            <div>
                <span class="text-slate-400 text-[0.6875rem] uppercase tracking-widest font-bold">Timeline</span>
                <div class="text-slate-900 text-sm font-semibold mt-1">{{ $portfolio->year }}</div>
            </div>
            @endif
            @if($portfolio->technologies && count($portfolio->technologies))
            <div>
                <span class="text-slate-400 text-[0.6875rem] uppercase tracking-widest font-bold">Tech Stack</span>
                <div class="flex flex-wrap gap-1.5 mt-1.5">
                    @foreach($portfolio->technologies as $tech)
                    <span class="tag tag-accent text-xs">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Featured Hero Image --}}
@if($portfolio->featured_image)
<section class="py-12 bg-slate-50/80">
    <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10 reveal-scale">
        <div class="rounded-2xl overflow-hidden border border-slate-200/90 shadow-elevated bg-white">
            <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full">
        </div>
    </div>
</section>
@endif

{{-- Structured Case Study Content --}}
<section class="section-padding bg-white pt-8">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10">
        
        @if($portfolio->description)
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-slate-900 mb-4">Project Overview</h2>
            <div class="text-slate-700 leading-relaxed prose-light text-base lg:text-lg">
                {!! nl2br(e($portfolio->description)) !!}
            </div>
        </div>
        @endif

        {{-- Modular Cards for Challenge, Approach, Solution, Impact --}}
        <div class="space-y-8 mb-14">
            @if($portfolio->challenge)
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 reveal">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold">01</span>
                    <h3 class="text-xl font-display font-bold text-slate-900">The Challenge</h3>
                </div>
                <div class="text-slate-700 text-base leading-relaxed pl-11">
                    {!! nl2br(e($portfolio->challenge)) !!}
                </div>
            </div>
            @endif

            @if($portfolio->approach)
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 reveal">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">02</span>
                    <h3 class="text-xl font-display font-bold text-slate-900">The Architectural Approach</h3>
                </div>
                <div class="text-slate-700 text-base leading-relaxed pl-11">
                    {!! nl2br(e($portfolio->approach)) !!}
                </div>
            </div>
            @endif

            @if($portfolio->solution)
            <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 reveal">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold">03</span>
                    <h3 class="text-xl font-display font-bold text-slate-900">The Implemented Solution</h3>
                </div>
                <div class="text-slate-700 text-base leading-relaxed pl-11">
                    {!! nl2br(e($portfolio->solution)) !!}
                </div>
            </div>
            @endif

            @if($portfolio->results)
            <div class="p-8 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 reveal">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">04</span>
                    <h3 class="text-xl font-display font-bold text-slate-900">Business Impact & Results</h3>
                </div>
                <div class="text-slate-700 text-base leading-relaxed pl-11">
                    {!! nl2br(e($portfolio->results)) !!}
                </div>
            </div>
            @endif
        </div>

        {{-- Gallery Grid --}}
        @if($portfolio->images->count())
        <div class="mb-14 reveal">
            <h2 class="text-2xl font-display font-bold text-slate-900 mb-6">Gallery & Mockups</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach($portfolio->images as $image)
                <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-50 shadow-2xs">
                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption ?? $portfolio->title }}" class="w-full hover:scale-105 transition-transform duration-300">
                    @if($image->caption)
                    <div class="px-4 py-3 bg-white text-xs text-slate-600 border-t border-slate-100 font-medium">{{ $image->caption }}</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

{{-- Related Portfolios --}}
@if($relatedPortfolios->count())
<section class="section-padding bg-slate-50/80 border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <h2 class="text-2xl lg:text-3xl font-display font-bold text-slate-900 mb-8">Related Case Studies</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPortfolios as $related)
            <a href="{{ route('portfolio.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-[16/10] bg-slate-100">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @endif
                </div>
                <div class="p-6">
                    <span class="text-[0.6875rem] font-bold tracking-wider text-blue-600 uppercase">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-display font-bold text-slate-900 text-base mt-2 group-hover:text-blue-600 transition-colors">{{ $related->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Striking Dark CTA Section --}}
<section class="py-20 lg:py-28 relative bg-[#090D16] text-white overflow-hidden">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Tertarik Membangun Sistem Serupa?
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Konsultasikan alur kerja dan fitur yang Anda inginkan bersama lead software architect Aldef Tech.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getProjectUrl($portfolio->category->name ?? 'Project') }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-lg reveal reveal-delay-2">
            <span>Diskusikan Project Anda</span>
            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
