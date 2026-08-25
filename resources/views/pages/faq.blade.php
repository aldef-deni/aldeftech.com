@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Frequently Asked Questions (FAQ) — Aldef Tech';
$metaDescription = 'Jawaban lengkap seputar proses konsultasi, durasi pengerjaan, tech stack, kepemilikan source code, keamanan data, dan SLA support di Aldef Tech.';
@endphp

{{-- Hero Section (Signature Aldef Dark & Navy Tech Hero) --}}
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
                <span class="text-xs font-semibold text-blue-200 tracking-wide uppercase">Knowledge & Clarification</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Frequently Asked <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Questions</span>
            </h1>
            <p class="text-slate-300 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Segala hal yang perlu Anda ketahui mengenai metodologi kerja, estimasi investasi, jaminan kepemilikan kode, keamanan data, dan dukungan SLA kami.
            </p>
        </div>
    </div>
</section>

{{-- FAQ Main Section (Aldef Dark & Navy Signature Background) --}}
<section class="section-padding bg-gradient-to-b from-[#090E1A] via-[#0C1427] to-[#080D18] relative text-slate-300 border-b border-slate-800/80"
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
    {{-- Ambient Lighting --}}
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-10 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        
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
                       class="w-full pl-11 pr-4 py-4 bg-[#0F172A]/90 border border-white/15 rounded-2xl text-white placeholder-slate-400 shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm font-medium backdrop-blur-md">
            </div>

            {{-- Category Filter Pills --}}
            <div class="flex flex-wrap items-center justify-center gap-2.5">
                <button @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-blue-600 text-white border-blue-500 shadow-md shadow-blue-500/25' : 'bg-white/[0.06] border-white/10 text-slate-300 hover:text-white hover:bg-white/10 hover:border-white/20'"
                        class="px-5 py-2 rounded-xl text-xs font-mono font-bold uppercase transition-all border">
                    Semua Topik
                </button>
                @foreach($categories as $cat)
                <button @click="selectedCategory = '{{ strtolower($cat) }}'"
                        :class="selectedCategory === '{{ strtolower($cat) }}' ? 'bg-blue-600 text-white border-blue-500 shadow-md shadow-blue-500/25' : 'bg-white/[0.06] border-white/10 text-slate-300 hover:text-white hover:bg-white/10 hover:border-white/20'"
                        class="px-5 py-2 rounded-xl text-xs font-mono font-bold uppercase transition-all border">
                    {{ $cat }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Accordion List with Hover Motion Animation --}}
        <div class="space-y-4">
            @foreach($faqs as $index => $faq)
            <div x-show="matches('{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}', '{{ addslashes($faq->category ?? 'General') }}')"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="rounded-2xl border transition-all duration-300 overflow-hidden group shadow-[0_8px_20px_-6px_rgba(0,0,0,0.3)] hover:-translate-y-1 hover:shadow-[0_16px_36px_-8px_rgba(37,99,235,0.25)]"
                 :class="activeFaq === {{ $index }} ? 'bg-[#111C35]/95 border-blue-500/60 ring-1 ring-blue-500/30' : 'bg-[#0F172A]/90 border-white/10 hover:border-blue-500/40'">
                
                {{-- Question Header --}}
                <button @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})"
                        class="w-full px-6 py-5 text-left flex items-center justify-between gap-4 font-display font-bold text-white text-base lg:text-lg transition-colors group-hover:text-blue-300">
                    <div class="flex items-center gap-3">
                        <span class="text-[0.6875rem] font-mono font-bold px-2.5 py-1 rounded-md bg-blue-950/80 text-blue-300 border border-blue-800/60 group-hover:border-blue-500/80 transition-colors">
                            {{ $faq->category ?? 'General' }}
                        </span>
                        <span>{{ $faq->question }}</span>
                    </div>

                    <span class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300"
                          :class="activeFaq === {{ $index }} ? 'rotate-180 bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-white/[0.08] text-slate-400 group-hover:bg-blue-600/30 group-hover:text-white'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>

                {{-- Answer Content --}}
                <div x-show="activeFaq === {{ $index }}" x-collapse>
                    <div class="px-6 pb-6 pt-3 text-slate-300 text-sm sm:text-base leading-relaxed border-t border-white/10 bg-slate-950/50">
                        {{ $faq->answer }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Still Have Questions CTA Card (Green WhatsApp Button + Blue Form Button) --}}
        <div class="mt-16 bg-gradient-to-br from-[#0D1527] via-[#0F1D38] to-[#0A1020] border border-blue-500/30 rounded-3xl p-8 sm:p-12 text-center shadow-[0_20px_50px_-10px_rgba(0,0,0,0.6)] relative overflow-hidden reveal">
            <div class="absolute -top-24 -right-24 w-60 h-60 bg-blue-600/15 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-60 h-60 bg-indigo-600/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-2xl mx-auto mb-5 shadow-lg shadow-blue-500/25">
                💬
            </div>
            <h3 class="text-2xl lg:text-3xl font-display font-extrabold text-white mb-3">
                Punya Pertanyaan Spesifik Terkait Sistem Anda?
            </h3>
            <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto mb-8 leading-relaxed">
                Tim software engineer kami siap menjawab pertanyaan teknis, mendiskusikan integrasi API, atau memberikan preliminary review arsitektur secara cuma-cuma.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 relative z-10">
                {{-- Green WhatsApp Button --}}
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20memiliki%20pertanyaan%20spesifik%20mengenai%20layanan%20software%20engineering."
                   target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-500 transition-all duration-300 shadow-lg shadow-emerald-600/30 hover:shadow-emerald-500/40 hover:-translate-y-0.5 group">
                    <svg class="w-4 h-4 text-white group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <span>Chat WhatsApp Langsung</span>
                </a>
                
                {{-- Blue Form Button --}}
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm text-white bg-blue-600 hover:bg-blue-500 transition-all duration-300 shadow-lg shadow-blue-600/30 hover:shadow-blue-500/40 hover:-translate-y-0.5 group">
                    <span>Kirim Pesan via Form</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
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
