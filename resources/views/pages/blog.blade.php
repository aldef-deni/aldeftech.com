@extends('layouts.app')
@section('content')
@php $pageTitle = 'Insights — Aldef Tech'; @endphp

<section class="section-padding pt-20 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Insights</span>
            <h1 class="text-display-sm font-display text-text-primary mb-6 reveal reveal-delay-1">Blog & Insights</h1>
            <p class="text-text-secondary text-body-lg leading-relaxed reveal reveal-delay-2">Artikel seputar teknologi, software development, digital transformation, dan AI.</p>
        </div>

        @if($categories->count())
        <div class="flex flex-wrap gap-2 mb-12 reveal">
            <a href="{{ route('blog') }}" class="text-sm px-4 py-2 rounded-full border transition-all duration-200 font-medium {{ !request('category') ? 'bg-accent text-white border-accent' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light' }}">All</a>
            @foreach($categories as $cat)
            <a href="{{ route('blog') }}?category={{ $cat->slug }}" class="text-sm px-4 py-2 rounded-full border transition-all duration-200 font-medium {{ request('category') === $cat->slug ? 'bg-accent text-white border-accent' : 'bg-brand-surface-2 border-brand-border text-text-muted hover:text-text-primary hover:border-brand-border-light' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-sm">Article</div>
                    @endif
                </div>
                <div class="p-6">
                    @if($post->category)
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $post->category->name }}</span>
                    @endif
                    <h3 class="font-display font-semibold text-text-primary mt-2.5 mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $post->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-xs text-text-dark">{{ $post->published_at->format('d M Y') }}</span>
                        @if($post->author)
                        <span class="text-xs text-text-dark">by {{ $post->author->name }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20 text-text-muted">Belum ada artikel yang dipublikasikan.</div>
            @endforelse
        </div>

        @if($posts->hasPages())
        <div class="mt-12 text-center">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
