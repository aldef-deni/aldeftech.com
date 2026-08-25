@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Portfolio & Case Studies — Aldef Tech';
$metaDescription = 'Lihat ragam proyek software, web application, SaaS, dan automasi AI yang telah kami bangun untuk klien dan industri.';
@endphp

{{-- Hero Section --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">Our Portfolio</span>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Karya & Studi Kasus Kami
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Lihat bagaimana rekayasa perangkat lunak dan automasi cerdas kami membantu efisiensi nyata pada berbagai lini bisnis.
            </p>
        </div>
    </div>
</section>

{{-- Portfolio Showcase --}}
<section class="section-padding bg-slate-50/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        {{-- Category Filters --}}
        @if($categories->count())
        <div class="flex flex-wrap items-center justify-center gap-2 mb-12 reveal">
            <a href="{{ route('portfolio') }}"
               class="text-xs font-semibold px-4 py-2 rounded-full border transition-all duration-200 {{ !request('category') ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-300' }}">
                Semua Project
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('portfolio') }}?category={{ $cat->slug }}"
               class="text-xs font-semibold px-4 py-2 rounded-full border transition-all duration-200 {{ request('category') === $cat->slug ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-300' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
        @endif

        {{-- Projects Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse($portfolios as $portfolio)
            <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="premium-card overflow-hidden group flex flex-col justify-between reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div>
                    {{-- Image Container --}}
                    <div class="aspect-[16/10] bg-slate-100 overflow-hidden relative border-b border-slate-100">
                        @if($portfolio->featured_image)
                        <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-400 p-6 text-center">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 mb-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">{{ $portfolio->category->name ?? 'Software System' }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="p-6 lg:p-7">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[0.6875rem] font-bold tracking-wider text-blue-600 uppercase">{{ $portfolio->category->name ?? 'Project' }}</span>
                            @if($portfolio->client)
                            <span class="text-xs text-slate-400">• {{ $portfolio->client }}</span>
                            @endif
                        </div>

                        <h3 class="font-display font-bold text-slate-900 text-lg mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $portfolio->title }}
                        </h3>

                        <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed mb-4">
                            {{ $portfolio->short_description }}
                        </p>

                        @if($portfolio->technologies)
                        <div class="flex flex-wrap gap-1.5 mt-4">
                            @foreach(array_slice($portfolio->technologies, 0, 3) as $tech)
                            <span class="tag text-[0.6875rem]">{{ $tech }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="px-6 lg:px-7 pb-6 pt-2 flex items-center justify-between text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                    <span>View Case Study</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-20 text-slate-500 bg-white rounded-2xl border border-slate-200">
                Belum ada project yang dipublikasikan.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Striking Dark CTA Section --}}
<section class="py-20 lg:py-28 relative bg-[#090D16] text-white overflow-hidden">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Ingin Membangun Solusi Seperti Ini?
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Mari wujudkan aplikasi dan sistem digital berkinerja tinggi bersama tim engineer kami.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-lg reveal reveal-delay-2">
            <span>Mulai Konsultasi Project</span>
            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
