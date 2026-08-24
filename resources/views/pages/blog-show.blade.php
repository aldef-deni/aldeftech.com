@extends('layouts.app')
@section('content')
@php
$pageTitle = $post->meta_title ?? $post->title;
$metaDescription = $post->meta_description ?? $post->excerpt;
@endphp

<article class="section-padding pt-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('blog') }}" class="inline-flex items-center gap-1 text-sm text-accent hover:text-accent-light mb-8">← Back to Insights</a>

        <div class="reveal">
            @if($post->category)
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-display-sm text-text-primary mb-4">{{ $post->title }}</h1>
            <div class="flex items-center gap-4 text-sm text-text-muted mb-8">
                <span>{{ $post->published_at->format('d M Y') }}</span>
                @if($post->author)
                <span>by {{ $post->author->name }}</span>
                @endif
            </div>
        </div>

        @if($post->featured_image)
        <div class="mb-10 reveal">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full rounded-2xl border border-brand-border">
        </div>
        @endif

        <div class="prose-dark leading-relaxed text-body-lg reveal">
            {!! $post->content !!}
        </div>

        @if($post->tags->count())
        <div class="mt-8 pt-6 border-t border-brand-border reveal">
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <span class="text-xs px-3 py-1 bg-brand-surface border border-brand-border rounded-full text-text-muted">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</article>

@if($relatedPosts->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-heading text-text-primary mb-8">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-video bg-brand-surface-2">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                </div>
                <div class="p-5">
                    <span class="text-xs text-accent">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-semibold text-text-primary mt-1 group-hover:text-accent transition-colors">{{ $related->title }}</h3>
                    <span class="text-xs text-text-dark mt-2 block">{{ $related->published_at->format('d M Y') }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
