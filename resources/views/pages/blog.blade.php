@extends('layouts.app')
@section('content')
@php $pageTitle = 'Insights — Aldef Tech'; @endphp

<section class="section-padding pt-24 lg:pt-32 relative overflow-hidden">
    <div class="hero-orb hero-orb-1 opacity-50"></div>
    <div class="hero-orb hero-orb-2 opacity-50"></div>
    <div class="absolute inset-0 hero-grid opacity-30"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Insights</span>
            <h1 class="text-4xl sm:text-5xl lg:text-display-sm font-display text-gradient-hero mb-6 reveal reveal-delay-1">Blog & Insights</h1>
            <p class="text-text-secondary text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">Artikel, analisa arsitektur, panduan software engineering, transformasi digital, dan inovasi AI terkini.</p>
        </div>

        @if($categories->count())
        <div class="flex flex-wrap gap-2.5 mb-12 reveal">
            <a href="{{ route('blog') }}" class="text-sm px-5 py-2.5 rounded-xl border transition-all duration-300 font-medium {{ !request('category') ? 'bg-gradient-to-r from-accent to-accent-dark text-white border-accent shadow-[0_0_15px_rgba(168,85,247,0.3)]' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light' }}">All Articles</a>
            @foreach($categories as $cat)
            <a href="{{ route('blog') }}?category={{ $cat->slug }}" class="text-sm px-5 py-2.5 rounded-xl border transition-all duration-300 font-medium {{ request('category') === $cat->slug ? 'bg-gradient-to-r from-accent to-accent-dark text-white border-accent shadow-[0_0_15px_rgba(168,85,247,0.3)]' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden relative">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-surface-2 to-brand-surface-3">
                        <svg class="w-8 h-8 text-text-dark/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="p-6 lg:p-7">
                    @if($post->category)
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $post->category->name }}</span>
                    @endif
                    <h3 class="font-display font-semibold text-text-primary text-lg mt-2.5 mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $post->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-brand-border/60">
                        <span class="text-xs text-text-dark font-medium">{{ $post->published_at->format('d M Y') }}</span>
                        @if($post->author)
                        <span class="text-xs text-text-muted font-medium">by {{ $post->author->name }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20 text-text-muted">Belum ada artikel yang dipublikasikan.</div>
            @endforelse
        </div>

        @if($posts->hasPages())
        <div class="mt-14 text-center">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
