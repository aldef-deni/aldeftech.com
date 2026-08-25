@extends('layouts.app')
@section('content')
@php $pageTitle = 'Portfolio — Aldef Tech'; @endphp

<section class="section-padding pt-24 lg:pt-32 relative overflow-hidden">
    <div class="hero-orb hero-orb-1 opacity-50"></div>
    <div class="hero-orb hero-orb-2 opacity-50"></div>
    <div class="absolute inset-0 hero-grid opacity-30"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Portfolio</span>
            <h1 class="text-4xl sm:text-5xl lg:text-display-sm font-display text-gradient-hero mb-6 reveal reveal-delay-1">Project & Karya Kami</h1>
            <p class="text-text-secondary text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">Lihat bagaimana kami membantu bisnis mentransformasi operasional melalui solusi digital yang teruji, andal, dan berkinerja tinggi.</p>
        </div>

        {{-- Category Filter --}}
        @if($categories->count())
        <div class="flex flex-wrap gap-2.5 mb-12 reveal" x-data="{ activeCategory: 'all' }">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-gradient-to-r from-accent to-accent-dark text-white border-accent shadow-[0_0_15px_rgba(168,85,247,0.3)]' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light'"
                    class="text-sm px-5 py-2.5 rounded-xl border transition-all duration-300 font-medium">All Projects</button>
            @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat->slug }}'" :class="activeCategory === '{{ $cat->slug }}' ? 'bg-gradient-to-r from-accent to-accent-dark text-white border-accent shadow-[0_0_15px_rgba(168,85,247,0.3)]' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light'"
                    class="text-sm px-5 py-2.5 rounded-xl border transition-all duration-300 font-medium">{{ $cat->name }} <span class="text-xs opacity-60">({{ $cat->portfolios_count }})</span></button>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($portfolios as $portfolio)
            <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden relative">
                    @if($portfolio->featured_image)
                    <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-surface-2 to-brand-surface-3">
                        <div class="text-center">
                            <div class="w-14 h-14 rounded-xl bg-accent/5 border border-accent/10 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-accent/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-text-dark text-xs font-medium">{{ $portfolio->category->name ?? 'Project' }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-bg/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <div class="p-6 lg:p-7">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $portfolio->category->name ?? 'Project' }}</span>
                        @if($portfolio->client)
                        <span class="text-xs text-text-dark">· {{ $portfolio->client }}</span>
                        @endif
                    </div>
                    <h3 class="font-display font-semibold text-text-primary text-lg mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $portfolio->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2 leading-relaxed">{{ $portfolio->short_description }}</p>
                    @if($portfolio->technologies)
                    <div class="flex flex-wrap gap-1.5 mt-5">
                        @foreach(array_slice($portfolio->technologies, 0, 3) as $tech)
                        <span class="tag text-[0.625rem]">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </a>
            @endempty
            <div class="col-span-full text-center py-20 text-text-muted">Belum ada project yang dipublikasikan.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-padding bg-brand-surface relative overflow-hidden">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-4 reveal">Ingin Project Seperti Ini?</h2>
        <p class="text-text-muted text-lg mb-8 reveal reveal-delay-1">Diskusikan kebutuhan spesifik Anda bersama tim engineer Aldef Tech.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-gradient btn-lg magnetic reveal reveal-delay-2 px-10 py-4">Mulai Konsultasi →</a>
    </div>
</section>
@endsection
