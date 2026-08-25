@extends('layouts.app')
@section('content')
@php
$pageTitle = $post->meta_title ?? $post->title;
$metaDescription = $post->meta_description ?? $post->excerpt;
@endphp

<article class="section-padding pt-24 lg:pt-32 relative overflow-hidden">
    <div class="hero-orb hero-orb-1 opacity-30"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <a href="{{ route('blog') }}" class="btn-link mb-10 reveal inline-flex items-center gap-2 text-sm text-text-muted hover:text-accent-light">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/></svg>
            Kembali ke Insights
        </a>

        <div class="reveal reveal-delay-1">
            @if($post->category)
            <span class="section-eyebrow">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-3xl sm:text-4xl lg:text-display-sm font-display text-gradient-hero mb-6 leading-tight">{{ $post->title }}</h1>
            <div class="flex items-center gap-4 text-sm text-text-muted pb-8 border-b border-brand-border">
                <span>{{ $post->published_at->format('d M Y') }}</span>
                <span>·</span>
                @if($post->author)
                <span class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-gradient-to-br from-accent/20 to-brand-cyan/10 border border-accent/20 flex items-center justify-center text-accent text-xs font-display font-bold">{{ substr($post->author->name, 0, 1) }}</span>
                    <span class="text-text-secondary font-medium">{{ $post->author->name }}</span>
                </span>
                @endif
            </div>
        </div>
    </div>

    @if($post->featured_image)
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 mt-10 mb-12 reveal-scale">
        <div class="rounded-2xl overflow-hidden border border-brand-border shadow-elevated">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full">
        </div>
    </div>
    @endif

    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="prose-dark leading-relaxed text-lg reveal">
            {!! $post->content !!}
        </div>

        @if($post->tags->count())
        <div class="mt-12 pt-8 border-t border-brand-border reveal">
            <div class="text-xs font-semibold text-text-dark uppercase tracking-wider mb-3">Tags</div>
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <span class="tag tag-accent text-xs">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</article>

@if($relatedPosts->count())
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <h2 class="text-2xl lg:text-3xl font-display font-bold text-text-primary mb-8">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @endif
                </div>
                <div class="p-6">
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-display font-semibold text-text-primary text-base mt-2 group-hover:text-accent-light transition-colors duration-300">{{ $related->title }}</h3>
                    <span class="text-xs text-text-dark mt-3 block font-medium">{{ $related->published_at->format('d M Y') }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
