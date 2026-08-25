@extends('layouts.app')
@section('content')
@php $pageTitle = 'Portfolio — Aldef Tech'; @endphp

<section class="section-padding pt-20 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Portfolio</span>
            <h1 class="text-display-sm font-display text-text-primary mb-6 reveal reveal-delay-1">Project Kami</h1>
            <p class="text-text-secondary text-body-lg leading-relaxed reveal reveal-delay-2">Lihat bagaimana kami membantu bisnis membangun solusi digital yang efektif.</p>
        </div>

        {{-- Category Filter --}}
        @if($categories->count())
        <div class="flex flex-wrap gap-2 mb-12 reveal" x-data="{ activeCategory: 'all' }">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-accent text-white border-accent' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light'"
                    class="text-sm px-4 py-2 rounded-full border transition-all duration-200 font-medium">All</button>
            @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat->slug }}'" :class="activeCategory === '{{ $cat->slug }}' ? 'bg-accent text-white border-accent' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light'"
                    class="text-sm px-4 py-2 rounded-full border transition-all duration-200 font-medium">{{ $cat->name }} <span class="text-xs opacity-60">({{ $cat->portfolios_count }})</span></button>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($portfolios as $portfolio)
            <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden relative">
                    @if($portfolio->featured_image)
                    <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-sm font-medium">{{ $portfolio->category->name ?? 'Project' }}</div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-surface/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $portfolio->category->name ?? 'Project' }}</span>
                        @if($portfolio->client)
                        <span class="text-xs text-text-dark">· {{ $portfolio->client }}</span>
                        @endif
                    </div>
                    <h3 class="font-display font-semibold text-text-primary mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $portfolio->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2 leading-relaxed">{{ $portfolio->short_description }}</p>
                    @if($portfolio->technologies)
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        @foreach(array_slice($portfolio->technologies, 0, 3) as $tech)
                        <span class="tag text-[0.625rem]">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20 text-text-muted">Belum ada project yang dipublikasikan.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-padding bg-brand-surface relative overflow-hidden">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-heading font-display text-text-primary mb-4 reveal">Ingin Project Seperti Ini?</h2>
        <p class="text-text-muted mb-8 reveal reveal-delay-1">Diskusikan kebutuhan Anda bersama kami.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary magnetic reveal reveal-delay-2">Mulai Konsultasi →</a>
    </div>
</section>
@endsection
