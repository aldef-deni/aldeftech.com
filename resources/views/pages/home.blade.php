@extends('layouts.app')

@section('content')
@php
$pageTitle = 'Aldef Tech — Premium Software Engineering, SaaS & AI Studio';
$metaDescription = 'Aldef Tech adalah software development partner terpercaya untuk custom business systems, SaaS platforms, AI solutions, dan otomasi proses bisnis.';
@endphp

{{-- ============================================================
     1. HERO SECTION
     ============================================================ --}}
<section class="hero-light-gradient relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-32 border-b border-slate-200/60">
    {{-- Ambient Light Grids --}}
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-gradient-to-tr from-blue-500/10 via-indigo-500/5 to-transparent blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            {{-- Left Column: Copy & Actions --}}
            <div class="lg:col-span-7">
                {{-- Status Pill --}}
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-blue-50/90 border border-blue-200/80 shadow-2xs mb-6 reveal">
                    <span class="status-dot status-dot-pulse"></span>
                    <span class="text-xs font-semibold text-blue-700 tracking-wide uppercase">Software Engineering & AI Studio</span>
                </div>

                {{-- Main Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-[3.65rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                    We Build Digital Products That <span class="text-gradient-blue">Move Your Business Forward.</span>
                </h1>

                {{-- Subheadline --}}
                <p class="text-slate-600 text-lg lg:text-xl leading-relaxed max-w-2xl mb-8 reveal reveal-delay-2">
                    Custom software, scalable SaaS platforms, AI solutions, and intelligent business automation engineered around the exact way your business operates.
                </p>

                {{-- Actions --}}
                <div class="flex flex-wrap items-center gap-4 mb-12 reveal reveal-delay-3">
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-md group">
                        <span>Konsultasi Proyek Gratis</span>
                        <svg class="w-4 h-4 ml-0.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#portfolio" class="btn-secondary btn-lg">
                        <span>Lihat Portofolio</span>
                    </a>
                </div>

                {{-- Key Trust Metrics --}}
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-slate-200/80 reveal reveal-delay-4">
                    <div>
                        <div class="text-2xl lg:text-3xl font-display font-bold text-slate-900">99.9%</div>
                        <div class="text-xs font-medium text-slate-500 mt-0.5">Uptime Architecture</div>
                    </div>
                    <div>
                        <div class="text-2xl lg:text-3xl font-display font-bold text-slate-900">&lt; 45ms</div>
                        <div class="text-xs font-medium text-slate-500 mt-0.5">P99 API Latency</div>
                    </div>
                    <div>
                        <div class="text-2xl lg:text-3xl font-display font-bold text-slate-900">100%</div>
                        <div class="text-xs font-medium text-slate-500 mt-0.5">Custom Tailored</div>
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
                <div class="relative bg-white border border-slate-200/90 rounded-2xl shadow-elevated p-5 sm:p-6 overflow-hidden">
                    {{-- Top Window Controls --}}
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="px-3 py-1 rounded-full bg-slate-50 border border-slate-200 text-[0.6875rem] font-mono text-slate-500">
                            aldef-core-cluster // live
                        </div>
                    </div>

                    {{-- Live Dashboard Metrics --}}
                    <div class="space-y-4 font-mono text-xs">
                        {{-- Pipeline Status Card --}}
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-slate-500 font-sans font-semibold">AI Workflow Pipeline</span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[0.65rem] font-bold">ACTIVE</span>
                            </div>
                            <div class="text-slate-900 font-semibold font-sans text-sm mb-1">Automated Invoice & Data Extraction</div>
                            <div class="text-slate-500 text-[0.6875rem]">Model: Aldef-OCR-Engine v2.4 • Accuracy: 99.8%</div>
                        </div>

                        {{-- Microservice Metrics Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <div class="text-[0.6875rem] text-slate-500 font-sans">API Response</div>
                                <div class="text-base font-bold text-slate-900 font-sans mt-0.5">32ms</div>
                                <div class="text-[0.65rem] text-emerald-600 font-semibold mt-1">● Optimal P99</div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <div class="text-[0.6875rem] text-slate-500 font-sans">Database Sync</div>
                                <div class="text-base font-bold text-slate-900 font-sans mt-0.5">0.02s lag</div>
                                <div class="text-[0.65rem] text-blue-600 font-semibold mt-1">● Multi-Region</div>
                            </div>
                        </div>

                        {{-- Code Diagnostic Snippet --}}
                        <div class="p-3.5 rounded-xl bg-[#0B1020] text-slate-300 font-mono text-[0.6875rem] leading-relaxed">
                            <div class="text-blue-400">// Aldef Tech Scalable Architecture</div>
                            <div class="text-slate-400">$service->deploy(<span class="text-emerald-300">'custom_erp'</span>, [</div>
                            <div class="pl-3 text-slate-300"><span class="text-indigo-300">'cache'</span> => <span class="text-amber-300">'Redis::cluster'</span>,</div>
                            <div class="pl-3 text-slate-300"><span class="text-indigo-300">'queue'</span> => <span class="text-amber-300">'Horizon::parallel'</span></div>
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

        {{-- Services Grid (6 Core Services) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $coreServices = [
                [
                    'num' => '01',
                    'title' => 'Custom Software Development',
                    'desc' => 'Perancangan sistem enterprise khusus yang disesuaikan 100% dengan alur operasional dan aturan bisnis perusahaan Anda.',
                    'features' => ['Analisis Proses Bisnis', 'Arsitektur Database Terukur', 'Clean Code & Dokumentasi', 'API Integration Ready'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>'
                ],
                [
                    'num' => '02',
                    'title' => 'SaaS Platform Engineering',
                    'desc' => 'Membangun platform SaaS dari tahap MVP hingga production skala besar dengan arsitektur multi-tenant dan billing terintegrasi.',
                    'features' => ['Multi-tenant Architecture', 'Subscription & Payment Gateway', 'Role & Permission Granular', 'Admin Telemetry Dashboard'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>'
                ],
                [
                    'num' => '03',
                    'title' => 'AI & Intelligent Automation',
                    'desc' => 'Integrasi AI Agent, LLM, knowledge base perusahaan, dan asisten cerdas untuk mengotomasi interaksi serta keputusan operasional.',
                    'features' => ['AI Chatbot & Knowledge Base', 'OCR & Document Processing', 'Autonomous AI Agents', 'Data Sentiment & Prediction'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>'
                ],
                [
                    'num' => '04',
                    'title' => 'Business Process Automation',
                    'desc' => 'Menghilangkan pekerjaan manual yang berulang melalui integrasi alur kerja otomatis antar berbagai divisi dan platform.',
                    'features' => ['Automated Invoicing & Sync', 'Multi-System Data Pipeline', 'WhatsApp & Email Triggers', 'Real-time Approval Flow'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'
                ],
                [
                    'num' => '05',
                    'title' => 'Web Application Development',
                    'desc' => 'Aplikasi web interaktif dengan performa tinggi, responsive di setiap ukuran layar, dan keamanan berlapis untuk bisnis modern.',
                    'features' => ['Single Page Applications', 'PWA & Mobile-First UX', 'Zero-Downtime Deployment', 'Automated Testing & QA'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'
                ],
                [
                    'num' => '06',
                    'title' => 'System Integration & APIs',
                    'desc' => 'Menghubungkan ekosistem perangkat lunak Anda dengan payment gateway, logistik, WhatsApp API, ERP pusat, dan pihak ketiga.',
                    'features' => ['Payment Gateway & Banking', 'WhatsApp Business API', 'PPOB & Third-party Sync', 'Webhook & Event Brokers'],
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'
                ],
            ];
            @endphp

            @foreach($coreServices as $s)
            <div class="premium-card p-8 lg:p-9 flex flex-col justify-between group reveal">
                <div>
                    {{-- Top Number & Icon --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white shadow-2xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $s['icon'] !!}</svg>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-600 tracking-wider">{{ $s['num'] }}</span>
                    </div>

                    {{-- Title & Description --}}
                    <h3 class="text-xl font-display font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">
                        {{ $s['title'] }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        {{ $s['desc'] }}
                    </p>

                    {{-- Feature Checklist --}}
                    <ul class="space-y-2.5 mb-8">
                        @foreach($s['features'] as $feat)
                        <li class="flex items-center gap-2.5 text-xs text-slate-600 font-medium">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $feat }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Action Link --}}
                <div class="pt-5 border-t border-slate-100">
                    <a href="{{ route('services') }}" class="btn-link text-xs uppercase tracking-wider">
                        <span>Detail Layanan</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     4. WHY ALDEF TECH / THE ENGINEERING ADVANTAGE
     ============================================================ --}}
<section class="section-padding bg-slate-50/80 relative border-y border-slate-200/80" id="why-us">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
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

        {{-- 3 Guarantee Badges --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-10 border-t border-slate-200">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs reveal">
                <div class="text-2xl mb-3">🛡️</div>
                <h4 class="font-display font-bold text-slate-900 text-base mb-1">100% Code & IP Ownership</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Seluruh source code, arsitektur database, dan aset digital sepenuhnya menjadi milik perusahaan Anda.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs reveal reveal-delay-1">
                <div class="text-2xl mb-3">⏱️</div>
                <h4 class="font-display font-bold text-slate-900 text-base mb-1">Milestone On-Time Guarantee</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Jadwal pengerjaan bertahap dengan demo sprint berkala agar implementasi selesai tepat waktu.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs reveal reveal-delay-2">
                <div class="text-2xl mb-3">🤝</div>
                <h4 class="font-display font-bold text-slate-900 text-base mb-1">Post-Launch SLA & Support</h4>
                <p class="text-xs text-slate-600 leading-relaxed">Kami mendampingi migrasi data, pelatihan tim operasional, dan pemeliharaan keamanan.</p>
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $workflowSteps = [
                ['01', 'Discovery & Requirements', 'Memahami pain point bisnis, model data, dan target luaran sistem melalui sesi interview mendalam.'],
                ['02', 'Architecture & Strategy', 'Merancang skema database, pemetaan API, diagram alur sistem, dan pemilihan teknologi yang tepat.'],
                ['03', 'UI/UX & Prototyping', 'Mendesain antarmuka yang intuitif dan mudah dipelajari oleh staf operasional maupun pengguna umum.'],
                ['04', 'Agile Engineering', 'Membangun fungsionalitas dengan metodologi agile sprint dan update berkala setiap milestone.'],
                ['05', 'Testing & Quality Assurance', 'Validasi keamanan data, pengetesan performa beban tinggi, dan User Acceptance Testing (UAT).'],
                ['06', 'Launch, SLA & Scaling', 'Deployment ke server production, pendampingan go-live, pelatihan pengguna, dan maintenance berkesinambungan.'],
            ];
            @endphp

            @foreach($workflowSteps as $st)
            <div class="premium-card p-8 lg:p-9 relative reveal">
                <div class="step-number mb-4">{{ $st[0] }}</div>
                <h3 class="text-lg font-display font-bold text-slate-900 mb-2.5">{{ $st[1] }}</h3>
                <p class="text-slate-600 text-sm leading-relaxed">{{ $st[2] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     6. FEATURED CASE STUDIES / PORTFOLIO
     ============================================================ --}}
<section class="section-padding bg-slate-50/80 border-y border-slate-200/80" id="portfolio">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <span class="section-eyebrow reveal">Case Studies</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight reveal reveal-delay-1">
                    Karya Nyata yang Menggerakkan Bisnis
                </h2>
            </div>
            <a href="{{ route('portfolio') }}" class="btn-secondary self-start md:self-auto reveal">
                <span>Semua Studi Kasus</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($portfolios as $p)
            <div class="premium-card overflow-hidden flex flex-col justify-between group reveal">
                <div>
                    {{-- Thumbnail --}}
                    <div class="aspect-[16/10] bg-slate-100 relative overflow-hidden border-b border-slate-100">
                        @if($p->featured_image)
                        <img src="{{ asset('storage/' . $p->featured_image) }}" alt="{{ $p->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                            <span class="text-3xl mb-2">💻</span>
                            <span class="text-xs font-mono font-bold tracking-wider text-slate-500 uppercase">{{ $p->category->name ?? 'Software System' }}</span>
                        </div>
                        @endif
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-white/90 backdrop-blur-md text-[0.6875rem] font-bold text-slate-700 shadow-xs">
                            {{ $p->category->name ?? 'Enterprise' }}
                        </span>
                    </div>

                    {{-- Body Content --}}
                    <div class="p-7">
                        <h3 class="text-lg font-display font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $p->title }}
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-5 line-clamp-2">
                            {{ $p->short_description }}
                        </p>

                        {{-- Tech badges --}}
                        @if($p->technologies)
                        <div class="flex flex-wrap gap-1.5 mb-2">
                            @foreach(array_slice($p->technologies, 0, 4) as $tech)
                            <span class="text-[0.6875rem] px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 font-mono font-medium">
                                {{ $tech }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="px-7 pb-7 pt-2">
                    <a href="{{ route('portfolio.show', $p->slug) }}" class="btn-link text-xs font-bold uppercase tracking-wider">
                        <span>Lihat Studi Kasus</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-slate-400">
                Portofolio sedang dalam proses update dokumentasi.
            </div>
            @endforelse
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($solutions as $sol)
            <div class="p-7 rounded-2xl bg-white border border-slate-200 hover:border-blue-400 hover:shadow-card transition-all duration-300 group reveal">
                <div class="text-3xl mb-4 group-hover:scale-110 transition-transform">{{ $sol->icon ?? '📦' }}</div>
                <h3 class="font-display font-bold text-slate-900 text-lg mb-2 group-hover:text-blue-600 transition-colors">
                    {{ $sol->title }}
                </h3>
                <p class="text-slate-600 text-xs leading-relaxed">
                    {{ $sol->short_description }}
                </p>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     8. TRANSPARENT PROJECT INVESTMENT & ESTIMATION
     ============================================================ --}}
<section class="section-padding bg-slate-50/80 border-t border-slate-200/80" id="pricing">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        
        <div class="max-w-3xl mx-auto text-center mb-16">
            <span class="section-eyebrow justify-center reveal">Investment Models</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight mb-5 reveal reveal-delay-1">
                Estimasi Investasi Transparan & Fleksibel
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Setiap sistem dibangun custom. Kami menyediakan opsi paket dasar untuk memudahkan perencanaan anggaran teknologi Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Tier 1: MVP / Starter System --}}
            <div class="price-card reveal">
                <h3 class="text-xl font-display font-bold text-slate-900 mb-2">MVP / Quick System</h3>
                <p class="text-xs text-slate-500 mb-6">Cocok untuk validasi ide produk SaaS awal atau sistem operasional departemen mandiri.</p>
                
                <div class="pb-6 mb-6 border-b border-slate-100">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Mulai dari</span>
                    <span class="text-3xl font-display font-extrabold text-slate-900">Custom Scope</span>
                    <span class="text-xs text-slate-500 block mt-1">Estimasi 2 - 4 minggu</span>
                </div>

                <ul class="space-y-3 text-xs text-slate-600 mb-8 flex-1">
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> Core Feature Architecture</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> Clean Responsive Dashboard</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> Essential Database & Auth</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> Production Deployment</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> 30 Hari Post-Launch Support</li>
                </ul>

                @php
                    $waTextMvp = 'Halo Aldef Tech, saya ingin konsultasi mengenai pembuatan sistem MVP / Quick System.';
                    $waUrlMvp = 'https://wa.me/' . preg_replace('/\D/', '', \App\Models\SiteSetting::get('whatsapp_number', '6281234567890')) . '?text=' . rawurlencode($waTextMvp);
                @endphp
                <a href="{{ $waUrlMvp }}" target="_blank" rel="noopener" class="btn-secondary w-full text-center py-3">
                    Konsultasi Paket MVP →
                </a>
            </div>

            {{-- Tier 2: Custom Business System (Featured) --}}
            <div class="price-card featured reveal reveal-delay-1">
                <span class="price-card-badge">Paling Banyak Dipilih</span>
                <h3 class="text-xl font-display font-bold text-slate-900 mb-2">Custom Business System</h3>
                <p class="text-xs text-slate-500 mb-6">Solusi komprehensif untuk otomatisasi ERP, CRM, POS, dan operasional bisnis terintegrasi.</p>
                
                <div class="pb-6 mb-6 border-b border-slate-100">
                    <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider block">Full Bespoke Solution</span>
                    <span class="text-3xl font-display font-extrabold text-slate-900">Custom Architecture</span>
                    <span class="text-xs text-slate-500 block mt-1">Estimasi 4 - 10 minggu</span>
                </div>

                <ul class="space-y-3 text-xs text-slate-700 mb-8 flex-1 font-medium">
                    <li class="flex items-center gap-2.5"><span class="text-blue-600 font-bold">✓</span> Analisis Alur Bisnis Menyeluruh</li>
                    <li class="flex items-center gap-2.5"><span class="text-blue-600 font-bold">✓</span> Multi-Role Granular Permissions</li>
                    <li class="flex items-center gap-2.5"><span class="text-blue-600 font-bold">✓</span> WhatsApp / Payment Gateway API Sync</li>
                    <li class="flex items-center gap-2.5"><span class="text-blue-600 font-bold">✓</span> Automated PDF / Excel Reporting</li>
                    <li class="flex items-center gap-2.5"><span class="text-blue-600 font-bold">✓</span> Pelatihan Staf & Migrasi Data</li>
                    <li class="flex items-center gap-2.5"><span class="text-blue-600 font-bold">✓</span> 90 Hari Garansi & SLA Support</li>
                </ul>

                @php
                    $waTextBiz = 'Halo Aldef Tech, saya ingin konsultasi mengenai pembuatan Custom Business System / ERP.';
                    $waUrlBiz = 'https://wa.me/' . preg_replace('/\D/', '', \App\Models\SiteSetting::get('whatsapp_number', '6281234567890')) . '?text=' . rawurlencode($waTextBiz);
                @endphp
                <a href="{{ $waUrlBiz }}" target="_blank" rel="noopener" class="btn-primary w-full text-center py-3 shadow-md">
                    Diskusikan Custom System →
                </a>
            </div>

            {{-- Tier 3: Enterprise & SaaS Platform --}}
            <div class="price-card reveal reveal-delay-2">
                <h3 class="text-xl font-display font-bold text-slate-900 mb-2">Enterprise & SaaS Platform</h3>
                <p class="text-xs text-slate-500 mb-6">Arsitektur skala besar dengan AI capabilities, multi-tenant cloud, dan dedicated SLA.</p>
                
                <div class="pb-6 mb-6 border-b border-slate-100">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Enterprise Grade</span>
                    <span class="text-3xl font-display font-extrabold text-slate-900">Dedicated Scope</span>
                    <span class="text-xs text-slate-500 block mt-1">Timeline disesuaikan SLA</span>
                </div>

                <ul class="space-y-3 text-xs text-slate-600 mb-8 flex-1">
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> Multi-Tenant SaaS Architecture</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> AI Engine / Agent Integration</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> High Concurrency & Redis Clustering</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> Automated CI/CD & Cloud Setup</li>
                    <li class="flex items-center gap-2.5"><span class="text-emerald-500 font-bold">✓</span> Dedicated Lead Architect & Priority SLA</li>
                </ul>

                @php
                    $waTextEnt = 'Halo Aldef Tech, saya ingin konsultasi mengenai solusi Enterprise / SaaS Platform.';
                    $waUrlEnt = 'https://wa.me/' . preg_replace('/\D/', '', \App\Models\SiteSetting::get('whatsapp_number', '6281234567890')) . '?text=' . rawurlencode($waTextEnt);
                @endphp
                <a href="{{ $waUrlEnt }}" target="_blank" rel="noopener" class="btn-secondary w-full text-center py-3">
                    Konsultasi Enterprise →
                </a>
            </div>

        </div>

    </div>
</section>

{{-- ============================================================
     9. CEO SPOTLIGHT
     ============================================================ --}}
@if($ceoProfile)
<section class="section-padding bg-white relative">
    <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="bg-gradient-to-br from-slate-50 to-blue-50/40 border border-slate-200/90 rounded-3xl p-8 lg:p-12 shadow-elevated">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="md:col-span-4 text-center md:text-left">
                    <div class="w-32 h-32 lg:w-40 lg:h-40 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-display font-bold text-4xl shadow-xl mx-auto md:mx-0">
                        {{ strtoupper(substr($ceoProfile->name, 0, 1)) }}
                    </div>
                </div>
                <div class="md:col-span-8">
                    <span class="section-eyebrow mb-2">Leadership & Technical Advisory</span>
                    <h3 class="text-2xl lg:text-3xl font-display font-bold text-slate-900 mb-1">
                        {{ $ceoProfile->name }}
                    </h3>
                    <p class="text-blue-600 text-sm font-semibold mb-4">{{ $ceoProfile->position }}</p>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        {{ $ceoProfile->short_bio }}
                    </p>
                    <a href="{{ route('about') }}" class="btn-secondary btn-sm">
                        <span>Tentang Kepemimpinan & Visi →</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     10. FAQ SECTION
     ============================================================ --}}
<section class="section-padding bg-slate-50/80 border-t border-slate-200/80" id="faq">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10">
        
        <div class="text-center mb-16">
            <span class="section-eyebrow justify-center reveal">FAQ</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-tight mb-5 reveal reveal-delay-1">
                Pertanyaan yang Sering Diajukan
            </h2>
            <p class="text-slate-600 text-base lg:text-lg leading-relaxed reveal reveal-delay-2">
                Punya pertanyaan lain? Hubungi kami langsung melalui WhatsApp untuk konsultasi teknis.
            </p>
        </div>

        <div class="space-y-4" x-data="{ activeFaq: 0 }">
            @foreach($faqs as $index => $faq)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all shadow-2xs reveal">
                <button @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})"
                        class="w-full px-6 py-5 text-left flex items-center justify-between gap-4 font-display font-bold text-slate-900 text-base">
                    <span>{{ $faq->question }}</span>
                    <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 transition-transform duration-200"
                          :class="activeFaq === {{ $index }} ? 'rotate-180 bg-blue-50 text-blue-600' : 'text-slate-400'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="activeFaq === {{ $index }}" x-collapse
                     class="px-6 pb-6 pt-1 text-slate-600 text-sm leading-relaxed border-t border-slate-100">
                    {{ $faq->answer }}
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ============================================================
     11. FINAL HIGH-CONTRAST CONVERSION CTA (DEEP NAVY)
     ============================================================ --}}
<section class="py-24 lg:py-32 relative bg-[#090D16] text-white overflow-hidden" id="contact">
    {{-- Ambient Lighting --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-gradient-to-tr from-blue-600/15 via-indigo-600/10 to-transparent blur-[140px] pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <span class="section-eyebrow section-eyebrow-dark justify-center mb-4 reveal">Mulai Transformasi</span>
        
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal reveal-delay-1">
            Have a Business Challenge That Technology Can Solve?
        </h2>
        
        <p class="text-slate-300 text-lg lg:text-xl mb-10 max-w-2xl mx-auto leading-relaxed reveal reveal-delay-2">
            Mari wujudkan ide, alur kerja, atau tantangan bisnis Anda menjadi aset digital yang andal, scalable, dan berperforma tinggi.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 reveal reveal-delay-3">
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
               class="btn-primary btn-lg shadow-xl font-bold">
                <span>Start a Conversation</span>
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn-ghost-light btn-lg">
                <span>Isi Form Konsultasi Proyek</span>
            </a>
        </div>
    </div>
</section>

@endsection
