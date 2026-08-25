@extends('layouts.app')
@section('content')
@php
$pageTitle = 'About Aldef Tech — Premium Software Engineering & AI Studio';
$metaDescription = 'Aldef Tech adalah software engineering partner yang membantu bisnis membangun custom software, SaaS platform, AI & Machine Learning, dan sistem otomasi enterprise.';
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
            <span class="text-blue-400 font-semibold">ABOUT US</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.08] border border-white/15 backdrop-blur-md shadow-2xs mb-6 reveal">
                <span class="status-dot status-dot-pulse"></span>
                <span class="text-xs font-semibold text-blue-200 tracking-wide uppercase">Software Engineering & AI Technology</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Engineering Technology for <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Real Business Impact.</span>
            </h1>
            <p class="text-slate-300 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                {{ \App\Models\SiteSetting::get('about_subtitle', 'Aldef Tech adalah technology partner yang merancang dan membangun custom software, SaaS, aplikasi web, dan automasi AI untuk mengakselerasi transformasi digital bisnis Anda.') }}
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision Section (Signature Aldef Dark & Navy Background — Matching Services) --}}
<section class="section-padding bg-gradient-to-b from-[#090E1A] via-[#0C1427] to-[#080D18] relative text-slate-300 border-b border-slate-800/80">
    {{-- Ambient Lighting --}}
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
            {{-- Mission --}}
            <div class="bg-[#0F172A]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 lg:p-12 shadow-[0_16px_40px_-10px_rgba(0,0,0,0.5)] hover:border-blue-500/40 hover:shadow-[0_24px_50px_-10px_rgba(37,99,235,0.25)] hover:-translate-y-1.5 transition-all duration-300 group reveal">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-blue-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    🎯
                </div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-950/80 border border-blue-800/60 text-blue-300 text-xs font-mono font-bold uppercase tracking-wider mb-4">
                    <span>Our Mission</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-display font-extrabold text-white mb-4 group-hover:text-blue-300 transition-colors">
                    Empowering Digital Operations
                </h3>
                <p class="text-slate-300 leading-relaxed text-base">
                    {{ \App\Models\SiteSetting::get('about_mission', 'Membantu bisnis membangun sistem digital yang handal, scalable, dan terintegrasi secara mulus dengan alur kerja nyata perusahaan untuk memangkas biaya dan mempercepat pertumbuhan.') }}
                </p>
            </div>

            {{-- Vision --}}
            <div class="bg-[#0F172A]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 lg:p-12 shadow-[0_16px_40px_-10px_rgba(0,0,0,0.5)] hover:border-indigo-500/40 hover:shadow-[0_24px_50px_-10px_rgba(99,102,241,0.25)] hover:-translate-y-1.5 transition-all duration-300 group reveal reveal-delay-1">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-indigo-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    🔭
                </div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-950/80 border border-indigo-800/60 text-indigo-300 text-xs font-mono font-bold uppercase tracking-wider mb-4">
                    <span>Our Vision</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-display font-extrabold text-white mb-4 group-hover:text-indigo-300 transition-colors">
                    Leading Technology Partner
                </h3>
                <p class="text-slate-300 leading-relaxed text-base">
                    {{ \App\Models\SiteSetting::get('about_vision', 'Menjadi mitra teknologi terpercaya yang memimpin standar software development berkualitas tinggi, arsitektur tanpa utang teknis, dan adopsi kecerdasan buatan terdepan di Indonesia.') }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Core Values / Principles (3 Soft Colored, 3D Elevated Cards with Hover Motion) --}}
<section class="section-padding bg-white relative overflow-hidden" id="principles">
    {{-- Ambient Subtle Accent --}}
    <div class="absolute -top-32 -left-20 w-[450px] h-[450px] bg-blue-100/40 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-20 w-[450px] h-[450px] bg-indigo-100/40 blur-[130px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Our Principles</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight mb-4 reveal reveal-delay-1">
                Standar Kualitas yang Kami Pegang
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Prinsip fundamental kami dalam setiap proyek, dari baris kode pertama hingga pemeliharaan jangka panjang.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Principle 1: Soft Blue --}}
            <div class="rounded-3xl border border-[#BFDBFE]/80 bg-gradient-to-b from-[#F0F7FF] to-[#E6F1FD] p-8 lg:p-9 shadow-[0_14px_34px_-8px_rgba(37,99,235,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(37,99,235,0.28)] hover:-translate-y-2 hover:border-blue-400 transition-all duration-300 group reveal">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center shadow-md shadow-blue-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono font-bold px-3 py-1 rounded-full text-blue-700 bg-blue-100/90 border border-blue-200">
                        01. SOLID ARCH
                    </span>
                </div>
                <h3 class="text-xl font-display font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">
                    Architectural Integrity
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Setiap sistem dirancang dengan arsitektur modular yang bersih (*clean code*), terstruktur, dan mudah dikembangkan seiring lonjakan skala transaksi bisnis Anda.
                </p>
            </div>

            {{-- Principle 2: Soft Emerald Green --}}
            <div class="rounded-3xl border border-[#BBF7D0]/80 bg-gradient-to-b from-[#F0FDF4] to-[#DCFCE7] p-8 lg:p-9 shadow-[0_14px_34px_-8px_rgba(16,185,129,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(16,185,129,0.28)] hover:-translate-y-2 hover:border-emerald-400 transition-all duration-300 group reveal reveal-delay-1">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center shadow-md shadow-emerald-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono font-bold px-3 py-1 rounded-full text-emerald-800 bg-emerald-100/90 border border-emerald-200">
                        02. ROI FOCUS
                    </span>
                </div>
                <h3 class="text-xl font-display font-bold text-slate-900 mb-3 group-hover:text-emerald-600 transition-colors">
                    Business-First Mindset
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Teknologi adalah sarana pertumbuhan. Kami selalu memprioritaskan efisiensi operasional, simplifikasi alur kerja, dan percepatan *return on investment* (ROI) perusahaan.
                </p>
            </div>

            {{-- Principle 3: Soft Purple / Indigo --}}
            <div class="rounded-3xl border border-[#DDD6FE]/80 bg-gradient-to-b from-[#F5F3FF] to-[#ECE7FD] p-8 lg:p-9 shadow-[0_14px_34px_-8px_rgba(99,102,241,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(99,102,241,0.28)] hover:-translate-y-2 hover:border-indigo-400 transition-all duration-300 group reveal reveal-delay-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-md shadow-indigo-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono font-bold px-3 py-1 rounded-full text-indigo-700 bg-indigo-100/90 border border-indigo-200">
                        03. TRANSPARENCY
                    </span>
                </div>
                <h3 class="text-xl font-display font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">
                    Transparent Collaboration
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Komunikasi terbuka tanpa jargon rumit, sprint review berkala, dan dokumentasi lengkap agar Anda memiliki kendali penuh 100% atas status pengerjaan proyek.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- CEO / Leadership Section --}}
@if($ceoProfile)
<section class="section-padding bg-slate-50/80 border-t border-slate-200/80 relative" id="leadership">
    <div class="max-w-6xl mx-auto px-5 sm:px-8 lg:px-10">
        
        <div class="bg-gradient-to-br from-white via-slate-50/90 to-blue-50/40 border border-slate-200/90 rounded-3xl p-8 sm:p-10 lg:p-14 shadow-[0_20px_50px_-12px_rgba(15,23,42,0.09)] relative overflow-hidden reveal">
            {{-- Ambient background light --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center relative z-10">
                {{-- CEO Photo (Prominent, Taller & Top-Aligned) --}}
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative group w-full max-w-[340px] sm:max-w-[380px] lg:max-w-none">
                        {{-- Outer Soft Glow Frame --}}
                        <div class="absolute -inset-2 bg-gradient-to-tr from-blue-600 via-indigo-500 to-cyan-400 rounded-3xl opacity-20 blur-md group-hover:opacity-35 transition duration-500"></div>
                        
                        <div class="relative aspect-[3/4.2] sm:h-[28rem] lg:h-[33rem] w-full rounded-2xl overflow-hidden border-2 border-white shadow-2xl bg-slate-900">
                            <img src="{{ asset('images/deni-afrizal.jpg') }}" alt="{{ $ceoProfile->name }}" class="w-full h-full object-cover object-[top_center] transition-transform duration-700 group-hover:scale-105">
                            
                            {{-- High-tech Glass Overlay Badge --}}
                            <div class="absolute bottom-3.5 left-3.5 right-3.5 p-3.5 rounded-xl bg-slate-950/85 backdrop-blur-md border border-white/15 text-white flex items-center justify-between shadow-lg">
                                <div>
                                    <p class="text-sm font-display font-bold text-white leading-tight">{{ $ceoProfile->name }}</p>
                                    <p class="text-[0.6875rem] text-blue-300 font-mono font-medium">{{ $ceoProfile->position }}</p>
                                </div>
                                <div class="flex items-center gap-1.5 px-2 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[0.625rem] font-mono font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>ACTIVE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bio & Profile Details --}}
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-700 text-xs font-mono font-bold tracking-wider mb-4">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        LEADERSHIP & TECHNICAL ADVISORY
                    </div>
                    
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-display font-extrabold text-slate-900 mb-1.5 tracking-tight">
                        {{ $ceoProfile->name }}
                    </h3>
                    <p class="text-blue-600 font-semibold text-base sm:text-lg mb-5">
                        {{ $ceoProfile->position }}
                    </p>
                    
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                        {{ $ceoProfile->full_bio ?? $ceoProfile->short_bio }}
                    </p>

                    {{-- Core Expertise Pills --}}
                    <div class="mb-8">
                        <span class="text-xs font-mono font-bold text-slate-400 uppercase tracking-wider block mb-3">Core Expertise & Capabilities</span>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-xs px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 font-medium shadow-2xs">System Architecture</span>
                            <span class="text-xs px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 font-medium shadow-2xs">Custom Software Engineering</span>
                            <span class="text-xs px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 font-medium shadow-2xs">SaaS & Multi-Tenant Platform</span>
                            <span class="text-xs px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 font-medium shadow-2xs">Business Automation & AI</span>
                            <span class="text-xs px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 font-medium shadow-2xs">IT Project Management</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-4 pt-2 border-t border-slate-200/80">
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Deni%20Afrizal,%20saya%20ingin%20konsultasi%20langsung%20mengenai%20arsitektur%20sistem%20bisnis%20saya."
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-sm bg-emerald-50 text-emerald-800 border border-emerald-300/90 hover:bg-emerald-100 hover:border-emerald-400 shadow-sm transition-all duration-200 group">
                            <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span>Konsultasi Langsung via WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endif

{{-- Final Conversion CTA (Black, Blue & Red Luxury Mesh — Matching Home Mulai Transformasi) --}}
<section class="py-24 lg:py-32 relative bg-[#060810] text-white overflow-hidden" id="contact">
    {{-- Ambient Luxury Lighting: Black base + Royal Blue & Crimson Red Mesh Spheres --}}
    <div class="absolute -top-32 -left-20 w-[520px] h-[520px] bg-gradient-to-br from-blue-600/30 via-indigo-600/20 to-transparent rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-20 w-[560px] h-[560px] bg-gradient-to-tl from-rose-600/25 via-red-600/20 to-transparent rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-radial from-blue-600/15 via-red-700/10 to-transparent blur-[120px] pointer-events-none"></div>

    {{-- Subtle Masked Grid --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] pointer-events-none opacity-60"></div>

    <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        {{-- Section Eyebrow with Blue/Red Ambient Frame --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-blue-950/80 via-slate-900/90 to-red-950/80 border border-white/15 text-xs font-mono font-bold tracking-wider mb-6 shadow-lg reveal">
            <span class="w-2 h-2 rounded-full bg-gradient-to-r from-blue-400 to-rose-500 animate-pulse"></span>
            <span class="bg-gradient-to-r from-blue-300 via-white to-rose-300 bg-clip-text text-transparent uppercase">Mulai Transformasi</span>
        </div>
        
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal reveal-delay-1">
            Siap Membangun <span class="bg-gradient-to-r from-blue-400 via-indigo-200 to-rose-400 bg-clip-text text-transparent">Sistem Digital Anda?</span>
        </h2>
        
        <p class="text-slate-300 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed reveal reveal-delay-2">
            Mari jadwalkan sesi konsultasi gratis untuk mendiskusikan visi, rancangan arsitektur, dan kebutuhan teknis bisnis Anda.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 reveal reveal-delay-3">
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20ingin%20berkonsultasi%20mengenai%20kebutuhan%20teknologi%20perusahaan%20kami."
               target="_blank" rel="noopener"
               class="btn-primary btn-lg shadow-xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-600 border border-blue-400/30">
                <span>Mulai Konsultasi Gratis</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn-ghost-light btn-lg border border-white/20 hover:border-white/40">
                <span>Isi Form Konsultasi</span>
            </a>
        </div>
    </div>
</section>
@endsection
