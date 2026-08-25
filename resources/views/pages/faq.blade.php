@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Frequently Asked Questions (FAQ) — Aldef Tech';
$metaDescription = 'Pertanyaan umum seputar layanan custom software development, pembuatan SaaS, AI automation, estimasi biaya, timeline, dan metodologi kerja Aldef Tech.';

$categories = $faqs->pluck('category')->filter()->unique()->values();

$faqCardStyles = [
    [
        'card_bg' => 'bg-gradient-to-b from-[#F0F7FF] via-[#E6F1FD] to-[#DCEAFB] border-blue-200/90 shadow-[0_10px_25px_-6px_rgba(37,99,235,0.15)] hover:shadow-[0_18px_36px_-8px_rgba(37,99,235,0.25)] hover:border-blue-400',
        'badge' => 'text-blue-800 bg-blue-100/90 border-blue-200',
        'title_hover' => 'group-hover:text-blue-700',
        'btn_active' => 'bg-blue-600 text-white',
        'btn_inactive' => 'bg-blue-100 text-blue-800 group-hover:bg-blue-600 group-hover:text-white',
        'border_answer' => 'border-blue-200/80',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#F0FDF4] via-[#DCFCE7] to-[#D1FAE5] border-emerald-200/90 shadow-[0_10px_25px_-6px_rgba(16,185,129,0.15)] hover:shadow-[0_18px_36px_-8px_rgba(16,185,129,0.25)] hover:border-emerald-400',
        'badge' => 'text-emerald-800 bg-emerald-100/90 border-emerald-200',
        'title_hover' => 'group-hover:text-emerald-700',
        'btn_active' => 'bg-emerald-600 text-white',
        'btn_inactive' => 'bg-emerald-100 text-emerald-800 group-hover:bg-emerald-600 group-hover:text-white',
        'border_answer' => 'border-emerald-200/80',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#FAF5FF] via-[#F3E8FF] to-[#E9D5FF] border-purple-200/90 shadow-[0_10px_25px_-6px_rgba(168,85,247,0.15)] hover:shadow-[0_18px_36px_-8px_rgba(168,85,247,0.25)] hover:border-purple-400',
        'badge' => 'text-purple-800 bg-purple-100/90 border-purple-200',
        'title_hover' => 'group-hover:text-purple-700',
        'btn_active' => 'bg-purple-600 text-white',
        'btn_inactive' => 'bg-purple-100 text-purple-800 group-hover:bg-purple-600 group-hover:text-white',
        'border_answer' => 'border-purple-200/80',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#FFFBEB] via-[#FEF3C7] to-[#FDE68A] border-amber-200/90 shadow-[0_10px_25px_-6px_rgba(245,158,11,0.15)] hover:shadow-[0_18px_36px_-8px_rgba(245,158,11,0.25)] hover:border-amber-400',
        'badge' => 'text-amber-900 bg-amber-100/90 border-amber-200',
        'title_hover' => 'group-hover:text-amber-700',
        'btn_active' => 'bg-amber-600 text-white',
        'btn_inactive' => 'bg-amber-100 text-amber-900 group-hover:bg-amber-600 group-hover:text-white',
        'border_answer' => 'border-amber-200/80',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#ECFEFF] via-[#CFFAFE] to-[#BAE6FD] border-cyan-200/90 shadow-[0_10px_25px_-6px_rgba(6,182,212,0.15)] hover:shadow-[0_18px_36px_-8px_rgba(6,182,212,0.25)] hover:border-cyan-400',
        'badge' => 'text-cyan-900 bg-cyan-100/90 border-cyan-200',
        'title_hover' => 'group-hover:text-cyan-700',
        'btn_active' => 'bg-cyan-600 text-white',
        'btn_inactive' => 'bg-cyan-100 text-cyan-900 group-hover:bg-cyan-600 group-hover:text-white',
        'border_answer' => 'border-cyan-200/80',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#FFF1F2] via-[#FFE4E6] to-[#FECDD3] border-rose-200/90 shadow-[0_10px_25px_-6px_rgba(244,63,94,0.15)] hover:shadow-[0_18px_36px_-8px_rgba(244,63,94,0.25)] hover:border-rose-400',
        'badge' => 'text-rose-900 bg-rose-100/90 border-rose-200',
        'title_hover' => 'group-hover:text-rose-700',
        'btn_active' => 'bg-rose-600 text-white',
        'btn_inactive' => 'bg-rose-100 text-rose-900 group-hover:bg-rose-600 group-hover:text-white',
        'border_answer' => 'border-rose-200/80',
    ],
];
@endphp

{{-- Hero Section (Signature Aldef Dark & Navy Tech Hero — Matching Services) --}}
<section class="hero-premium-dark section-padding pt-16 lg:pt-24 pb-20 lg:pb-28 relative overflow-hidden border-b border-slate-800/80">
    <div class="absolute inset-0 hero-grid-dark pointer-events-none opacity-60"></div>
    <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-blue-600/25 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-cyan-500/20 blur-[130px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        {{-- Breadcrumb --}}
        <div class="flex items-center justify-center gap-2 text-xs font-mono text-slate-400 mb-6 reveal">
            <a href="{{ route('home') }}" class="hover:text-blue-400 transition-colors">HOME</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold">FAQ</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.08] border border-white/15 backdrop-blur-md shadow-2xs mb-6 reveal">
                <span class="status-dot status-dot-pulse"></span>
                <span class="text-xs font-semibold text-blue-200 tracking-wide uppercase">Help Center & Knowledge</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Frequently Asked <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Questions</span>
            </h1>
            <p class="text-slate-300 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Temukan jawaban lengkap mengenai proses engineering, arsitektur, estimasi durasi, model kerja sama, dan komitmen pemeliharaan kami.
            </p>
        </div>
    </div>
</section>

{{-- FAQ Main Section (Aldef Signature Dark Theme with Soft Elegant Pastel Question Cards) --}}
<section class="section-padding bg-gradient-to-b from-[#090E1A] via-[#0C1427] to-[#080D18] relative text-slate-900 border-b border-slate-800/80 overflow-hidden"
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
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-10 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">

        {{-- Search & Category Filter --}}
        <div class="mb-12 space-y-6 reveal">
            {{-- Search Bar --}}
            <div class="relative">
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari pertanyaan... (cth. durasi, garansi, integrasi, backend)"
                       class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/95 border border-white/80 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base shadow-xl transition-all">
                <svg class="w-5 h-5 text-slate-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Category Filter Pills --}}
            @if($categories->count() > 1)
            <div class="flex flex-wrap items-center justify-center gap-2">
                <button @click="activeCategory = 'all'"
                        :class="activeCategory === 'all' ? 'bg-blue-600 text-white font-bold shadow-md' : 'bg-white/10 text-slate-300 border border-white/15 hover:bg-white/20'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200">
                    Semua Kategori
                </button>
                @foreach($categories as $category)
                <button @click="activeCategory = '{{ strtolower($category) }}'"
                        :class="activeCategory === '{{ strtolower($category) }}' ? 'bg-blue-600 text-white font-bold shadow-md' : 'bg-white/10 text-slate-300 border border-white/15 hover:bg-white/20'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200">
                    {{ $category }}
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- FAQ Accordion Cards (Soft Elegant Colored Cards) --}}
        <div class="space-y-4">
            @forelse($faqs as $index => $faq)
            @php
                $style = $faqCardStyles[$index % count($faqCardStyles)];
            @endphp
            <div x-show="filterFaq('{{ $faq->category ?? 'general' }}', '{{ addslashes($faq->question) }}', '{{ addslashes(strip_tags($faq->answer)) }}')"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="rounded-2xl border transition-all duration-300 overflow-hidden group hover:-translate-y-1 {{ $style['card_bg'] }}">
                
                {{-- Question Header --}}
                <button @click="toggle({{ $faq->id }})"
                        class="w-full p-6 text-left flex items-start justify-between gap-4 cursor-pointer focus:outline-none select-none">
                    <div class="flex-1 pr-2">
                        @if($faq->category)
                        <span class="inline-block text-[0.6875rem] font-mono font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md mb-2 border {{ $style['badge'] }}">
                            {{ $faq->category }}
                        </span>
                        @endif
                        <h3 class="text-base sm:text-lg font-display font-bold text-slate-900 transition-colors leading-snug {{ $style['title_hover'] }}">
                            {{ $faq->question }}
                        </h3>
                    </div>

                    {{-- Rotating Toggle Chevron --}}
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300"
                         :class="activeAccordion === {{ $faq->id }} ? '{{ $style['btn_active'] }} rotate-180 shadow-md' : '{{ $style['btn_inactive'] }}'">
                        <svg class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                {{-- Answer Panel --}}
                <div x-show="activeAccordion === {{ $faq->id }}"
                     x-collapse
                     class="px-6 pb-6 pt-2 text-slate-700 text-sm sm:text-base leading-relaxed border-t font-normal {{ $style['border_answer'] }}">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white/95 rounded-2xl border border-white/80 text-slate-600">
                Belum ada data FAQ yang dipublikasikan.
            </div>
            @endforelse
        </div>

        {{-- Still Have Questions CTA Card (Matching Home Mulai Transformasi Elegance) --}}
        <div class="mt-16 bg-[#0F172A]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 sm:p-10 shadow-2xl text-center reveal text-white">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-2xl mx-auto mb-5 shadow-lg shadow-blue-500/25">
                💬
            </div>
            <h3 class="text-2xl font-display font-extrabold text-white mb-3">
                Punya Pertanyaan Spesifik Terkait Sistem Anda?
            </h3>
            <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto mb-8 leading-relaxed">
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
