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

                    <div class="hidden lg:block mt-8 card-lux card-lux-featured faq-support-card p-6 reveal">
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
                <div class="accordion-item faq-premium-card reveal"
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

@push('styles')
<style>
    .faq-premium-card,
    .faq-support-card {
        position: relative;
        isolation: isolate;
        color: #f4eff0;
        border-color: rgb(196 151 69 / 20%);
        background:
            radial-gradient(90% 75% at 100% 100%, rgb(139 17 34 / 24%) 0%, transparent 68%),
            linear-gradient(137deg, #080a0d 0%, #101014 48%, #241014 100%);
        box-shadow:
            inset 0 1px 0 rgb(255 255 255 / 4%),
            0 20px 46px -32px rgb(22 4 8 / 75%);
        transition:
            transform 620ms var(--e-soft),
            border-color 420ms var(--e-glide),
            box-shadow 620ms var(--e-soft),
            background 420ms var(--e-glide);
    }
    .faq-premium-card:nth-child(3n + 2) {
        background:
            radial-gradient(80% 90% at 0% 0%, rgb(160 22 40 / 15%) 0%, transparent 62%),
            linear-gradient(142deg, #111014 0%, #080a0d 52%, #2d0e15 100%);
    }
    .faq-premium-card:nth-child(3n + 3) {
        background:
            radial-gradient(75% 85% at 86% 12%, rgb(177 25 44 / 16%) 0%, transparent 60%),
            linear-gradient(132deg, #07090c 0%, #111014 58%, #251015 100%);
    }
    .faq-premium-card::before,
    .faq-support-card::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        opacity: 1;
        background: linear-gradient(118deg, transparent 22%, rgb(255 255 255 / 2.5%) 50%, transparent 76%);
    }
    .faq-premium-card::after,
    .faq-support-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 14%;
        left: 14%;
        height: 1px;
        opacity: .72;
        transform: none;
        background: linear-gradient(90deg, transparent, rgb(215 173 94 / 72%), rgb(206 61 78 / 44%), transparent);
    }
    .faq-premium-card .accordion-trigger { color: #faf6f6; }
    .faq-premium-card .accordion-panel p { color: #bbb7bc; }
    .faq-premium-card .accordion-marker {
        color: #d9b87c;
        border-color: rgb(217 184 124 / 28%);
        background: linear-gradient(145deg, rgb(217 184 124 / 12%), rgb(116 17 31 / 18%));
    }
    .faq-premium-card .chip-neutral {
        color: #e8d3a7;
        border-color: rgb(217 184 124 / 24%);
        background: rgb(217 184 124 / 10%);
    }
    .faq-premium-card.is-open {
        border-color: rgb(217 184 124 / 34%);
        background:
            radial-gradient(95% 90% at 100% 100%, rgb(155 20 39 / 30%) 0%, transparent 68%),
            linear-gradient(137deg, #0a0c10 0%, #131116 48%, #2b1117 100%);
        box-shadow:
            inset 0 1px 0 rgb(255 255 255 / 6%),
            0 24px 50px -32px rgb(89 5 18 / 52%);
    }
    .faq-premium-card.is-open .accordion-marker {
        color: #101014;
        border-color: #d9b87c;
        background: #d9b87c;
    }
    .faq-support-card p { color: #bbb7bc; }
    .faq-support-card p:first-child { color: #faf6f6; }

    @media (hover: hover) {
        .faq-premium-card:hover,
        .faq-support-card:hover {
            transform: translateY(-4px);
            border-color: rgb(217 184 124 / 36%);
            background:
                radial-gradient(95% 85% at 100% 100%, rgb(155 20 39 / 29%) 0%, transparent 68%),
                linear-gradient(137deg, #0a0c10 0%, #131116 48%, #2b1117 100%);
            box-shadow:
                inset 0 1px 0 rgb(255 255 255 / 6%),
                0 26px 54px -32px rgb(89 5 18 / 56%),
                0 0 28px rgb(142 17 32 / 9%);
        }
        .faq-premium-card .accordion-trigger:hover { color: #f0d69f; }
        .faq-premium-card:hover .accordion-marker {
            color: #f5e8cc;
            border-color: rgb(232 211 167 / 42%);
        }
        .faq-premium-card.is-open:hover .accordion-marker {
            color: #101014;
        }
    }

    @media (max-width: 640px) {
        .faq-premium-card { border-radius: 1rem; }
        .faq-premium-card .accordion-trigger { padding: 1.25rem; }
    }
</style>
@endpush
