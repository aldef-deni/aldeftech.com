@extends('layouts.app')

@php
    $pageTitle = $post->meta_title ?: $post->title . ' — Aldef Tech';
    $metaDescription = $post->meta_description ?: excerpt_text($post->excerpt ?: $post->content, 160);
    $ogImage = media_url($post->featured_image, 'images/aldef-tech-banner.png');
    $ogType = 'article';
    $canonical = $post->canonical_url ?: route('blog.show', $post->slug);

    $readMinutes = max(1, (int) ceil(str_word_count(strip_tags((string) $post->content)) / 200));
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $metaDescription,
    'image' => $ogImage,
    'datePublished' => optional($post->published_at)->toIso8601String(),
    'dateModified' => optional($post->updated_at)->toIso8601String(),
    'author' => ['@type' => 'Person', 'name' => $post->author->name ?? 'Aldef Tech'],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Aldef Tech',
        'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
    ],
    'mainEntityOfPage' => $canonical,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<x-page-hero
    :eyebrow="$post->category->name ?? __('site.common.insight')"
    :title="$post->title"
    align="left"
    compact
    :breadcrumbs="[
        ['label' => __('site.nav.blog'), 'url' => route('blog')],
        ['label' => $post->title],
    ]" />

{{-- ── Byline ───────────────────────────────────────────────────────────── --}}
<section class="surface-ivory-deep border-b border-line">
    <div class="shell">
        <div class="py-6 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-graphite-500 reveal">
            <span class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-full bg-gold-100 border border-gold-200 text-gold-700 font-display text-[0.6875rem] font-semibold flex items-center justify-center">
                    {{ initials_of($post->author->name ?? 'Aldef Tech') }}
                </span>
                <span class="text-graphite-700 font-medium">{{ $post->author->name ?? 'Aldef Tech' }}</span>
            </span>

            @if($post->published_at)
            <span class="flex items-center gap-2">
                <x-lux-icon name="clock" class="w-4 h-4 text-gold-600" />
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>
            </span>
            @endif

            <span class="tabular">{{ __('site.common.minutes_read', ['count' => $readMinutes]) }}</span>
        </div>
    </div>
</section>

{{-- ── Featured image ───────────────────────────────────────────────────── --}}
@if($src = media_url($post->featured_image))
<section class="surface-ivory pt-12 lg:pt-16">
    <div class="shell">
        <figure class="frame-lux reveal-scale max-w-4xl mx-auto">
            <img src="{{ $src }}" alt="{{ $post->title }}" class="!h-auto" decoding="async">
        </figure>
    </div>
</section>
@endif

{{-- ── Article ──────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">

            <article class="lg:col-span-8">
                <div class="prose-lux reveal">
                    {!! $post->content !!}
                </div>

                @if($post->tags && $post->tags->isNotEmpty())
                <div class="mt-12 pt-8 border-t border-line">
                    <p class="eyebrow mb-4">{{ __('pages.blog.detail.topics') }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <span class="chip chip-neutral">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-10">
                    <a href="{{ route('blog') }}" class="link-arrow">
                        <svg class="w-3.5 h-3.5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        <span>{{ __('pages.blog.detail.all_posts') }}</span>
                    </a>
                </div>
            </article>

            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-28 card-lux card-lux-featured p-6 lg:p-7 reveal">
                    <span class="icon-plate icon-plate-sm"><x-lux-icon name="spark" /></span>
                    <p class="mt-4 font-display text-base font-semibold text-graphite-900">
                        {{ __('pages.blog.detail.apply_title') }}
                    </p>
                    <p class="mt-2 text-[0.8125rem] leading-relaxed text-graphite-600">
                        {{ __('pages.blog.detail.apply_body') }}
                    </p>
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                       class="btn btn-primary btn-sm btn-block mt-5">
                        <span>{{ __('pages.blog.detail.consult') }}</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ── Related ──────────────────────────────────────────────────────────── --}}
@if($relatedPosts->isNotEmpty())
<section class="section-padding-sm surface-parchment border-t border-line">
    <div class="shell">
        <p class="eyebrow reveal">{{ __('pages.blog.detail.related') }}</p>

        <div class="mt-8 cards-swipe md:grid md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="80">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="card-lux reveal group overflow-hidden">
                <div class="frame-lux !rounded-none !border-0 !border-b !border-line aspect-[16/10] bg-ivory-200">
                    @if($rsrc = media_url($related->featured_image))
                        <img src="{{ $rsrc }}" alt="{{ $related->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-gold-400/50">
                            <x-lux-icon name="code" class="w-10 h-10" />
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    <p class="text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700">{{ $related->category->name ?? __('site.common.insight') }}</p>
                    <h3 class="mt-3 text-base leading-snug">{{ $related->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-cta-band />

@endsection
