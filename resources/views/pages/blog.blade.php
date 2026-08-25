@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Insights & Engineering Blog — Aldef Tech';
$metaDescription = 'Kumpulan artikel seputar arsitektur software, SaaS development, AI applications, dan strategi transformasi digital.';
@endphp

{{-- Hero Section --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">Engineering & Technology Insights</span>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Blog & Insights
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Analisa arsitektur software, panduan teknis SaaS, tren automasi AI, dan strategi digital untuk scale-up bisnis.
            </p>
        </div>
    </div>
</section>

{{-- Blog Grid Section --}}
<section class="section-padding bg-slate-50/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        {{-- Category Filters --}}
        @if($categories->count())
        <div class="flex flex-wrap items-center justify-center gap-2 mb-12 reveal">
            <a href="{{ route('blog') }}"
               class="text-xs font-semibold px-4 py-2 rounded-full border transition-all duration-200 {{ !request('category') ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-300' }}">
                Semua Artikel
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('blog') }}?category={{ $cat->slug }}"
               class="text-xs font-semibold px-4 py-2 rounded-full border transition-all duration-200 {{ request('category') === $cat->slug ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-300' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
        @endif

        {{-- Posts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group flex flex-col justify-between reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div>
                    {{-- Featured Image Container --}}
                    <div class="aspect-[16/10] bg-slate-100 overflow-hidden relative border-b border-slate-100">
                        @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-400">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Technology Insight</span>
                        </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-6 lg:p-7">
                        @if($post->category)
                        <span class="text-[0.6875rem] font-bold tracking-wider text-blue-600 uppercase">{{ $post->category->name }}</span>
                        @endif
                        
                        <h2 class="font-display font-bold text-slate-900 text-lg mt-2 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $post->title }}
                        </h2>
                        
                        <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed">
                            {{ $post->excerpt }}
                        </p>
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="px-6 lg:px-7 pb-6 pt-3 flex items-center justify-between text-xs text-slate-400 font-medium border-t border-slate-50">
                    <span>{{ $post->published_at->format('d M Y') }}</span>
                    @if($post->author)
                    <span class="text-slate-600 font-semibold">{{ $post->author->name }}</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-20 text-slate-500 bg-white rounded-2xl border border-slate-200">
                Belum ada artikel yang dipublikasikan.
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
        <div class="mt-14 text-center">
            {{ $posts->links() }}
        </div>
        @endif

    </div>
</section>

{{-- Striking Dark CTA Section --}}
<section class="py-20 lg:py-28 relative bg-[#090D16] text-white overflow-hidden">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Punya Rencana Proyek Digital?
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Konsultasikan ide Anda bersama software developer dan sistem arsitek berpengalaman kami.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-lg reveal reveal-delay-2">
            <span>Mulai Konsultasi Gratis</span>
            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
