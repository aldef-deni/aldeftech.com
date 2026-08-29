@extends('layouts.app')

@php
    $pageTitle = __('pages.blog.meta_title');
    $metaDescription = __('pages.blog.meta_description');
@endphp

@section('content')

<x-page-hero
    :eyebrow="__('pages.blog.eyebrow')"
    :title="__('pages.blog.title')"
    :accent="__('pages.blog.accent')"
    :lead="__('pages.blog.lead')"
    :breadcrumbs="[['label' => __('site.nav.blog')]]" />

<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">

            {{-- Posts --}}
            <div class="lg:col-span-8">
                @if($posts->isEmpty())
                    <div class="card-lux p-12 text-center reveal">
                        <span class="icon-plate mx-auto"><x-lux-icon name="code" /></span>
                        <h2 class="mt-6 text-xl">{{ __('pages.blog.empty_title') }}</h2>
                        <p class="mt-3 text-sm text-graphite-600 max-w-md mx-auto leading-relaxed">
                            {{ __('pages.blog.empty_body') }}
                        </p>
                        <a href="{{ lroute('portfolio') }}" class="btn btn-outline mt-7">
                            <span>{{ __('pages.blog.empty_cta') }}</span>
                            <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                @else
                    <div class="cards-swipe md:grid md:grid-cols-2 gap-5 lg:gap-6" data-reveal-group="80">
                        @foreach($posts as $post)
                        <article class="reveal">
                            <a href="{{ lroute('blog.show', $post->slug) }}" class="card-lux group h-full overflow-hidden">
                                <div class="frame-lux !rounded-none !border-0 !border-b !border-line aspect-[16/10] bg-ivory-200">
                                    @if($src = media_url($post->featured_image))
                                        <img src="{{ $src }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                                    @else
                                        <span class="absolute inset-0 flex items-center justify-center text-gold-400/50">
                                            <x-lux-icon name="code" class="w-10 h-10" />
                                        </span>
                                    @endif
                                </div>

                                <div class="p-6 lg:p-7 flex-1 flex flex-col">
                                    <div class="flex items-center gap-2.5 text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700">
                                        <span>{{ $post->category->name ?? __('site.common.insight') }}</span>
                                        @if($post->published_at)
                                            <span class="w-1 h-1 rounded-full bg-gold-400" aria-hidden="true"></span>
                                            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d M Y') }}</time>
                                        @endif
                                    </div>

                                    <h2 class="mt-3.5 text-lg leading-snug">{{ $post->title }}</h2>

                                    <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                                        {{ excerpt_text($post->excerpt ?: $post->content, 130) }}
                                    </p>

                                    <span class="mt-auto pt-6 link-arrow">
                                        <span>{{ __('site.common.read') }}</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </span>
                                </div>
                            </a>
                        </article>
                        @endforeach
                    </div>

                    @if($posts->hasPages())
                    <div class="mt-12 pt-8 border-t border-line">
                        {{ $posts->onEachSide(1)->links('vendor.pagination.lux') }}
                    </div>
                    @endif
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-28 space-y-5">

                    @if($categories->isNotEmpty())
                    <div class="card-lux p-6 lg:p-7 reveal">
                        <p class="eyebrow mb-5">{{ __('pages.blog.categories') }}</p>
                        <ul class="space-y-1">
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ lroute('blog') }}?category={{ $category->slug }}"
                                   class="flex items-center justify-between gap-3 px-3 py-2.5 -mx-1 rounded-lg text-sm text-graphite-600 transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] hover:bg-ivory-100 hover:text-graphite-900 hover:translate-x-1">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-xs text-graphite-400 tabular">{{ $category->posts_count ?? 0 }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="card-lux card-lux-featured p-6 lg:p-7 reveal">
                        <span class="icon-plate icon-plate-sm"><x-lux-icon name="spark" /></span>
                        <p class="mt-4 font-display text-base font-semibold text-graphite-900">
                            {{ __('pages.blog.similar_title') }}
                        </p>
                        <p class="mt-2 text-[0.8125rem] leading-relaxed text-graphite-600">
                            {{ __('pages.blog.similar_body') }}
                        </p>
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                           class="btn btn-primary btn-sm btn-block mt-5">
                            <span>{{ __('pages.blog.start_discussion') }}</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<x-cta-band />

@endsection
