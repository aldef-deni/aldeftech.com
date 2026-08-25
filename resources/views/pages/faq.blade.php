@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Frequently Asked Questions (FAQ) — Aldef Tech';
$metaDescription = 'Pertanyaan umum seputar layanan custom software development, pembuatan SaaS, AI automation, estimasi biaya, timeline, dan metodologi kerja Aldef Tech.';

$categories = $faqs->pluck('category')->filter()->unique()->values();
@endphp

{{-- Hero Section (Blue & Orange Luxury Blend) --}}
<section class="section-padding pt-16 lg:pt-24 pb-20 lg:pb-28 relative overflow-hidden border-b border-orange-500/30 bg-gradient-to-br from-[#1e3a8a] via-[#2563eb] to-[#ea580c] text-white">
    {{-- Ambient Mesh Glows --}}
    <div class="absolute inset-0 hero-grid-dark pointer-events-none opacity-30"></div>
    <div class="absolute -top-24 -right-24 w-[550px] h-[550px] bg-orange-400/35 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-blue-400/35 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-amber-400/20 blur-[140px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        {{-- Breadcrumb --}}
        <div class="flex items-center justify-center gap-2 text-xs font-mono text-blue-100 mb-6 reveal">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">HOME</a>
            <span>/</span>
            <span class="text-white font-bold">FAQ</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/15 border border-white/25 backdrop-blur-md shadow-2xs mb-6 reveal">
                <span class="status-dot status-dot-pulse bg-orange-300"></span>
                <span class="text-xs font-semibold text-white tracking-wide uppercase">Help Center & Knowledge</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Frequently Asked <span class="bg-gradient-to-r from-white via-amber-100 to-orange-100 bg-clip-text text-transparent">Questions</span>
            </h1>
            <p class="text-blue-50 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Temukan jawaban lengkap mengenai proses engineering, arsitektur, estimasi durasi, model kerja sama, dan komitmen pemeliharaan kami.
            </p>
        </div>
    </div>
</section>

{{-- FAQ Main Section (Blue & Orange Blend with Crisp Black Text on White Cards) --}}
<section class="section-padding bg-gradient-to-b from-[#1e3a8a] via-[#1e40af] to-[#c2410c] relative border-b border-orange-500/30 overflow-hidden"
         x-data="{
             activeAccordion: null,
             activeCategory: 'all',
             searchQuery: '',
             toggle(id) {
                 this.activeAccordion = this.activeAccordion === id ? null : id;
             },
             filterFaq(category, question, answer) {
                 const matchCat = this.activeCategory === 'all' || category.toLowerCase() === this.activeCategory.toLowerCase();
                 const matchSearch = this.searchQuery === '' ||
                                     question.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                     answer.toLowerCase().includes(this.searchQuery.toLowerCase());
                 return matchCat && matchSearch;
             }
         }">

    {{-- Ambient Lighting --}}
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-500/20 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-orange-500/25 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-15 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">

        {{-- Search & Category Filter --}}
        <div class="mb-12 space-y-6 reveal">
            {{-- Search Bar --}}
            <div class="relative">
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari pertanyaan... (cth. durasi, garansi, integrasi, backend)"
                       class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/95 border border-white/80 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 text-sm sm:text-base shadow-xl transition-all">
                <svg class="w-5 h-5 text-slate-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Category Filter Pills --}}
            @if($categories->count() > 1)
            <div class="flex flex-wrap items-center justify-center gap-2">
                <button @click="activeCategory = 'all'"
                        :class="activeCategory === 'all' ? 'bg-white text-slate-900 font-bold shadow-md' : 'bg-white/20 text-white border border-white/30 hover:bg-white/30'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200">
                    Semua Kategori
                </button>
                @foreach($categories as $category)
                <button @click="activeCategory = '{{ strtolower($category) }}'"
                        :class="activeCategory === '{{ strtolower($category) }}' ? 'bg-white text-slate-900 font-bold shadow-md' : 'bg-white/20 text-white border border-white/30 hover:bg-white/30'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200">
                    {{ $category }}
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- FAQ Accordion Cards (Crisp Black Text on White Surfaces) --}}
        <div class="space-y-4">
            @forelse($faqs as $faq)
            <div x-show="filterFaq('{{ $faq->category ?? 'general' }}', '{{ addslashes($faq->question) }}', '{{ addslashes(strip_tags($faq->answer)) }}')"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="rounded-2xl bg-white/95 border border-white/80 shadow-md hover:shadow-xl hover:-translate-y-1 hover:border-orange-400 transition-all duration-300 overflow-hidden group">
                
                {{-- Question Header (Crisp Black Text) --}}
                <button @click="toggle({{ $faq->id }})"
                        class="w-full p-6 text-left flex items-start justify-between gap-4 cursor-pointer focus:outline-none select-none">
                    <div class="flex-1 pr-2">
                        @if($faq->category)
                        <span class="inline-block text-[0.6875rem] font-mono font-bold uppercase tracking-wider text-blue-800 bg-blue-100/90 border border-blue-200 px-2.5 py-0.5 rounded-md mb-2">
                            {{ $faq->category }}
                        </span>
                        @endif
                        <h3 class="text-base sm:text-lg font-display font-bold text-slate-900 group-hover:text-blue-700 transition-colors leading-snug">
                            {{ $faq->question }}
                        </h3>
                    </div>

                    {{-- Rotating Toggle Chevron --}}
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300"
                         :class="activeAccordion === {{ $faq->id }} ? 'bg-orange-500 text-white rotate-180 shadow-md' : 'bg-slate-100 text-slate-700 group-hover:bg-blue-600 group-hover:text-white'">
                        <svg class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                {{-- Answer Panel (Crisp Black Text) --}}
                <div x-show="activeAccordion === {{ $faq->id }}"
                     x-collapse
                     class="px-6 pb-6 pt-2 text-slate-800 text-sm sm:text-base leading-relaxed border-t border-slate-200/80 font-normal">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white/95 rounded-2xl border border-white/80 text-slate-600">
                Belum ada data FAQ yang dipublikasikan.
            </div>
            @endforelse
        </div>

        {{-- Still Have Questions CTA Card (Crisp Black Text) --}}
        <div class="mt-16 bg-white/95 border border-white/80 rounded-3xl p-8 sm:p-10 shadow-2xl text-center reveal">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-orange-500 text-white flex items-center justify-center text-2xl mx-auto mb-5 shadow-lg shadow-blue-600/20">
                💬
            </div>
            <h3 class="text-2xl font-display font-extrabold text-slate-900 mb-3">
                Punya Pertanyaan Spesifik Terkait Sistem Anda?
            </h3>
            <p class="text-slate-700 text-sm sm:text-base max-w-xl mx-auto mb-8 leading-relaxed">
                Tim lead engineer kami siap membantu menganalisis tantangan sistem, arsitektur, integrasi API, atau estimasi timeline proyek Anda.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                {{-- Green Solid WhatsApp Button --}}
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20punya%20pertanyaan%20spesifik%20mengenai%20layanan%20software."
                   target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <span>Chat WhatsApp Langsung</span>
                </a>

                {{-- Blue Solid Form Button --}}
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 transition-all duration-200">
                    <span>Kirim Pesan via Form</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
