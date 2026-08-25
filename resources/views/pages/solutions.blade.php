@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Solusi Software Enterprise & AI — Aldef Tech';
$metaDescription = 'Solusi sistem manajemen bisnis, SaaS platform, POS omnichannel, CRM sales funnel, hospitality booking engine, dan custom ERP enterprise.';

$solutionCardStyles = [
    [
        'card_bg' => 'bg-gradient-to-br from-[#111A2E]/95 via-[#1E3A8A]/45 to-[#0F172A]/95 border-blue-500/40 shadow-[0_16px_40px_-10px_rgba(37,99,235,0.25)] hover:border-blue-400 hover:shadow-[0_24px_50px_-10px_rgba(37,99,235,0.4)]',
        'icon_box' => 'bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-lg shadow-blue-500/30',
        'badge' => 'text-blue-200 bg-blue-950/80 border-blue-700/60',
        'link' => 'text-blue-300 hover:text-white',
    ],
    [
        'card_bg' => 'bg-gradient-to-br from-[#111A2E]/95 via-[#065F46]/45 to-[#0F172A]/95 border-emerald-500/40 shadow-[0_16px_40px_-10px_rgba(16,185,129,0.25)] hover:border-emerald-400 hover:shadow-[0_24px_50px_-10px_rgba(16,185,129,0.4)]',
        'icon_box' => 'bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-lg shadow-emerald-500/30',
        'badge' => 'text-emerald-200 bg-emerald-950/80 border-emerald-700/60',
        'link' => 'text-emerald-300 hover:text-white',
    ],
    [
        'card_bg' => 'bg-gradient-to-br from-[#111A2E]/95 via-[#581C87]/45 to-[#0F172A]/95 border-purple-500/40 shadow-[0_16px_40px_-10px_rgba(168,85,247,0.25)] hover:border-purple-400 hover:shadow-[0_24px_50px_-10px_rgba(168,85,247,0.4)]',
        'icon_box' => 'bg-gradient-to-br from-purple-500 to-purple-700 text-white shadow-lg shadow-purple-500/30',
        'badge' => 'text-purple-200 bg-purple-950/80 border-purple-700/60',
        'link' => 'text-purple-300 hover:text-white',
    ],
    [
        'card_bg' => 'bg-gradient-to-br from-[#111A2E]/95 via-[#78350F]/45 to-[#0F172A]/95 border-amber-500/40 shadow-[0_16px_40px_-10px_rgba(245,158,11,0.25)] hover:border-amber-400 hover:shadow-[0_24px_50px_-10px_rgba(245,158,11,0.4)]',
        'icon_box' => 'bg-gradient-to-br from-amber-500 to-amber-700 text-white shadow-lg shadow-amber-500/30',
        'badge' => 'text-amber-200 bg-amber-950/80 border-amber-700/60',
        'link' => 'text-amber-300 hover:text-white',
    ],
    [
        'card_bg' => 'bg-gradient-to-br from-[#111A2E]/95 via-[#155E75]/45 to-[#0F172A]/95 border-cyan-500/40 shadow-[0_16px_40px_-10px_rgba(6,182,212,0.25)] hover:border-cyan-400 hover:shadow-[0_24px_50px_-10px_rgba(6,182,212,0.4)]',
        'icon_box' => 'bg-gradient-to-br from-cyan-500 to-cyan-700 text-white shadow-lg shadow-cyan-500/30',
        'badge' => 'text-cyan-200 bg-cyan-950/80 border-cyan-700/60',
        'link' => 'text-cyan-300 hover:text-white',
    ],
    [
        'card_bg' => 'bg-gradient-to-br from-[#111A2E]/95 via-[#881337]/45 to-[#0F172A]/95 border-rose-500/40 shadow-[0_16px_40px_-10px_rgba(244,63,94,0.25)] hover:border-rose-400 hover:shadow-[0_24px_50px_-10px_rgba(244,63,94,0.4)]',
        'icon_box' => 'bg-gradient-to-br from-rose-500 to-rose-700 text-white shadow-lg shadow-rose-500/30',
        'badge' => 'text-rose-200 bg-rose-950/80 border-rose-700/60',
        'link' => 'text-rose-300 hover:text-white',
    ],
];
@endphp

{{-- Hero Section (Dominant Cyan & Blue Blend) --}}
<section class="section-padding pt-16 lg:pt-24 pb-20 lg:pb-28 relative overflow-hidden border-b border-cyan-500/30 bg-gradient-to-br from-[#082f49] via-[#0e7490] to-[#06b6d4] text-white">
    {{-- Cyan Ambient Mesh Glows --}}
    <div class="absolute inset-0 hero-grid-dark pointer-events-none opacity-40"></div>
    <div class="absolute -top-24 -right-24 w-[550px] h-[550px] bg-cyan-300/35 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-sky-400/30 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-cyan-400/20 blur-[140px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        {{-- Breadcrumb --}}
        <div class="flex items-center justify-center gap-2 text-xs font-mono text-cyan-100 mb-6 reveal">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">HOME</a>
            <span>/</span>
            <span class="text-white font-bold">SOLUTIONS</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/15 border border-white/25 backdrop-blur-md shadow-2xs mb-6 reveal">
                <span class="status-dot status-dot-pulse bg-cyan-200"></span>
                <span class="text-xs font-semibold text-white tracking-wide uppercase">Enterprise Solution Architecture</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Sistem Bisnis <span class="bg-gradient-to-r from-white via-cyan-100 to-sky-100 bg-clip-text text-transparent">Siap Adaptasi</span>
            </h1>
            <p class="text-cyan-50 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Solusi perangkat lunak enterprise teruji yang dirancang untuk mengotomasi proses kerja, mengeliminasi inefisiensi manual, dan memberikan visibilitas data real-time.
            </p>
        </div>
    </div>
</section>

{{-- Solutions Grid (Distinct Soft-Colored Backgrounds with White Text) --}}
<section class="section-padding bg-gradient-to-b from-[#090E1A] via-[#0C1427] to-[#080D18] relative text-white border-b border-slate-800/80">
    {{-- Ambient Lighting --}}
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($solutions as $index => $solution)
            @php
                $style = $solutionCardStyles[$index % count($solutionCardStyles)];
            @endphp
            <div class="rounded-3xl p-8 flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 reveal reveal-delay-{{ min($loop->iteration, 3) }} border backdrop-blur-xl {{ $style['card_bg'] }}">
                <div>
                    {{-- Icon Container & Badge --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 {{ $style['icon_box'] }}">
                            {{ $solution->icon ?? '📦' }}
                        </div>
                        <span class="text-[0.6875rem] font-mono font-bold px-2.5 py-1 rounded-full border {{ $style['badge'] }}">
                            Enterprise Ready
                        </span>
                    </div>

                    <h2 class="text-xl font-display font-bold text-white mb-3">
                        {{ $solution->title }}
                    </h2>

                    <p class="text-white/90 text-sm leading-relaxed mb-6 font-normal">
                        {{ $solution->short_description }}
                    </p>

                    @if($solution->features && count($solution->features))
                    <div class="space-y-2 pt-5 border-t border-white/15 mb-6">
                        @foreach($solution->features as $feature)
                        <div class="flex items-start gap-2.5 text-xs text-white bg-white/10 border border-white/15 px-3.5 py-2 rounded-xl">
                            <svg class="w-4 h-4 text-cyan-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-medium leading-normal">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- CTA Action Link --}}
                <div class="pt-5 border-t border-white/15 flex items-center justify-between">
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20dengan%20solusi%20{{ urlencode($solution->title) }}"
                       target="_blank" rel="noopener"
                       class="text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5 transition-colors group/link {{ $style['link'] }}">
                        <span>Konsultasi Solusi Ini</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20 text-white/70 bg-slate-900/50 rounded-3xl border border-white/10">
                Solusi akan segera diperbarui.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Final Conversion CTA (Black, Blue & Red Luxury Mesh — Matching Home Mulai Transformasi) --}}
<section class="py-24 lg:py-32 relative bg-[#060810] text-white overflow-hidden" id="contact">
    <div class="absolute -top-32 -left-20 w-[520px] h-[520px] bg-gradient-to-br from-blue-600/30 via-indigo-600/20 to-transparent rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-20 w-[560px] h-[560px] bg-gradient-to-tl from-rose-600/25 via-red-600/20 to-transparent rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-radial from-blue-600/15 via-red-700/10 to-transparent blur-[120px] pointer-events-none"></div>

    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none opacity-60"></div>

    <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-blue-950/80 via-slate-900/90 to-red-950/80 border border-white/15 text-xs font-mono font-bold tracking-wider mb-6 shadow-lg reveal">
            <span class="w-2 h-2 rounded-full bg-gradient-to-r from-blue-400 to-rose-500 animate-pulse"></span>
            <span class="bg-gradient-to-r from-blue-300 via-white to-rose-300 bg-clip-text text-transparent uppercase">Mulai Transformasi</span>
        </div>
        
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal reveal-delay-1">
            Membutuhkan Sistem yang <span class="bg-gradient-to-r from-blue-400 via-indigo-200 to-rose-400 bg-clip-text text-transparent">Disesuaikan Khusus?</span>
        </h2>
        
        <p class="text-slate-300 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed reveal reveal-delay-2">
            Kami siap mengembangkan custom software yang menyatu dengan SOP dan infrastruktur IT perusahaan Anda dengan arsitektur masa depan.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 reveal reveal-delay-3">
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20ingin%20berkonsultasi%20mengenai%20kebutuhan%20custom%20software%20enterprise%20untuk%20perusahaan%20saya."
               target="_blank" rel="noopener"
               class="btn-primary btn-lg shadow-xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-600 border border-blue-400/30">
                <span>Diskusikan Kebutuhan Custom</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn-ghost-light btn-lg border border-white/20 hover:border-white/40">
                <span>Isi Form Konsultasi</span>
            </a>
        </div>
    </div>
</section>
@endsection
