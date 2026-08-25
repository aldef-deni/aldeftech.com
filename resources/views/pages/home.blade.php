@extends('layouts.app')

@section('content')
@php
$pageTitle = 'Aldef Tech — Premium Software Engineering, SaaS & AI Studio';
$metaDescription = 'Aldef Tech adalah software development partner terpercaya untuk custom business systems, SaaS platforms, AI solutions, dan otomasi proses bisnis.';
@endphp

{{-- ============================================================
     1. HERO SECTION (ELEGANT & PREMIUM TECH HERO)
     ============================================================ --}}
<section class="hero-premium-dark relative overflow-hidden pt-14 pb-24 lg:pt-24 lg:pb-36 border-b border-slate-800/80">
    {{-- Ambient Lighting & Grid Overlay --}}
    <div class="absolute inset-0 hero-grid-dark pointer-events-none"></div>
    <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-blue-600/20 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-cyan-500/15 blur-[130px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            {{-- Left Column: Copy & Actions --}}
            <div class="lg:col-span-7">
                {{-- Status Pill --}}
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.07] border border-white/15 backdrop-blur-md shadow-2xs mb-6 reveal">
                    <span class="status-dot status-dot-pulse"></span>
                    <span class="text-xs font-semibold text-blue-200 tracking-wide uppercase">Software Engineering & AI Technology</span>
                </div>

                {{-- Main Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-[3.75rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                    We Build Digital Products That <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Move Your Business Forward.</span>
                </h1>

                {{-- Subheadline --}}
                <p class="text-slate-300 text-lg lg:text-xl leading-relaxed max-w-2xl mb-8 reveal reveal-delay-2">
                    Custom software, scalable SaaS platforms, AI solutions, and intelligent business automation engineered around the exact way your business operates.
                </p>

                {{-- Actions --}}
                <div class="flex flex-wrap items-center gap-4 mb-12 reveal reveal-delay-3">
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-xl shadow-blue-500/25 group">
                        <span>Konsultasi Proyek Gratis</span>
                        <svg class="w-4 h-4 ml-0.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#portfolio" class="btn-ghost-light btn-lg">
                        <span>Lihat Portofolio</span>
                    </a>
                </div>

                {{-- Key Trust Metrics --}}
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-white/10 reveal reveal-delay-4">
                    <div>
                        <div class="text-2xl lg:text-3xl font-display font-bold text-white tracking-tight">99.9%</div>
                        <div class="text-xs font-medium text-slate-400 mt-0.5">Uptime Architecture</div>
                    </div>
                    <div>
                        <div class="text-2xl lg:text-3xl font-display font-bold text-white tracking-tight">&lt; 45ms</div>
                        <div class="text-xs font-medium text-slate-400 mt-0.5">P99 API Latency</div>
                    </div>
                    <div>
                        <div class="text-2xl lg:text-3xl font-display font-bold text-white tracking-tight">100%</div>
                        <div class="text-xs font-medium text-slate-400 mt-0.5">Custom Tailored</div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Interactive System Architecture Mockup --}}
            <div class="lg:col-span-5 relative reveal-scale reveal-delay-2">
                {{-- Floating Badges --}}
                <div class="float-badge float-badge-1 hidden sm:flex">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">⚙️</span>
                    <span>Custom System Architecture</span>
                </div>
                <div class="float-badge float-badge-2 hidden sm:flex">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">🔒</span>
                    <span>Enterprise Grade Security</span>
                </div>

                {{-- Mockup Window Container --}}
                <div class="relative bg-[#0D1527]/90 backdrop-blur-2xl border border-white/15 rounded-2xl shadow-2xl p-5 sm:p-6 overflow-hidden">
                    {{-- Top Window Controls --}}
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-white/10">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="px-3 py-1 rounded-full bg-white/[0.06] border border-white/10 text-[0.6875rem] font-mono text-cyan-300">
                            aldef-core-cluster // live
                        </div>
                    </div>

                    {{-- Live Dashboard Metrics --}}
                    <div class="space-y-4 font-mono text-xs">
                        {{-- Pipeline Status Card --}}
                        <div class="p-4 rounded-xl bg-white/[0.04] border border-white/10">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-slate-300 font-sans font-semibold">AI Workflow Pipeline</span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[0.65rem] font-bold">ACTIVE</span>
                            </div>
                            <div class="text-white font-semibold font-sans text-sm mb-1">Automated Invoice & Data Extraction</div>
                            <div class="text-slate-400 text-[0.6875rem]">Model: Aldef-OCR-Engine v2.4 • Accuracy: 99.8%</div>
                        </div>

                        {{-- Microservice Metrics Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-xl bg-white/[0.04] border border-white/10">
                                <div class="text-[0.6875rem] text-slate-400 font-sans">API Response</div>
                                <div class="text-base font-bold text-white font-sans mt-0.5">32ms</div>
                                <div class="text-[0.65rem] text-emerald-400 font-semibold mt-1">● Optimal P99</div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-white/[0.04] border border-white/10">
                                <div class="text-[0.6875rem] text-slate-400 font-sans">Database Sync</div>
                                <div class="text-base font-bold text-white font-sans mt-0.5">0.02s lag</div>
                                <div class="text-[0.65rem] text-cyan-400 font-semibold mt-1">● Multi-Region</div>
                            </div>
                        </div>

                        {{-- Code Diagnostic Snippet --}}
                        <div class="p-3.5 rounded-xl bg-[#060913] text-slate-300 font-mono text-[0.6875rem] leading-relaxed border border-white/10">
                            <div class="text-blue-400">// Aldef Tech Scalable Architecture</div>
                            <div class="text-slate-400">$service-&gt;deploy(<span class="text-emerald-300">'custom_erp'</span>, [</div>
                            <div class="pl-3 text-slate-300"><span class="text-indigo-300">'cache'</span> =&gt; <span class="text-amber-300">'Redis::cluster'</span>,</div>
                            <div class="pl-3 text-slate-300"><span class="text-indigo-300">'queue'</span> =&gt; <span class="text-amber-300">'Horizon::parallel'</span></div>
                            <div class="text-slate-400">]); <span class="text-emerald-400">// Status: 200 OK</span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================
     2. TECHNOLOGY MARQUEE TRACK
     ============================================================ --}}
<section class="py-9 bg-slate-50/80 border-b border-slate-200/70 overflow-hidden">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 mb-3 text-center">
        <p class="text-[0.6875rem] font-display font-bold uppercase tracking-[0.2em] text-slate-600">
            Enterprise Technology Stack & Modern Ecosystem
        </p>
    </div>

    <div class="marquee-wrapper">
        <div class="marquee-content">
            @php
            $techStack = [
                'Laravel 11+', 'PHP 8.3', 'Python', 'OpenAI & AI Agents', 'Vue.js 3', 'React', 'Tailwind CSS',
                'PostgreSQL', 'MySQL', 'Redis', 'Docker', 'REST & GraphQL APIs', 'AWS & Cloud Infrastructure',
                'FastAPI', 'Microservices', 'Laravel 11+', 'PHP 8.3', 'Python', 'OpenAI & AI Agents', 'Vue.js 3', 'React', 'Tailwind CSS',
                'PostgreSQL', 'MySQL', 'Redis', 'Docker', 'REST & GraphQL APIs', 'AWS & Cloud Infrastructure'
            ];
            @endphp
            @foreach($techStack as $tech)
            <span class="inline-flex items-center gap-2 font-display font-bold text-sm text-slate-600 tracking-tight">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500/70"></span>
                {{ $tech }}
            </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     3. CORE SERVICES (01 - 06)
     ============================================================ --}}
<section class="section-padding bg-white relative" id="services">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        {{-- Section Header --}}
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20">
            <span class="section-eyebrow justify-center reveal">Core Capabilities</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight mb-5 reveal reveal-delay-1">
                Solusi Lengkap dari Analisis hingga Rilis
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Kami menangani seluruh siklus rekayasa perangkat lunak, sehingga Anda dapat fokus sepenuhnya pada akselerasi bisnis.
            </p>
        </div>

        {{-- Services Grid (6 Core Services with Soft Colors & Elevated 3D Look) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $coreServices = [
                [
                    'num' => '01',
                    'title' => 'Custom Software Development',
                    'desc' => 'Perancangan sistem enterprise khusus yang disesuaikan 100% dengan alur operasional dan aturan bisnis perusahaan Anda.',
                    'features' => ['Analisis Proses Bisnis', 'Arsitektur Database Terukur', 'Clean Code & Dokumentasi', 'API Integration Ready'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#F0F7FF] to-[#E6F1FD] border-[#BFDBFE]/80 shadow-[0_14px_34px_-8px_rgba(37,99,235,0.16)] hover:shadow-[0_24px_48px_-10px_rgba(37,99,235,0.28)] hover:border-blue-400',
                    'icon_bg' => 'bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-md shadow-blue-500/30',
                    'num_class' => 'text-blue-700 bg-blue-100/90 border border-blue-200',
                    'accent_hover' => 'group-hover:text-blue-600',
                    'btn_class' => 'text-blue-600 hover:text-blue-800'
                ],
                [
                    'num' => '02',
                    'title' => 'SaaS Platform Engineering',
                    'desc' => 'Membangun platform SaaS dari tahap MVP hingga production skala besar dengan arsitektur multi-tenant dan billing terintegrasi.',
                    'features' => ['Multi-tenant Architecture', 'Subscription & Payment Gateway', 'Role & Permission Granular', 'Admin Telemetry Dashboard'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#F5F3FF] to-[#ECE7FD] border-[#DDD6FE]/80 shadow-[0_14px_34px_-8px_rgba(99,102,241,0.16)] hover:shadow-[0_24px_48px_-10px_rgba(99,102,241,0.28)] hover:border-indigo-400',
                    'icon_bg' => 'bg-gradient-to-br from-indigo-500 to-indigo-700 text-white shadow-md shadow-indigo-500/30',
                    'num_class' => 'text-indigo-700 bg-indigo-100/90 border border-indigo-200',
                    'accent_hover' => 'group-hover:text-indigo-600',
                    'btn_class' => 'text-indigo-600 hover:text-indigo-800'
                ],
                [
                    'num' => '03',
                    'title' => 'AI & Intelligent Automation',
                    'desc' => 'Integrasi AI Agent, LLM, knowledge base perusahaan, dan asisten cerdas untuk mengotomasi interaksi serta keputusan operasional.',
                    'features' => ['AI Chatbot & Knowledge Base', 'OCR & Document Processing', 'Autonomous AI Agents', 'Data Sentiment & Prediction'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#FAF5FF] to-[#F3E8FF] border-[#E9D5FF]/80 shadow-[0_14px_34px_-8px_rgba(168,85,247,0.16)] hover:shadow-[0_24px_48px_-10px_rgba(168,85,247,0.28)] hover:border-purple-400',
                    'icon_bg' => 'bg-gradient-to-br from-purple-500 to-purple-700 text-white shadow-md shadow-purple-500/30',
                    'num_class' => 'text-purple-700 bg-purple-100/90 border border-purple-200',
                    'accent_hover' => 'group-hover:text-purple-600',
                    'btn_class' => 'text-purple-600 hover:text-purple-800'
                ],
                [
                    'num' => '04',
                    'title' => 'Business Process Automation',
                    'desc' => 'Menghilangkan pekerjaan manual yang berulang melalui integrasi alur kerja otomatis antar berbagai divisi dan platform.',
                    'features' => ['Automated Invoicing & Sync', 'Multi-System Data Pipeline', 'WhatsApp & Email Triggers', 'Real-time Approval Flow'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#FFFDF5] to-[#FEF3C7] border-[#FDE68A]/80 shadow-[0_14px_34px_-8px_rgba(217,119,6,0.16)] hover:shadow-[0_24px_48px_-10px_rgba(217,119,6,0.28)] hover:border-amber-400',
                    'icon_bg' => 'bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-md shadow-amber-500/30',
                    'num_class' => 'text-amber-800 bg-amber-100/90 border border-amber-200',
                    'accent_hover' => 'group-hover:text-amber-600',
                    'btn_class' => 'text-amber-700 hover:text-amber-900'
                ],
                [
                    'num' => '05',
                    'title' => 'Web Application Development',
                    'desc' => 'Aplikasi web interaktif dengan performa tinggi, responsive di setiap ukuran layar, dan keamanan berlapis untuk bisnis modern.',
                    'features' => ['Single Page Applications', 'PWA & Mobile-First UX', 'Zero-Downtime Deployment', 'Automated Testing & QA'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#F0FDF4] to-[#DCFCE7] border-[#BBF7D0]/80 shadow-[0_14px_34px_-8px_rgba(16,185,129,0.16)] hover:shadow-[0_24px_48px_-10px_rgba(16,185,129,0.28)] hover:border-emerald-400',
                    'icon_bg' => 'bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-500/30',
                    'num_class' => 'text-emerald-800 bg-emerald-100/90 border border-emerald-200',
                    'accent_hover' => 'group-hover:text-emerald-600',
                    'btn_class' => 'text-emerald-700 hover:text-emerald-900'
                ],
                [
                    'num' => '06',
                    'title' => 'System Integration & APIs',
                    'desc' => 'Menghubungkan ekosistem perangkat lunak Anda dengan payment gateway, logistik, WhatsApp API, ERP pusat, dan pihak ketiga.',
                    'features' => ['Payment Gateway & Banking', 'WhatsApp Business API', 'PPOB & Third-party Sync', 'Webhook & Event Brokers'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#ECFEFF] to-[#CEFAFE] border-[#A5F3FC]/80 shadow-[0_14px_34px_-8px_rgba(6,182,212,0.16)] hover:shadow-[0_24px_48px_-10px_rgba(6,182,212,0.28)] hover:border-cyan-400',
                    'icon_bg' => 'bg-gradient-to-br from-cyan-500 to-cyan-700 text-white shadow-md shadow-cyan-500/30',
                    'num_class' => 'text-cyan-800 bg-cyan-100/90 border border-cyan-200',
                    'accent_hover' => 'group-hover:text-cyan-600',
                    'btn_class' => 'text-cyan-700 hover:text-cyan-900'
                ],
            ];
            @endphp

            @foreach($coreServices as $s)
            <div class="rounded-3xl border p-8 lg:p-9 flex flex-col justify-between group transition-all duration-300 hover:-translate-y-2 reveal {{ $s['bg_class'] }}">
                <div>
                    {{-- Top Number & Icon --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-13 h-13 rounded-2xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 {{ $s['icon_bg'] }}">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $s['icon'] !!}</svg>
                        </div>
                        <span class="text-xs font-mono font-bold px-3 py-1 rounded-full {{ $s['num_class'] }} tracking-wider">{{ $s['num'] }}</span>
                    </div>

                    {{-- Title & Description --}}
                    <h3 class="text-xl font-display font-bold text-slate-900 mb-3 transition-colors {{ $s['accent_hover'] }}">
                        {{ $s['title'] }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        {{ $s['desc'] }}
                    </p>

                    {{-- Feature Checklist (Soft White Elevated Pills) --}}
                    <div class="space-y-2 mb-8">
                        @foreach($s['features'] as $feat)
                        <div class="flex items-center gap-2.5 text-xs text-slate-700 font-medium bg-white/85 backdrop-blur-xs border border-white/80 px-3.5 py-2.5 rounded-xl shadow-2xs">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $feat }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Action Link --}}
                <div class="pt-5 border-t border-black/5 flex items-center justify-between">
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider transition-colors {{ $s['btn_class'] }}">
                        <span>Detail Layanan</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20konsultasi%20layanan%20{{ urlencode($s['title']) }}" target="_blank" rel="noopener" class="text-[0.6875rem] font-semibold text-slate-500 hover:text-slate-800">
                        Konsultasi →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     4. WHY ALDEF TECH / THE ENGINEERING ADVANTAGE (SOFT PURPLE)
     ============================================================ --}}
<section class="section-padding bg-gradient-to-b from-[#FAF5FF] via-[#F5EEFB] to-[#FAF5FF] relative border-y border-purple-200/70 overflow-hidden" id="why-us">
    {{-- Subtle Ambient Purple Glow --}}
    <div class="absolute -top-32 -left-20 w-[450px] h-[450px] bg-purple-300/20 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-20 w-[450px] h-[450px] bg-fuchsia-300/15 blur-[130px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center mb-16">
            
            {{-- Left Column: Visual Diagnostic Card --}}
            <div class="lg:col-span-5 reveal">
                <div class="bg-white border border-slate-200/90 rounded-2xl p-7 shadow-elevated">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                            AT
                        </div>
                        <div>
                            <div class="font-display font-bold text-slate-900 text-sm">The Aldef Tech Standard</div>
                            <div class="text-[0.6875rem] text-slate-500 font-medium">Enterprise Software Quality Checklist</div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-100 flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <div class="text-xs font-bold text-slate-900">Zero Technical Debt Strategy</div>
                                <div class="text-[0.6875rem] text-slate-600 mt-0.5">Kode terstruktur rapi dan mudah dilanjutkan oleh developer internal Anda.</div>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-100 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <div>
                                <div class="text-xs font-bold text-slate-900">Strict Data Privacy & Security</div>
                                <div class="text-[0.6875rem] text-slate-600 mt-0.5">Enkripsi data, sanitasi input ketat, dan perlindungan kerentanan OWASP Top 10.</div>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-indigo-50/70 border border-indigo-100 flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <div>
                                <div class="text-xs font-bold text-slate-900">High-Performance Benchmarking</div>
                                <div class="text-[0.6875rem] text-slate-600 mt-0.5">Query optimization, Redis caching, dan index database untuk kecepatan instan.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Copywriting & Pillars --}}
            <div class="lg:col-span-7 reveal-right">
                <span class="section-eyebrow">The Engineering Advantage</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
                    Bukan Sekadar Coding — Kami Bangun Solusi Bisnis
                </h2>
                <p class="text-slate-600 text-base lg:text-lg leading-relaxed mb-8">
                    Teknologi hanyalah sarana. Yang terpenting adalah dampaknya terhadap pertumbuhan bisnis Anda: alur kerja menjadi lebih ringkas, biaya operasional berkurang, dan produktivitas tim meningkat secara nyata.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">✓</div>
                        <div>
                            <h4 class="font-display font-bold text-slate-900 text-sm">Business-First Approach</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Merancang sistem berdasarkan pemahaman alur kerja nyata bisnis Anda.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">✓</div>
                        <div>
                            <h4 class="font-display font-bold text-slate-900 text-sm">Transparent Communication</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Update progres berkala dan sprint review tanpa istilah teknis membingungkan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">✓</div>
                        <div>
                            <h4 class="font-display font-bold text-slate-900 text-sm">On-Time & On-Budget</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Ruang lingkup dan estimasi disepakati di awal tanpa biaya tersembunyi.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">✓</div>
                        <div>
                            <h4 class="font-display font-bold text-slate-900 text-sm">Post-Launch Warranty</h4>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">Dukungan teknis dan garansi operasional pasca-rilis sistem.</p>
                        </div>
                    </div>
                </div>

                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-md">
                    <span>Ajak Diskusi Kebutuhan Anda</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

        </div>

        {{-- 3 Guarantee Badges (Soft Colored, 3D Elevated with Hover Motion) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-10 border-t border-slate-200/80">
            {{-- Guarantee 1 --}}
            <div class="rounded-3xl border border-[#BFDBFE]/80 bg-gradient-to-b from-[#F0F7FF] to-[#E6F1FD] p-7 shadow-[0_14px_34px_-8px_rgba(37,99,235,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(37,99,235,0.28)] hover:-translate-y-2 hover:border-blue-400 transition-all duration-300 group reveal">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center shadow-md shadow-blue-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-[0.6875rem] font-mono font-bold px-2.5 py-1 rounded-full text-blue-700 bg-blue-100/90 border border-blue-200">100% IP</span>
                </div>
                <h4 class="font-display font-bold text-slate-900 text-base mb-2 group-hover:text-blue-600 transition-colors">
                    100% Code & IP Ownership
                </h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Seluruh source code, arsitektur database, dan aset digital sepenuhnya menjadi milik perusahaan Anda tanpa lisensi terikat.
                </p>
            </div>

            {{-- Guarantee 2 --}}
            <div class="rounded-3xl border border-[#E9D5FF]/80 bg-gradient-to-b from-[#FAF5FF] to-[#F3E8FF] p-7 shadow-[0_14px_34px_-8px_rgba(168,85,247,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(168,85,247,0.28)] hover:-translate-y-2 hover:border-purple-400 transition-all duration-300 group reveal reveal-delay-1">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 text-white flex items-center justify-center shadow-md shadow-purple-500/25 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-[0.6875rem] font-mono font-bold px-2.5 py-1 rounded-full text-purple-700 bg-purple-100/90 border border-purple-200">ON TIME</span>
                </div>
                <h4 class="font-display font-bold text-slate-900 text-base mb-2 group-hover:text-purple-600 transition-colors">
                    Milestone On-Time Guarantee
                </h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Jadwal pengerjaan bertahap dengan sprint review berkala untuk memastikan setiap milestone diserahkan tepat waktu.
                </p>
            </div>

            {{-- Guarantee 3 --}}
            <div class="rounded-3xl border border-[#FDE68A]/80 bg-gradient-to-b from-[#FFFDF5] to-[#FEF3C7] p-7 shadow-[0_14px_34px_-8px_rgba(217,119,6,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(217,119,6,0.28)] hover:-translate-y-2 hover:border-amber-400 transition-all duration-300 group reveal reveal-delay-2">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white flex items-center justify-center shadow-md shadow-amber-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-[0.6875rem] font-mono font-bold px-2.5 py-1 rounded-full text-amber-800 bg-amber-100/90 border border-amber-200">SLA 24/7</span>
                </div>
                <h4 class="font-display font-bold text-slate-900 text-base mb-2 group-hover:text-amber-700 transition-colors">
                    Post-Launch SLA & Support
                </h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pendampingan teknis penuh pasca-rilis, migrasi data, pelatihan staf, serta pemeliharaan keamanan berkala.
                </p>
            </div>
        </div>

    </div>
</section>

{{-- ============================================================
     5. HOW WE WORK (01 - 06 STRUCTURED ROADMAP)
     ============================================================ --}}
<section class="section-padding bg-white relative" id="process">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20">
            <span class="section-eyebrow justify-center reveal">Methodology</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight mb-5 reveal reveal-delay-1">
                Proses Pengembangan Terukur & Transparan
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Enam langkah terstruktur dari eksplorasi ide pertama hingga sistem Anda mengudara dan beroperasi di production.
            </p>
        </div>

        {{-- Methodology Grid (6 Structured Steps) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $workflowSteps = [
                [
                    'num' => '01',
                    'title' => 'Discovery & Requirements',
                    'desc' => 'Memahami pain point bisnis, model data, dan target luaran sistem melalui sesi interview mendalam.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#F0F9FF] to-[#E0F2FE] border-[#BAE6FD]/80 shadow-[0_14px_34px_-8px_rgba(14,165,233,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(14,165,233,0.28)] hover:border-sky-400',
                    'badge_bg' => 'bg-gradient-to-br from-sky-500 to-sky-700 text-white shadow-md shadow-sky-500/25',
                    'num_pill' => 'text-sky-700 bg-sky-100/90 border border-sky-200',
                    'accent_hover' => 'group-hover:text-sky-600'
                ],
                [
                    'num' => '02',
                    'title' => 'Architecture & Strategy',
                    'desc' => 'Merancang skema database, pemetaan API, diagram alur sistem, dan pemilihan teknologi yang tepat.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#F0F7FF] to-[#E6F1FD] border-[#BFDBFE]/80 shadow-[0_14px_34px_-8px_rgba(37,99,235,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(37,99,235,0.28)] hover:border-blue-400',
                    'badge_bg' => 'bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-md shadow-blue-500/25',
                    'num_pill' => 'text-blue-700 bg-blue-100/90 border border-blue-200',
                    'accent_hover' => 'group-hover:text-blue-600'
                ],
                [
                    'num' => '03',
                    'title' => 'UI/UX & Prototyping',
                    'desc' => 'Mendesain antarmuka yang intuitif dan mudah dipelajari oleh staf operasional maupun pengguna umum.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#F5F3FF] to-[#ECE7FD] border-[#DDD6FE]/80 shadow-[0_14px_34px_-8px_rgba(99,102,241,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(99,102,241,0.28)] hover:border-indigo-400',
                    'badge_bg' => 'bg-gradient-to-br from-indigo-500 to-indigo-700 text-white shadow-md shadow-indigo-500/25',
                    'num_pill' => 'text-indigo-700 bg-indigo-100/90 border border-indigo-200',
                    'accent_hover' => 'group-hover:text-indigo-600'
                ],
                [
                    'num' => '04',
                    'title' => 'Agile Engineering',
                    'desc' => 'Membangun fungsionalitas dengan metodologi agile sprint dan update berkala setiap milestone.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#FAF5FF] to-[#F3E8FF] border-[#E9D5FF]/80 shadow-[0_14px_34px_-8px_rgba(168,85,247,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(168,85,247,0.28)] hover:border-purple-400',
                    'badge_bg' => 'bg-gradient-to-br from-purple-500 to-purple-700 text-white shadow-md shadow-purple-500/25',
                    'num_pill' => 'text-purple-700 bg-purple-100/90 border border-purple-200',
                    'accent_hover' => 'group-hover:text-purple-600'
                ],
                [
                    'num' => '05',
                    'title' => 'Testing & Quality Assurance',
                    'desc' => 'Validasi keamanan data, pengetesan performa beban tinggi, dan User Acceptance Testing (UAT).',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#FFFDF5] to-[#FEF3C7] border-[#FDE68A]/80 shadow-[0_14px_34px_-8px_rgba(217,119,6,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(217,119,6,0.28)] hover:border-amber-400',
                    'badge_bg' => 'bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-md shadow-amber-500/25',
                    'num_pill' => 'text-amber-800 bg-amber-100/90 border border-amber-200',
                    'accent_hover' => 'group-hover:text-amber-600'
                ],
                [
                    'num' => '06',
                    'title' => 'Launch, SLA & Scaling',
                    'desc' => 'Deployment ke server production, pendampingan go-live, pelatihan pengguna, dan maintenance berkesinambungan.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                    'bg_class' => 'bg-gradient-to-b from-[#F0FDF4] to-[#DCFCE7] border-[#BBF7D0]/80 shadow-[0_14px_34px_-8px_rgba(16,185,129,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(16,185,129,0.28)] hover:border-emerald-400',
                    'badge_bg' => 'bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-500/25',
                    'num_pill' => 'text-emerald-800 bg-emerald-100/90 border border-emerald-200',
                    'accent_hover' => 'group-hover:text-emerald-600'
                ],
            ];
            @endphp

            @foreach($workflowSteps as $st)
            <div class="rounded-3xl border p-8 lg:p-9 flex flex-col justify-between group transition-all duration-300 hover:-translate-y-2 reveal {{ $st['bg_class'] }}">
                <div>
                    {{-- Top Step Row --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 {{ $st['badge_bg'] }}">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $st['icon'] !!}</svg>
                        </div>
                        <span class="text-xs font-mono font-bold px-3 py-1 rounded-full {{ $st['num_pill'] }} tracking-wider">STEP {{ $st['num'] }}</span>
                    </div>

                    {{-- Title & Description --}}
                    <h3 class="text-lg lg:text-xl font-display font-bold text-slate-900 mb-3 transition-colors {{ $st['accent_hover'] }}">
                        {{ $st['title'] }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $st['desc'] }}
                    </p>
                </div>

                <div class="pt-5 mt-6 border-t border-black/5 flex items-center justify-between text-xs font-medium text-slate-500">
                    <span>Phase {{ $st['num'] }}</span>
                    <span class="font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">Standard SLA →</span>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     6. THE PROJECT (FEATURED PORTFOLIO)
     ============================================================ --}}
<section class="section-padding bg-slate-50/80 border-y border-slate-200/80" id="portfolio">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <span class="section-eyebrow reveal">The Project</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight reveal reveal-delay-1">
                    Karya & Sistem Nyata yang Kami Bangun
                </h2>
                <p class="text-slate-600 text-base lg:text-lg mt-3 max-w-2xl reveal reveal-delay-2">
                    Showcase sistem enterprise, platform OTA, dan POS modern yang terbukti mengakselerasi transaksi serta operasional bisnis klien kami.
                </p>
            </div>
            <a href="{{ route('portfolio') }}" class="btn-secondary self-start md:self-auto reveal">
                <span>Lihat Semua Portfolio</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @php
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

        {{-- 3 Featured Project Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredProjects as $p)
            <div class="rounded-3xl border overflow-hidden flex flex-col justify-between group transition-all duration-300 hover:-translate-y-2 reveal {{ $p['bg_class'] }}">
                <div>
                    {{-- Featured Image with Zoom Effect --}}
                    <div class="aspect-[16/10] bg-slate-900/5 relative overflow-hidden border-b border-black/5">
                        <img src="{{ asset($p['image']) }}" alt="{{ $p['title'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-108" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-full text-[0.6875rem] font-mono font-bold backdrop-blur-md shadow-xs {{ $p['pill_class'] }}">
                            {{ $p['category'] }}
                        </span>
                    </div>

                    {{-- Body Content --}}
                    <div class="p-7">
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
                    <a href="{{ route('portfolio') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider transition-colors {{ $p['btn_class'] }}">
                        <span>Lihat Portfolio</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20dengan%20proyek%20serupa%20{{ urlencode($p['title']) }}" target="_blank" rel="noopener" class="text-[0.6875rem] font-semibold text-slate-500 hover:text-slate-800">
                        Diskusi Serupa →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     7. BUSINESS SOLUTIONS MATRIX
     ============================================================ --}}
<section class="section-padding bg-white relative" id="solutions">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        <div class="max-w-3xl mx-auto text-center mb-16 lg:mb-20">
            <span class="section-eyebrow justify-center reveal">Enterprise Solutions</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight mb-5 reveal reveal-delay-1">
                Sistem Bisnis Siap Adaptasi
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Fondasi arsitektur siap pakai yang kami kustomisasi secara mendalam sesuai model bisnis spesifik perusahaan Anda.
            </p>
        </div>

        @php
        $solutionColors = [
            [
                'bg' => 'bg-gradient-to-b from-[#F0F7FF] to-[#E6F1FD] border-[#BFDBFE]/80 shadow-[0_12px_30px_-8px_rgba(37,99,235,0.14)] hover:shadow-[0_22px_44px_-10px_rgba(37,99,235,0.25)] hover:border-blue-400',
                'icon_box' => 'bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-md shadow-blue-500/25',
                'accent_hover' => 'group-hover:text-blue-600',
                'badge' => 'text-blue-700 bg-blue-100/90 border border-blue-200',
                'btn_class' => 'text-blue-600 hover:text-blue-800'
            ],
            [
                'bg' => 'bg-gradient-to-b from-[#F5F3FF] to-[#ECE7FD] border-[#DDD6FE]/80 shadow-[0_12px_30px_-8px_rgba(99,102,241,0.14)] hover:shadow-[0_22px_44px_-10px_rgba(99,102,241,0.25)] hover:border-indigo-400',
                'icon_box' => 'bg-gradient-to-br from-indigo-500 to-indigo-700 text-white shadow-md shadow-indigo-500/25',
                'accent_hover' => 'group-hover:text-indigo-600',
                'badge' => 'text-indigo-700 bg-indigo-100/90 border border-indigo-200',
                'btn_class' => 'text-indigo-600 hover:text-indigo-800'
            ],
            [
                'bg' => 'bg-gradient-to-b from-[#FAF5FF] to-[#F3E8FF] border-[#E9D5FF]/80 shadow-[0_12px_30px_-8px_rgba(168,85,247,0.14)] hover:shadow-[0_22px_44px_-10px_rgba(168,85,247,0.25)] hover:border-purple-400',
                'icon_box' => 'bg-gradient-to-br from-purple-500 to-purple-700 text-white shadow-md shadow-purple-500/25',
                'accent_hover' => 'group-hover:text-purple-600',
                'badge' => 'text-purple-700 bg-purple-100/90 border border-purple-200',
                'btn_class' => 'text-purple-600 hover:text-purple-800'
            ],
            [
                'bg' => 'bg-gradient-to-b from-[#FFFDF5] to-[#FEF3C7] border-[#FDE68A]/80 shadow-[0_12px_30px_-8px_rgba(217,119,6,0.14)] hover:shadow-[0_22px_44px_-10px_rgba(217,119,6,0.25)] hover:border-amber-400',
                'icon_box' => 'bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-md shadow-amber-500/25',
                'accent_hover' => 'group-hover:text-amber-600',
                'badge' => 'text-amber-800 bg-amber-100/90 border border-amber-200',
                'btn_class' => 'text-amber-700 hover:text-amber-900'
            ],
            [
                'bg' => 'bg-gradient-to-b from-[#F0FDF4] to-[#DCFCE7] border-[#BBF7D0]/80 shadow-[0_12px_30px_-8px_rgba(16,185,129,0.14)] hover:shadow-[0_22px_44px_-10px_rgba(16,185,129,0.25)] hover:border-emerald-400',
                'icon_box' => 'bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-500/25',
                'accent_hover' => 'group-hover:text-emerald-600',
                'badge' => 'text-emerald-800 bg-emerald-100/90 border border-emerald-200',
                'btn_class' => 'text-emerald-700 hover:text-emerald-900'
            ],
            [
                'bg' => 'bg-gradient-to-b from-[#ECFEFF] to-[#CEFAFE] border-[#A5F3FC]/80 shadow-[0_12px_30px_-8px_rgba(6,182,212,0.14)] hover:shadow-[0_22px_44px_-10px_rgba(6,182,212,0.25)] hover:border-cyan-400',
                'icon_box' => 'bg-gradient-to-br from-cyan-500 to-cyan-700 text-white shadow-md shadow-cyan-500/25',
                'accent_hover' => 'group-hover:text-cyan-600',
                'badge' => 'text-cyan-800 bg-cyan-100/90 border border-cyan-200',
                'btn_class' => 'text-cyan-700 hover:text-cyan-900'
            ],
        ];
        @php
        $filteredSolutions = $solutions->reject(function($sol) {
            $t = strtolower($sol->title ?? '');
            $s = strtolower($sol->slug ?? '');
            return str_contains($t, 'dashboard') || str_contains($s, 'dashboard')
                || str_contains($t, 'customer service') || str_contains($s, 'customer-service') || str_contains($t, 'ai cs')
                || str_contains($t, 'custom system') || str_contains($s, 'custom-system')
                || str_contains($t, 'business automation') || str_contains($s, 'business-automation') || str_contains($t, 'otomasi bisnis') || str_contains($t, 'automasi bisnis');
        })->values();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($filteredSolutions as $index => $sol)
            @php
                $style = $solutionColors[$index % count($solutionColors)];
            @endphp
            <div class="rounded-3xl border p-7 lg:p-8 flex flex-col justify-between group transition-all duration-300 hover:-translate-y-2 reveal {{ $style['bg'] }}">
                <div>
                    {{-- Icon & Badge Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 {{ $style['icon_box'] }}">
                            {{ $sol->icon ?? '⚡' }}
                        </div>
                        <span class="text-[0.6875rem] font-mono font-bold px-2.5 py-1 rounded-full {{ $style['badge'] }}">
                            Enterprise Ready
                        </span>
                    </div>

                    {{-- Title & Description --}}
                    <h3 class="font-display font-bold text-slate-900 text-lg lg:text-xl mb-2.5 transition-colors {{ $style['accent_hover'] }}">
                        {{ $sol->title }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        {{ $sol->short_description }}
                    </p>

                    @if($sol->features && count($sol->features))
                    <div class="space-y-1.5 mb-6">
                        @foreach(array_slice($sol->features, 0, 2) as $feat)
                        <div class="flex items-center gap-2 text-xs text-slate-700 bg-white/80 border border-white/70 px-3 py-1.5 rounded-lg shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-medium truncate">{{ $feat }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Bottom Action Link --}}
                <div class="pt-4 border-t border-black/5 flex items-center justify-between">
                    <a href="{{ route('solutions') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider transition-colors {{ $style['btn_class'] }}">
                        <span>Jelajahi Solusi</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20dengan%20solusi%20{{ urlencode($sol->title) }}" target="_blank" rel="noopener" class="text-[0.6875rem] font-semibold text-slate-500 hover:text-slate-800">
                        Konsultasi →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     8. LEADERSHIP & TECHNICAL ADVISORY (CEO SPOTLIGHT)
     ============================================================ --}}
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
                        <a href="{{ route('about') }}" class="btn-primary">
                            <span>Visi & Metodologi Lengkap</span>
                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Deni%20Afrizal,%20saya%20ingin%20konsultasi%20langsung%20mengenai%20arsitektur%20sistem%20bisnis%20saya." target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-sm bg-emerald-50 text-emerald-800 border border-emerald-300/90 hover:bg-emerald-100 hover:border-emerald-400 shadow-sm transition-all duration-200 group">
                            <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <span>Konsultasi Langsung via WhatsApp</span>
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endif

{{-- ============================================================
     9. FINAL HIGH-CONTRAST CONVERSION CTA (BLACK, BLUE & RED LUXURY BLEND)
     ============================================================ --}}
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
            Have a Business Challenge That <span class="bg-gradient-to-r from-blue-400 via-indigo-200 to-rose-400 bg-clip-text text-transparent">Technology Can Solve?</span>
        </h2>
        
        <p class="text-slate-300 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed reveal reveal-delay-2">
            Mari wujudkan ide, alur kerja, atau tantangan bisnis Anda menjadi aset digital yang andal, scalable, dan berperforma tinggi.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 reveal reveal-delay-3">
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
               class="btn-primary btn-lg shadow-xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-600 border border-blue-400/30">
                <span>Start a Conversation</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn-ghost-light btn-lg border border-white/20 hover:border-white/40">
                <span>Isi Form Konsultasi Proyek</span>
            </a>
        </div>
    </div>
</section>

@endsection
