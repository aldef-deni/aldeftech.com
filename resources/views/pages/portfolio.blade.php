@extends('layouts.app')
@section('content')
@php
$pageTitle = 'The Project — Aldef Tech';
$metaDescription = 'Lihat ragam proyek software, web application, SaaS, platform OTA, dan POS modern yang telah kami bangun untuk klien dan industri.';

$featuredProjects = [
    [
        'title' => 'Arahinn Mobile — OTA & Travel Platform',
        'category' => 'Project OTA • Mobile Ecosystem',
        'image' => 'images/portfolio/arahinn-mobile.webp',
        'desc' => 'Aplikasi mobile Online Travel Agent modern dengan integrasi real-time inventory kamar, engine pencarian instan, payment gateway multi-channel otomatis, dan sistem loyalty rewards terpadu.',
        'technologies' => ['Laravel API', 'Flutter / Mobile', 'PostgreSQL', 'Midtrans Gateway', 'Redis Cache'],
        'bg_class' => 'bg-gradient-to-b from-[#F0F7FF] to-[#E6F1FD] border-[#BFDBFE]/80 shadow-[0_14px_34px_-8px_rgba(37,99,235,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(37,99,235,0.28)] hover:border-blue-400',
        'pill_class' => 'text-blue-700 bg-blue-100/90 border border-blue-200',
        'accent_hover' => 'group-hover:text-blue-600',
        'btn_class' => 'text-blue-600 hover:text-blue-800'
    ],
    [
        'title' => 'Bamboe Oerip — Booking Engine & Hospitality OTA',
        'category' => 'Project OTA • Hospitality Management',
        'image' => 'images/portfolio/bamboe-oerip.webp',
        'desc' => 'Sistem reservasi dan manajemen hospitality digital berbasis web dengan dynamic pricing engine, kalender okupansi interaktif, automated WhatsApp billing invoice, dan integrasi channel manager.',
        'technologies' => ['Laravel 11', 'Vue.js 3', 'Tailwind CSS', 'MySQL', 'WhatsApp Business API'],
        'bg_class' => 'bg-gradient-to-b from-[#F0FDF4] to-[#DCFCE7] border-[#BBF7D0]/80 shadow-[0_14px_34px_-8px_rgba(16,185,129,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(16,185,129,0.28)] hover:border-emerald-400',
        'pill_class' => 'text-emerald-800 bg-emerald-100/90 border border-emerald-200',
        'accent_hover' => 'group-hover:text-emerald-600',
        'btn_class' => 'text-emerald-700 hover:text-emerald-900'
    ],
    [
        'title' => 'Aldef POS — Omnichannel Smart POS System',
        'category' => 'Project POS Sistem • Multi-Outlet',
        'image' => 'images/portfolio/aldeftech-pos.webp',
        'desc' => 'Platform Point of Sale (POS) cloud omnichannel berkecepatan tinggi dengan sinkronisasi inventori multi-cabang, barcode scanning offline-ready, audit kasir real-time, dan analitik performa laba-rugi.',
        'technologies' => ['Laravel', 'Electron / PWA', 'PostgreSQL', 'Thermal Printing', 'WebSockets'],
        'bg_class' => 'bg-gradient-to-b from-[#F5F3FF] to-[#ECE7FD] border-[#DDD6FE]/80 shadow-[0_14px_34px_-8px_rgba(99,102,241,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(99,102,241,0.28)] hover:border-indigo-400',
        'pill_class' => 'text-indigo-700 bg-indigo-100/90 border border-indigo-200',
        'accent_hover' => 'group-hover:text-indigo-600',
        'btn_class' => 'text-indigo-600 hover:text-indigo-800'
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
            <span class="text-blue-400 font-semibold">PORTFOLIO</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.08] border border-white/15 backdrop-blur-md shadow-2xs mb-6 reveal">
                <span class="status-dot status-dot-pulse"></span>
                <span class="text-xs font-semibold text-blue-200 tracking-wide uppercase">The Project Portfolio</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Karya & <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Studi Kasus Kami</span>
            </h1>
            <p class="text-slate-300 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Lihat bagaimana rekayasa perangkat lunak, sistem OTA, dan POS modern kami memberikan efisiensi nyata pada berbagai lini bisnis klien.
            </p>
        </div>
    </div>
</section>

{{-- Portfolio Showcase (Clean 3 Cards from Home, No Tabs, Clean Unobstructed Image) --}}
<section class="section-padding bg-gradient-to-b from-[#090E1A] via-[#0C1427] to-[#080D18] relative text-slate-300 border-b border-slate-800/80">
    {{-- Ambient Lighting --}}
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        
        {{-- 3 Featured Project Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProjects as $p)
            <div class="rounded-3xl border overflow-hidden flex flex-col justify-between group transition-all duration-300 hover:-translate-y-2 reveal {{ $p['bg_class'] }}">
                <div>
                    {{-- Clean Unobstructed Image (No Tag/Badge on top of image) --}}
                    <div class="aspect-[16/10] bg-slate-900/5 relative overflow-hidden border-b border-black/5">
                        <img src="{{ asset($p['image']) }}" alt="{{ $p['title'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-108" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    {{-- Body Content --}}
                    <div class="p-7">
                        <div class="mb-2">
                            <span class="text-xs font-mono font-bold px-3 py-1 rounded-full inline-block {{ $p['pill_class'] }}">
                                {{ $p['category'] }}
                            </span>
                        </div>

                        <h3 class="text-lg lg:text-xl font-display font-bold text-slate-900 mb-3 transition-colors {{ $p['accent_hover'] }}">
                            {{ $p['title'] }}
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            {{ $p['desc'] }}
                        </p>

                        {{-- Tech badges --}}
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach($p['technologies'] as $tech)
                            <span class="text-[0.6875rem] px-2.5 py-1 rounded-lg bg-white/85 border border-white/80 text-slate-700 font-mono font-medium shadow-2xs">
                                {{ $tech }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Action Row --}}
                <div class="px-7 pb-7 pt-4 border-t border-black/5 flex items-center justify-between">
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20dengan%20proyek%20serupa%20{{ urlencode($p['title']) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider transition-colors {{ $p['btn_class'] }}">
                        <span>Diskusi Serupa</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20ingin%20konsultasi%20studi%20kasus%20{{ urlencode($p['title']) }}"
                       target="_blank" rel="noopener"
                       class="text-[0.6875rem] font-semibold text-slate-500 hover:text-slate-800">
                        Konsultasi →
                    </a>
                </div>
            </div>
            @endforeach
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
            Ingin Membangun Solusi <span class="bg-gradient-to-r from-blue-400 via-indigo-200 to-rose-400 bg-clip-text text-transparent">Seperti Ini?</span>
        </h2>
        
        <p class="text-slate-300 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed reveal reveal-delay-2">
            Mari wujudkan aplikasi dan sistem digital berkinerja tinggi bersama tim engineer kami dengan standar kualitas arsitektur enterprise.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 reveal reveal-delay-3">
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20ingin%20membangun%20solusi%20sistem%20seperti%20pada%20portfolio%20Aldef%20Tech."
               target="_blank" rel="noopener"
               class="btn-primary btn-lg shadow-xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-600 border border-blue-400/30">
                <span>Mulai Konsultasi Project</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn-ghost-light btn-lg border border-white/20 hover:border-white/40">
                <span>Isi Form Konsultasi</span>
            </a>
        </div>
    </div>
</section>
@endsection
