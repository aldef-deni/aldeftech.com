@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Layanan Software Development, SaaS & AI — Aldef Tech';
$metaDescription = 'Layanan lengkap pembuatan custom software, SaaS platform, AI & Machine Learning, automasi bisnis, dan integrasi sistem enterprise berkinerja tinggi.';

$serviceCardStyles = [
    [
        'card_bg' => 'bg-gradient-to-b from-[#F0F7FF] via-[#E6F1FD] to-[#DCEAFB] border-blue-200/90 shadow-[0_14px_34px_-8px_rgba(37,99,235,0.18)] hover:shadow-[0_24px_48px_-10px_rgba(37,99,235,0.3)] hover:border-blue-400',
        'icon_box' => 'bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-md shadow-blue-500/25',
        'feat_box' => 'bg-white/85 border-blue-200/80',
        'feat_pill' => 'bg-blue-50/90 border-blue-200 text-slate-800',
        'badge' => 'text-blue-800 bg-blue-100/90 border-blue-200',
        'check_icon' => 'text-blue-600',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#F0FDF4] via-[#DCFCE7] to-[#D1FAE5] border-emerald-200/90 shadow-[0_14px_34px_-8px_rgba(16,185,129,0.18)] hover:shadow-[0_24px_48px_-10px_rgba(16,185,129,0.3)] hover:border-emerald-400',
        'icon_box' => 'bg-gradient-to-br from-emerald-600 to-teal-700 text-white shadow-md shadow-emerald-500/25',
        'feat_box' => 'bg-white/85 border-emerald-200/80',
        'feat_pill' => 'bg-emerald-50/90 border-emerald-200 text-slate-800',
        'badge' => 'text-emerald-800 bg-emerald-100/90 border-emerald-200',
        'check_icon' => 'text-emerald-600',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#FAF5FF] via-[#F3E8FF] to-[#E9D5FF] border-purple-200/90 shadow-[0_14px_34px_-8px_rgba(168,85,247,0.18)] hover:shadow-[0_24px_48px_-10px_rgba(168,85,247,0.3)] hover:border-purple-400',
        'icon_box' => 'bg-gradient-to-br from-purple-600 to-violet-700 text-white shadow-md shadow-purple-500/25',
        'feat_box' => 'bg-white/85 border-purple-200/80',
        'feat_pill' => 'bg-purple-50/90 border-purple-200 text-slate-800',
        'badge' => 'text-purple-800 bg-purple-100/90 border-purple-200',
        'check_icon' => 'text-purple-600',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#FFFBEB] via-[#FEF3C7] to-[#FDE68A] border-amber-200/90 shadow-[0_14px_34px_-8px_rgba(245,158,11,0.18)] hover:shadow-[0_24px_48px_-10px_rgba(245,158,11,0.3)] hover:border-amber-400',
        'icon_box' => 'bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/25',
        'feat_box' => 'bg-white/85 border-amber-200/80',
        'feat_pill' => 'bg-amber-50/90 border-amber-200 text-slate-800',
        'badge' => 'text-amber-900 bg-amber-100/90 border-amber-200',
        'check_icon' => 'text-amber-600',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#ECFEFF] via-[#CFFAFE] to-[#BAE6FD] border-cyan-200/90 shadow-[0_14px_34px_-8px_rgba(6,182,212,0.18)] hover:shadow-[0_24px_48px_-10px_rgba(6,182,212,0.3)] hover:border-cyan-400',
        'icon_box' => 'bg-gradient-to-br from-cyan-600 to-sky-700 text-white shadow-md shadow-cyan-500/25',
        'feat_box' => 'bg-white/85 border-cyan-200/80',
        'feat_pill' => 'bg-cyan-50/90 border-cyan-200 text-slate-800',
        'badge' => 'text-cyan-900 bg-cyan-100/90 border-cyan-200',
        'check_icon' => 'text-cyan-600',
    ],
    [
        'card_bg' => 'bg-gradient-to-b from-[#FFF1F2] via-[#FFE4E6] to-[#FECDD3] border-rose-200/90 shadow-[0_14px_34px_-8px_rgba(244,63,94,0.18)] hover:shadow-[0_24px_48px_-10px_rgba(244,63,94,0.3)] hover:border-rose-400',
        'icon_box' => 'bg-gradient-to-br from-rose-600 to-red-700 text-white shadow-md shadow-rose-500/25',
        'feat_box' => 'bg-white/85 border-rose-200/80',
        'feat_pill' => 'bg-rose-50/90 border-rose-200 text-slate-800',
        'badge' => 'text-rose-900 bg-rose-100/90 border-rose-200',
        'check_icon' => 'text-rose-600',
    ],
];
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
            <span class="text-blue-400 font-semibold">SERVICES</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.08] border border-white/15 backdrop-blur-md shadow-2xs mb-6 reveal">
                <span class="status-dot status-dot-pulse"></span>
                <span class="text-xs font-semibold text-blue-200 tracking-wide uppercase">Enterprise Service Capabilities</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Comprehensive <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Software & AI Engineering</span>
            </h1>
            <p class="text-slate-300 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Dari analisis kebutuhan bisnis, arsitektur data, hingga implementasi kecerdasan buatan, kami membangun software berkualitas tinggi untuk pertumbuhan perusahaan Anda.
            </p>
        </div>
    </div>
</section>

{{-- Services Grid (Soft Elegant Colored Cards with High Legibility) --}}
<section class="section-padding bg-gradient-to-b from-[#090E1A] via-[#0C1427] to-[#080D18] relative text-slate-900 border-b border-slate-800/80">
    {{-- Ambient Lighting --}}
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="flex flex-col gap-12 lg:gap-16">
            @forelse($services as $index => $service)
            @php
                $style = $serviceCardStyles[$index % count($serviceCardStyles)];
            @endphp
            <div class="rounded-3xl p-8 lg:p-12 border transition-all duration-300 hover:-translate-y-2 group reveal reveal-delay-{{ min($loop->iteration, 3) }} {{ $style['card_bg'] }}" id="{{ $service->slug }}">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    {{-- Service Header & Icon --}}
                    <div class="lg:col-span-4">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-105 transition-transform duration-300 {{ $style['icon_box'] }}">
                            {{ $service->icon ?? '⚡' }}
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-display font-extrabold text-slate-900 mb-3 tracking-tight">
                            {{ $service->title }}
                        </h2>
                        <p class="text-slate-700 text-sm sm:text-base leading-relaxed mb-8 font-normal">
                            {{ $service->short_description }}
                        </p>
                        
                        {{-- Button: Blue background normally, purple on hover --}}
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20dengan%20layanan%20{{ urlencode($service->title) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm text-white bg-blue-600 hover:bg-purple-600 transition-all duration-300 shadow-lg shadow-blue-600/30 hover:shadow-purple-600/40 hover:-translate-y-0.5 group/btn">
                            <span>Konsultasikan Layanan Ini</span>
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>

                    {{-- Service Details & Features --}}
                    <div class="lg:col-span-8 rounded-2xl p-6 lg:p-8 border shadow-sm {{ $style['feat_box'] }}">
                        @if($service->description)
                        <div class="text-slate-800 text-sm sm:text-base leading-relaxed mb-6 font-normal">
                            {!! nl2br(e($service->description)) !!}
                        </div>
                        @endif

                        @if($service->features && count($service->features))
                        <div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-mono font-bold uppercase tracking-wider mb-4 border {{ $style['badge'] }}">
                                <span>⚡</span>
                                <span>Key Capabilities & Features</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($service->features as $feature)
                                <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-800 px-3.5 py-2.5 rounded-xl border shadow-2xs {{ $style['feat_pill'] }}">
                                    <svg class="w-4 h-4 shrink-0 {{ $style['check_icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span class="font-semibold">{{ $feature }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            @empty
            <div class="text-center py-20 text-slate-400 bg-slate-900/50 rounded-3xl border border-white/10">
                Layanan akan segera diperbarui.
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
            Butuh Spesifikasi <span class="bg-gradient-to-r from-blue-400 via-indigo-200 to-rose-400 bg-clip-text text-transparent">Sistem Khusus?</span>
        </h2>
        
        <p class="text-slate-300 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed reveal reveal-delay-2">
            Setiap bisnis memiliki keunikan alur kerja. Diskusikan rancangan arsitektur sistem impian Anda bersama lead engineer kami tanpa komitmen.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 reveal reveal-delay-3">
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20ingin%20berkonsultasi%20mengenai%20spesifikasi%20sistem%20khusus%20untuk%20bisnis%20saya."
               target="_blank" rel="noopener"
               class="btn-primary btn-lg shadow-xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-600 border border-blue-400/30">
                <span>Jadwalkan Diskusi Teknis</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn-ghost-light btn-lg border border-white/20 hover:border-white/40">
                <span>Isi Form Konsultasi</span>
            </a>
        </div>
    </div>
</section>
@endsection
