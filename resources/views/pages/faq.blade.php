@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Frequently Asked Questions (FAQ) — Aldef Tech';
$metaDescription = 'Jawaban lengkap seputar proses konsultasi, durasi pengerjaan, tech stack, kepemilikan source code, keamanan data, dan SLA support di Aldef Tech.';
@endphp

{{-- Hero Section --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        {{-- Breadcrumb --}}
        <div class="flex items-center justify-center gap-2 text-xs font-mono text-slate-500 mb-6 reveal">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">HOME</a>
            <span>/</span>
            <span class="text-blue-600 font-semibold">FAQ</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">Knowledge & Clarification</span>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Frequently Asked Questions
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Segala hal yang perlu Anda ketahui mengenai metodologi kerja, estimasi investasi, jaminan kepemilikan kode, keamanan data, dan dukungan SLA kami.
            </p>
        </div>
    </div>
</section>

{{-- FAQ Main Section --}}
<section class="section-padding bg-slate-50/80 relative"
         x-data="{
             search: '',
             selectedCategory: 'all',
             activeFaq: 0,
             matches(question, answer, category) {
                 const q = this.search.toLowerCase();
                 const matchText = !q || question.toLowerCase().includes(q) || answer.toLowerCase().includes(q);
                 const matchCat = this.selectedCategory === 'all' || category.toLowerCase() === this.selectedCategory.toLowerCase();
                 return matchText && matchCat;
             }
         }">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10">
        
        {{-- Search & Category Filters --}}
        <div class="mb-12 reveal">
            {{-- Search Bar --}}
            <div class="relative mb-6">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text"
                       x-model="search"
                       placeholder="Cari topik atau pertanyaan (contoh: estimasi, source code, API, garansi)..."
                       class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 shadow-2xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-medium">
            </div>

            {{-- Category Filter Pills --}}
            <div class="flex flex-wrap items-center justify-center gap-2">
                <button @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200'"
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition-all">
                    Semua Topik
                </button>
                @foreach($categories as $cat)
                <button @click="selectedCategory = '{{ strtolower($cat) }}'"
                        :class="selectedCategory === '{{ strtolower($cat) }}' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200'"
                        class="px-4 py-2 rounded-xl text-xs font-semibold transition-all">
                    {{ $cat }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Accordion List --}}
        <div class="space-y-4">
            @foreach($faqs as $index => $faq)
            <div x-show="matches('{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}', '{{ addslashes($faq->category ?? 'General') }}')"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="rounded-2xl border transition-all duration-300 overflow-hidden shadow-2xs group"
                 :class="activeFaq === {{ $index }} ? 'bg-white border-blue-200 shadow-md ring-1 ring-blue-500/10' : 'bg-white border-slate-200 hover:border-slate-300'">
                
                {{-- Question Header --}}
                <button @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})"
                        class="w-full px-6 py-5 text-left flex items-center justify-between gap-4 font-display font-bold text-slate-900 text-base lg:text-lg transition-colors group-hover:text-blue-600">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                            {{ $faq->category ?? 'General' }}
                        </span>
                        <span>{{ $faq->question }}</span>
                    </div>

                    <span class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-transform duration-300"
                          :class="activeFaq === {{ $index }} ? 'rotate-180 bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>

                {{-- Answer Content --}}
                <div x-show="activeFaq === {{ $index }}" x-collapse>
                    <div class="px-6 pb-6 pt-2 text-slate-600 text-sm sm:text-base leading-relaxed border-t border-slate-100 bg-slate-50/40">
                        {{ $faq->answer }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Still Have Questions CTA Card --}}
        <div class="mt-16 bg-gradient-to-br from-[#F0F7FF] via-white to-[#E6F1FD] border border-blue-200/80 rounded-3xl p-8 sm:p-10 text-center shadow-[0_14px_34px_-8px_rgba(37,99,235,0.12)] reveal">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-2xl mx-auto mb-5 shadow-md shadow-blue-500/25">
                💬
            </div>
            <h3 class="text-2xl font-display font-extrabold text-slate-900 mb-2">
                Punya Pertanyaan Spesifik Terkait Sistem Anda?
            </h3>
            <p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto mb-8 leading-relaxed">
                Tim software engineer kami siap menjawab pertanyaan teknis, mendiskusikan integrasi API, atau memberikan preliminary review arsitektur secara cuma-cuma.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20memiliki%20pertanyaan%20spesifik%20mengenai%20layanan%20software%20engineering."
                   target="_blank" rel="noopener"
                   class="btn-primary">
                    <span>Chat WhatsApp Langsung</span>
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="btn-secondary">
                    <span>Kirim Pesan via Form</span>
                </a>
            </div>
        </div>

    </div>
</section>

{{-- JSON-LD FAQPage Schema for Search Engines --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faqs->map(function($faq) {
        return [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags($faq->answer),
            ],
        ];
    })->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection
