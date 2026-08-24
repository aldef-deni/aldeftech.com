@extends('layouts.app')
@section('content')
@php $pageTitle = 'Portfolio — Aldef Tech'; @endphp

<section class="section-padding pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Portfolio</span>
            <h1 class="text-display-sm text-text-primary mb-6">Project Kami</h1>
            <p class="text-text-secondary text-body-lg">Lihat bagaimana kami membantu bisnis membangun solusi digital yang efektif.</p>
        </div>

        {{-- Category Filter --}}
        @if($categories->count())
        <div class="flex flex-wrap gap-2 mb-12 reveal" x-data="{ activeCategory: 'all' }">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-accent text-white' : 'bg-brand-surface border border-brand-border text-text-muted hover:text-text-primary'"
                    class="text-sm px-4 py-2 rounded-full transition-colors">All</button>
            @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat->slug }}'" :class="activeCategory === '{{ $cat->slug }}' ? 'bg-accent text-white' : 'bg-brand-surface border border-brand-border text-text-muted hover:text-text-primary'"
                    class="text-sm px-4 py-2 rounded-full transition-colors">{{ $cat->name }} <span class="text-xs opacity-60">({{ $cat->portfolios_count }})</span></button>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($portfolios as $portfolio)
            <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="aspect-video bg-brand-surface-2 overflow-hidden">
                    @if($portfolio->featured_image)
                    <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-lg">{{ $portfolio->category->name ?? 'Project' }}</div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs text-accent">{{ $portfolio->category->name ?? 'Project' }}</span>
                        @if($portfolio->client)
                        <span class="text-xs text-text-dark">· {{ $portfolio->client }}</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-text-primary mb-2 group-hover:text-accent transition-colors">{{ $portfolio->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2">{{ $portfolio->short_description }}</p>
                    @if($portfolio->technologies)
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach(array_slice($portfolio->technologies, 0, 3) as $tech)
                        <span class="text-xs px-2 py-0.5 bg-brand-surface-2 text-text-dark rounded-full">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-16 text-text-muted">Belum ada project yang dipublikasikan.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-padding bg-brand-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="text-heading text-text-primary mb-4">Ingin Project Seperti Ini?</h2>
        <p class="text-text-muted mb-8">Diskusikan kebutuhan Anda bersama kami.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary">Mulai Konsultasi →</a>
    </div>
</section>
@endsection
