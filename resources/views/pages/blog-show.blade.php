@extends('layouts.app')
@section('content')
@php
$pageTitle = $post->meta_title ?? $post->title;
$metaDescription = $post->meta_description ?? $post->excerpt;
@endphp

<article class="section-padding pt-20">
    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        <a href="{{ route('blog') }}" class="btn-link mb-10 reveal">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/></svg>
            Back to Insights
        </a>

        <div class="reveal reveal-delay-1">
            @if($post->category)
            <span class="section-eyebrow">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-display-sm font-display text-text-primary mb-5 leading-tight">{{ $post->title }}</h1>
            <div class="flex items-center gap-4 text-sm text-text-muted">
                <span>{{ $post->published_at->format('d M Y') }}</span>
                @if($post->author)
                <span class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-accent/15 border border-accent/20 flex items-center justify-center text-accent text-[0.625rem] font-display font-bold">{{ substr($post->author->name, 0, 1) }}</span>
                    {{ $post->author->name }}
                </span>
                @endif
            </div>
        </div>
    </div>

    @if($post->featured_image)
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 mt-10 mb-12 reveal-scale">
        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full rounded-2xl border border-brand-border">
    </div>
    @endif

    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="prose-dark leading-relaxed text-body-lg reveal">
            {!! $post->content !!}
        </div>

        @if($post->tags->count())
        <div class="mt-10 pt-7 border-t border-brand-border reveal">
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <span class="tag tag-accent">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</article>

@if($relatedPosts->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <h2 class="text-heading font-display text-text-primary mb-8">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-[16/10] bg-brand-surface-2">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @endif
                </div>
                <div class="p-5">
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-display font-semibold text-text-primary mt-2 group-hover:text-accent-light transition-colors duration-300">{{ $related->title }}</h3>
                    <span class="text-xs text-text-dark mt-2 block">{{ $related->published_at->format('d M Y') }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
