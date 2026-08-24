@extends('layouts.app')
@section('content')
@php $pageTitle = 'Insights — Aldef Tech'; @endphp

<section class="section-padding pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Insights</span>
            <h1 class="text-display-sm text-text-primary mb-6">Blog & Insights</h1>
            <p class="text-text-secondary text-body-lg">Artikel seputar teknologi, software development, digital transformation, dan AI.</p>
        </div>

        @if($categories->count())
        <div class="flex flex-wrap gap-2 mb-12 reveal">
            <a href="{{ route('blog') }}" class="text-sm px-4 py-2 rounded-full {{ !request('category') ? 'bg-accent text-white' : 'bg-brand-surface border border-brand-border text-text-muted hover:text-text-primary' }} transition-colors">All</a>
            @foreach($categories as $cat)
            <a href="{{ route('blog') }}?category={{ $cat->slug }}" class="text-sm px-4 py-2 rounded-full {{ request('category') === $cat->slug ? 'bg-accent text-white' : 'bg-brand-surface border border-brand-border text-text-muted hover:text-text-primary' }} transition-colors">{{ $cat->name }}</a>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="aspect-video bg-brand-surface-2 overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-sm">Article</div>
                    @endif
                </div>
                <div class="p-6">
                    @if($post->category)
                    <span class="text-xs text-accent">{{ $post->category->name }}</span>
                    @endif
                    <h3 class="font-semibold text-text-primary mt-2 mb-2 group-hover:text-accent transition-colors">{{ $post->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2">{{ $post->excerpt }}</p>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-xs text-text-dark">{{ $post->published_at->format('d M Y') }}</span>
                        @if($post->author)
                        <span class="text-xs text-text-dark">by {{ $post->author->name }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-16 text-text-muted">Belum ada artikel yang dipublikasikan.</div>
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
