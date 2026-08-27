@extends('layouts.app')

@php
    $pageTitle = __('pages.faq.meta_title');
    $metaDescription = __('pages.faq.meta_description');
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => [
        '@type' => 'Question',
        'name' => $f->question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f->answer)],
    ])->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<x-page-hero
    :eyebrow="__('pages.faq.eyebrow')"
    :title="__('pages.faq.title')"
    :accent="__('pages.faq.accent')"
    :lead="__('pages.faq.lead')"
    :breadcrumbs="[['label' => __('site.nav.faq')]]" />

{{-- ── Accordion ────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10"
         x-data="{ cat: 'all', open: null }">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">

            {{-- Category rail --}}
            <aside class="lg:col-span-3">
                <div class="lg:sticky lg:top-28">
                    <p class="eyebrow mb-5 reveal">{{ __('pages.faq.categories') }}</p>

                    <div class="flex lg:flex-col gap-2 overflow-x-auto no-scrollbar pb-1 reveal">
                        <button type="button" @click="cat = 'all'; open = null"
                                class="shrink-0 lg:shrink text-left px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] border"
                                :class="cat === 'all' ? 'bg-graphite-900 border-graphite-900 text-ivory-100' : 'bg-white border-line text-graphite-600 hover:border-gold-300 hover:text-graphite-900'">
                            {{ __('site.common.all') }}
                        </button>
                        @foreach($categories as $category)
                        <button type="button" @click="cat = @js($category); open = null"
                                class="shrink-0 lg:shrink text-left px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-500 ease-[cubic-bezier(.22,1,.36,1)] border"
                                :class="cat === @js($category) ? 'bg-graphite-900 border-graphite-900 text-ivory-100' : 'bg-white border-line text-graphite-600 hover:border-gold-300 hover:text-graphite-900'">
                            {{ $category }}
                        </button>
                        @endforeach
                    </div>

                    <div class="hidden lg:block mt-8 card-lux card-lux-featured p-6 reveal">
                        <p class="text-sm font-display font-semibold text-graphite-900">{{ __('pages.faq.unanswered_title') }}</p>
                        <p class="mt-2 text-[0.8125rem] leading-relaxed text-graphite-600">
                            {{ __('pages.faq.unanswered_body') }}
                        </p>
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                           class="btn btn-primary btn-sm btn-block mt-5">
                            <span>{{ __('pages.faq.ask_whatsapp') }}</span>
                        </a>
                    </div>
                </div>
            </aside>

            {{-- Questions --}}
            <div class="lg:col-span-9 space-y-3" data-reveal-group="50">
                @foreach($faqs as $i => $faq)
                <div class="accordion-item reveal"
                     :class="open === {{ $i }} && 'is-open'"
                     @if(!empty($faq->category)) x-show="cat === 'all' || cat === @js($faq->category)" x-transition.opacity.duration.400ms @endif>

                    <button type="button" class="accordion-trigger"
                            @click="open = open === {{ $i }} ? null : {{ $i }}"
                            :aria-expanded="(open === {{ $i }}).toString()">
                        <span class="flex-1">{{ $faq->question }}</span>
                        <span class="accordion-marker" aria-hidden="true">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                        </span>
                    </button>

                    <div class="accordion-panel">
                        <div>
                            <div class="px-6 pb-6 -mt-1">
                                @if(!empty($faq->category))
                                    <span class="chip chip-neutral mb-4">{{ $faq->category }}</span>
                                @endif
                                <p class="text-[0.9375rem] leading-[1.8] text-graphite-600">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<x-cta-band
    :eyebrow="__('pages.faq.closing.eyebrow')"
    :title="__('pages.faq.closing.title')"
    :accent="__('pages.faq.closing.accent')"
    :lead="__('pages.faq.closing.lead')"
    :primary-label="__('pages.faq.closing.primary')"
    :secondary-label="__('pages.faq.closing.secondary')" />

@endsection
