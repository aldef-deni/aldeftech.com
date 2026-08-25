@extends('layouts.app')
@section('content')
@php
$pageTitle = $post->meta_title ?? $post->title . ' — Aldef Tech Insights';
$metaDescription = $post->meta_description ?? $post->excerpt;
@endphp

<article class="section-padding bg-white pt-16 lg:pt-24 relative overflow-hidden">
    {{-- Header --}}
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <a href="{{ route('blog') }}" class="btn-link mb-8 reveal inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7l-7-7 7-7"/></svg>
            Kembali ke Insights
        </a>

        <div class="reveal reveal-delay-1">
            @if($post->category)
            <span class="section-eyebrow mb-3">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 mb-6 leading-tight tracking-tight">
                {{ $post->title }}
            </h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 pb-8 border-b border-slate-200">
                <span class="font-medium">{{ $post->published_at->format('d M Y') }}</span>
                <span>•</span>
                @if($post->author)
                <span class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-display font-bold">
                        {{ substr($post->author->name, 0, 1) }}
                    </span>
                    <span class="text-slate-800 font-semibold">{{ $post->author->name }}</span>
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Featured Image --}}
    @if($post->featured_image)
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 mt-10 mb-12 reveal-scale">
        <div class="rounded-2xl overflow-hidden border border-slate-200/90 shadow-card bg-slate-50">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full">
        </div>
    </div>
    @endif

    {{-- Body Content --}}
    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="prose-light leading-relaxed text-base lg:text-lg reveal">
            {!! $post->content !!}
        </div>

        {{-- Tags --}}
        @if($post->tags->count())
        <div class="mt-12 pt-8 border-t border-slate-200 reveal">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Topic Tags</div>
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <span class="tag tag-accent text-xs">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</article>

{{-- Related Articles --}}
@if($relatedPosts->count())
<section class="section-padding bg-slate-50/80 border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <h2 class="text-2xl lg:text-3xl font-display font-bold text-slate-900 mb-8">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="premium-card overflow-hidden group">
                <div class="aspect-[16/10] bg-slate-100">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @endif
                </div>
                <div class="p-6">
                    <span class="text-[0.6875rem] font-bold tracking-wider text-blue-600 uppercase">{{ $related->category->name ?? '' }}</span>
                    <h3 class="font-display font-bold text-slate-900 text-base mt-2 group-hover:text-blue-600 transition-colors">{{ $related->title }}</h3>
                    <span class="text-xs text-slate-400 mt-3 block font-medium">{{ $related->published_at->format('d M Y') }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Striking Dark CTA Section --}}
<section class="py-20 lg:py-28 relative bg-[#090D16] text-white overflow-hidden">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Ingin Mengimplementasikan Solusi Serupa?
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Hubungi tim Aldef Tech untuk eksplorasi arsitektur dan estimasi pengembangan proyek Anda.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-lg reveal reveal-delay-2">
            <span>Mulai Konsultasi Gratis</span>
            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
