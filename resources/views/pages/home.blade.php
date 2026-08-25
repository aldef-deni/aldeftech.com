@extends('layouts.app')

@section('content')
{{-- ================================================================
     HERO SECTION — Enterprise Tech & AI Studio
     ================================================================ --}}
<section class="hero-light-gradient relative overflow-hidden pt-12 pb-20 lg:pt-16 lg:pb-32 border-b border-slate-200/60">
    {{-- Subtle decorative grid --}}
    <div class="absolute inset-0 subtle-grid opacity-70 pointer-events-none"></div>

    {{-- Subtle ambient glow --}}
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-blue-500/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            {{-- Left Content --}}
            <div class="lg:col-span-7">
                {{-- Eyebrow Badge --}}
                <div class="reveal mb-6">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-50 border border-blue-200/80 text-blue-700 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        ALDEF TECH — Software & AI Studio
                    </span>
                </div>

                {{-- Main Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-[4.25rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.08] mb-6 reveal reveal-delay-1">
                    Build Smarter.<br>
                    <span class="text-gradient-brand">Scale Faster.</span>
                </h1>

                {{-- Subheadline --}}
                <p class="text-slate-600 text-lg sm:text-xl max-w-xl mb-10 leading-relaxed reveal reveal-delay-2 font-normal">
                    Custom software, SaaS, AI, and business automation solutions built for modern businesses that prioritize speed, scale, and reliability.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center gap-4 reveal reveal-delay-3">
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-md">
                        <span>Start Your Project</span>
                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('solutions') }}" class="btn-secondary btn-lg">
                        Explore Our Solutions
                    </a>
                </div>

                {{-- Trust Highlights --}}
                <div class="mt-12 pt-8 border-t border-slate-200/80 grid grid-cols-3 gap-6 max-w-lg reveal reveal-delay-4">
                    <div>
                        <div class="text-2xl font-bold font-display text-slate-900">50+</div>
                        <div class="text-xs text-slate-500 font-medium mt-0.5">Projects Delivered</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold font-display text-slate-900">99.9%</div>
                        <div class="text-xs text-slate-500 font-medium mt-0.5">System Reliability</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold font-display text-slate-900">100%</div>
                        <div class="text-xs text-slate-500 font-medium mt-0.5">Custom Architecture</div>
                    </div>
                </div>
            </div>

            {{-- Right Visual: Interactive Enterprise Tech Dashboard Card --}}
            <div class="lg:col-span-5 reveal-right reveal-delay-2">
                <div class="relative">
                    {{-- Soft glow behind card --}}
                    <div class="absolute -inset-2 rounded-3xl bg-gradient-to-tr from-blue-600/10 via-indigo-500/10 to-violet-500/10 blur-xl"></div>
                    
                    {{-- Main Tech Mockup Card --}}
                    <div class="relative bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-elevated">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                                <span class="text-xs font-mono text-slate-400 ml-2">aldef-system.prod</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[0.6875rem] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Active Pipeline
                            </span>
                        </div>

                        {{-- Metric Rows --}}
                        <div class="py-5 space-y-4">
                            {{-- Metric 1 --}}
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-slate-500">AI Automation Engine</div>
                                    <div class="text-sm font-bold text-slate-900 font-display mt-0.5">Autonomous Agent & OCR</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">99.8% Accuracy</span>
                                </div>
                            </div>

                            {{-- Metric 2 --}}
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-slate-500">System Performance</div>
                                    <div class="text-sm font-bold text-slate-900 font-display mt-0.5">Latency & Response Time</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">&lt; 45ms P99</span>
                                </div>
                            </div>

                            {{-- Metric 3 --}}
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-slate-500">Multi-tenant Architecture</div>
                                    <div class="text-sm font-bold text-slate-900 font-display mt-0.5">SaaS Core & Database Sync</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">Enterprise SLA</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom Mini Tech Stack --}}
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span class="font-medium">Stack: Laravel • Python • AI</span>
                            <span class="text-blue-600 font-semibold flex items-center gap-1">
                                Ready for Scale
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     SERVICES SECTION
     ================================================================ --}}
@if($services->count())
<section class="section-padding bg-slate-50/80 relative" id="services">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Services</span>
            <h2 class="text-3xl sm:text-4xl lg:text-display-sm font-display font-bold text-slate-900 mb-4 reveal reveal-delay-1 tracking-tight">
                Software & AI Engineering Services
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Kami merancang dan membangun arsitektur digital komprehensif yang disesuaikan secara presisi dengan kebutuhan bisnis Anda.
            </p>
        </div>

        {{-- Service Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($services as $service)
            <div class="premium-card p-7 group flex flex-col justify-between reveal reveal-delay-{{ $loop->iteration < 5 ? $loop->iteration : 1 }}">
                <div>
                    {{-- Icon Badge --}}
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xl mb-6 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-105 shadow-2xs">
                        {{ $service->icon ?? '⚡' }}
                    </div>
                    
                    <h3 class="text-lg font-display font-bold text-slate-900 mb-2.5 group-hover:text-blue-600 transition-colors">
                        {{ $service->title }}
                    </h3>
                    
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        {{ $service->short_description }}
                    </p>
                </div>

                {{-- Action Link --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                    <span>Lihat Detail</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     SOLUTIONS SECTION
     ================================================================ --}}
@if($solutions->count())
<section class="section-padding bg-white relative" id="solutions">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Solutions</span>
            <h2 class="text-3xl sm:text-4xl lg:text-display-sm font-display font-bold text-slate-900 mb-4 reveal reveal-delay-1 tracking-tight">
                Sistem yang Kami Bangun
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Solusi enterprise software yang siap diadaptasi untuk meningkatkan efisiensi dan otomasi operasional bisnis.
            </p>
        </div>

        {{-- Solutions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($solutions as $solution)
            <div class="premium-card p-7 flex items-start gap-5 group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-lg shrink-0 transition-transform duration-300 group-hover:scale-105 shadow-2xs">
                    {{ $solution->icon ?? '📦' }}
                </div>
                <div>
                    <h3 class="font-display font-bold text-slate-900 text-base mb-1.5 group-hover:text-blue-600 transition-colors">
                        {{ $solution->title }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $solution->short_description }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     FEATURED PORTFOLIO
     ================================================================ --}}
@if($portfolios->count())
<section class="section-padding bg-slate-50/80 relative" id="portfolio">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-14 lg:mb-16 gap-4">
            <div class="max-w-xl">
                <span class="section-eyebrow reveal">Portfolio</span>
                <h2 class="text-3xl sm:text-4xl lg:text-display-sm font-display font-bold text-slate-900 tracking-tight reveal reveal-delay-1">
                    Featured Projects
                </h2>
            </div>
            <a href="{{ route('portfolio') }}" class="btn-link font-semibold reveal reveal-delay-2 inline-flex items-center gap-1.5">
                <span>Lihat Semua Project</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Portfolio Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
            @foreach($portfolios as $item)
            <a href="{{ route('portfolio.show', $item->slug) }}" class="premium-card overflow-hidden group flex flex-col justify-between reveal reveal-delay-{{ $loop->iteration }}">
                <div>
                    <div class="aspect-[16/10] bg-slate-100 overflow-hidden relative border-b border-slate-100">
                        @if($item->featured_image)
                        <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-400 p-6 text-center">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 mb-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">{{ $item->category->name ?? 'Software System' }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-6 lg:p-7">
                        <div class="text-[0.6875rem] font-bold tracking-wider text-blue-600 uppercase mb-2">
                            {{ $item->category->name ?? 'Project' }}
                        </div>
                        <h3 class="font-display font-bold text-slate-900 text-lg mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $item->title }}
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed line-clamp-2">
                            {{ $item->short_description }}
                        </p>

                        @if($item->technologies)
                        <div class="flex flex-wrap gap-1.5 mt-5">
                            @foreach(array_slice($item->technologies, 0, 3) as $tech)
                            <span class="tag text-[0.6875rem]">{{ $tech }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="px-6 lg:px-7 pb-6 pt-2 flex items-center justify-between text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                    <span>View Case Study</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     PROCESS SECTION — How We Work
     ================================================================ --}}
<section class="section-padding bg-white relative" id="process">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">How We Work</span>
            <h2 class="text-3xl sm:text-4xl lg:text-display-sm font-display font-bold text-slate-900 mb-4 reveal reveal-delay-1 tracking-tight">
                Proses Delivery Terstruktur
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Metodologi agile yang teruji untuk memastikan setiap proyek selesai tepat waktu, aman, dan sesuai target bisnis.
            </p>
        </div>

        {{-- Steps Grid --}}
        @php
        $defaultSteps = [
            ['num' => '01', 'title' => 'Discover', 'desc' => 'Analisis mendalam terhadap proses bisnis, kebutuhan pengguna, dan target objektif.'],
            ['num' => '02', 'title' => 'Plan', 'desc' => 'Perancangan arsitektur sistem, pemilihan teknologi, dan timeline pengerjaan yang jelas.'],
            ['num' => '03', 'title' => 'Design', 'desc' => 'Pembuatan rancangan UI/UX intuitif serta pemodelan skema database yang scalable.'],
            ['num' => '04', 'title' => 'Build', 'desc' => 'Pengembangan kode bersih dengan standar enterprise, modular, dan iterative sprint.'],
            ['num' => '05', 'title' => 'Launch', 'desc' => 'Pengujian menyeluruh (QA & Security) serta deployment zero-downtime ke server produksi.'],
            ['num' => '06', 'title' => 'Scale', 'desc' => 'Pemeliharaan berkelanjutan, monitoring performa, dan optimasi kapasitas sistem.'],
        ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($defaultSteps as $step)
            <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-7 relative hover:bg-white hover:border-slate-300 hover:shadow-card transition-all duration-300 reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                <div class="text-3xl font-display font-black text-blue-600/30 mb-3 tracking-tighter">{{ $step['num'] }}</div>
                <h3 class="font-display font-bold text-slate-900 text-lg mb-2">{{ $step['title'] }}</h3>
                <p class="text-slate-600 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================================================================
     TECHNOLOGY STACK SECTION
     ================================================================ --}}
<section class="py-16 lg:py-20 bg-slate-50/80 border-t border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Enterprise-Grade Stack</span>
            <h3 class="text-xl font-display font-bold text-slate-800 mt-1">Teknologi Modern yang Kami Gunakan</h3>
        </div>

        @php
        $techStacks = ['Laravel', 'PHP', 'Python', 'Vue.js', 'Alpine.js', 'Tailwind CSS', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'AI & OpenAI', 'REST API'];
        @endphp
        <div class="flex flex-wrap items-center justify-center gap-3 max-w-3xl mx-auto">
            @foreach($techStacks as $tech)
            <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 text-sm font-semibold shadow-2xs hover:border-blue-400 hover:text-blue-600 transition-colors">
                {{ $tech }}
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================================================================
     CEO PROFILE / LEADERSHIP
     ================================================================ --}}
@if($ceoProfile)
<section class="section-padding bg-white relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            {{-- Left Content --}}
            <div class="lg:col-span-7 reveal">
                <span class="section-eyebrow">Leadership</span>
                <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 mb-2">
                    {{ $ceoProfile->name }}
                </h2>
                <p class="text-blue-600 font-display font-semibold text-lg mb-6">
                    {{ $ceoProfile->position }}
                </p>
                <p class="text-slate-600 leading-relaxed mb-8 text-base lg:text-lg">
                    {{ $ceoProfile->short_bio }}
                </p>

                @if($ceoProfile->skills && count($ceoProfile->skills))
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($ceoProfile->skills as $skill)
                    <span class="tag tag-accent">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif

                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary shadow-sm">
                    <span>Diskusikan Project Bersama {{ $ceoProfile->name }}</span>
                    <svg class="w-4 h-4 ml-1 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Right Photo / Visual --}}
            <div class="lg:col-span-5 reveal-right reveal-delay-2">
                @if($ceoProfile->profile_photo)
                <div class="relative">
                    <img src="{{ asset('storage/' . $ceoProfile->profile_photo) }}" alt="{{ $ceoProfile->name }}" class="rounded-2xl w-full max-w-md mx-auto border border-slate-200 shadow-card">
                </div>
                @else
                <div class="aspect-[4/3] bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-center max-w-md mx-auto p-8 shadow-card">
                    <div class="text-center">
                        <div class="w-24 h-24 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 text-4xl font-display font-extrabold mx-auto mb-4 shadow-sm">
                            {{ substr($ceoProfile->name, 0, 1) }}
                        </div>
                        <div class="text-slate-900 font-bold font-display text-lg">{{ $ceoProfile->name }}</div>
                        <div class="text-slate-500 text-sm mt-0.5">{{ $ceoProfile->position }}</div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endif

{{-- ================================================================
     WHY BUSINESSES CHOOSE US / TESTIMONIALS
     ================================================================ --}}
<section class="section-padding bg-slate-50/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        @if($testimonials->count())
            {{-- Testimonials Mode --}}
            <div class="max-w-2xl mb-16 lg:mb-20">
                <span class="section-eyebrow reveal">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl lg:text-display-sm font-display font-bold text-slate-900 reveal reveal-delay-1 tracking-tight">Kata Klien Kami</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($testimonials as $testimonial)
                <div class="premium-card p-7 lg:p-8 flex flex-col justify-between reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                    <div>
                        <div class="flex gap-1 text-amber-400 mb-4">
                            @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed italic mb-6">"{{ $testimonial->testimonial }}"</p>
                    </div>

                    <div class="flex items-center gap-3.5 pt-4 border-t border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-sm font-display">
                            {{ substr($testimonial->client_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $testimonial->client_name }}</div>
                            <div class="text-xs text-slate-500">{{ $testimonial->position }}{{ $testimonial->company ? ' • ' . $testimonial->company : '' }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- Value Proposition Mode (No fake testimonials) --}}
            <div class="max-w-2xl mb-16 lg:mb-20">
                <span class="section-eyebrow reveal">Why Choose Us</span>
                <h2 class="text-3xl sm:text-4xl lg:text-display-sm font-display font-bold text-slate-900 reveal reveal-delay-1 tracking-tight">
                    Why Businesses Choose Aldef Tech
                </h2>
                <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                    Kami memadukan pemahaman bisnis mendalam dengan standar engineering kelas enterprise.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="premium-card p-8 reveal">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xl mb-5 font-bold">
                        01
                    </div>
                    <h3 class="font-display font-bold text-slate-900 text-lg mb-2">100% Tailored Engineering</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Kami tidak memaksakan template generic. Setiap modul dibangun sesuai alur operasional dan kebutuhan unik perusahaan Anda.</p>
                </div>

                <div class="premium-card p-8 reveal reveal-delay-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-xl mb-5 font-bold">
                        02
                    </div>
                    <h3 class="font-display font-bold text-slate-900 text-lg mb-2">Clean, Scalable Architecture</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Arsitektur terstruktur, terdokumentasi, dan mudah dikembangkan untuk mendukung pertumbuhan volume transaksi bisnis Anda.</p>
                </div>

                <div class="premium-card p-8 reveal reveal-delay-2">
                    <div class="w-12 h-12 rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600 text-xl mb-5 font-bold">
                        03
                    </div>
                    <h3 class="font-display font-bold text-slate-900 text-lg mb-2">Direct Technical Consultation</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Komunikasi langsung dengan lead architect dan engineer tanpa birokrasi berbelit, memastikan keputusan teknis yang tepat dan cepat.</p>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ================================================================
     LATEST INSIGHTS
     ================================================================ --}}
@if($latestPosts->count())
<section class="section-padding bg-white relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-14 lg:mb-16 gap-4">
            <div class="max-w-xl">
                <span class="section-eyebrow reveal">Insights</span>
                <h2 class="text-3xl sm:text-4xl lg:text-display-sm font-display font-bold text-slate-900 tracking-tight reveal reveal-delay-1">Latest Articles</h2>
            </div>
            <a href="{{ route('blog') }}" class="btn-link font-semibold reveal reveal-delay-2 inline-flex items-center gap-1.5">
                <span>Lihat Semua Artikel</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group flex flex-col justify-between reveal reveal-delay-{{ $loop->iteration }}">
                <div>
                    <div class="aspect-[16/10] bg-slate-100 overflow-hidden relative border-b border-slate-100">
                        @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-400">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Technology Insight</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-6 lg:p-7">
                        @if($post->category)
                        <span class="text-[0.6875rem] font-bold tracking-wider text-blue-600 uppercase">{{ $post->category->name }}</span>
                        @endif
                        <h3 class="font-display font-bold text-slate-900 text-lg mt-2 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $post->title }}
                        </h3>
                        <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed">
                            {{ $post->excerpt }}
                        </p>
                    </div>
                </div>

                <div class="px-6 lg:px-7 pb-6 pt-2 text-xs text-slate-400 font-medium border-t border-slate-50">
                    {{ $post->published_at->format('d M Y') }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     FAQ ACCORDION
     ================================================================ --}}
@if($faqs->count())
<section class="section-padding bg-slate-50/80 relative" id="faq">
    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="text-center mb-14">
            <span class="section-eyebrow justify-center reveal">FAQ</span>
            <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 tracking-tight reveal reveal-delay-1">Pertanyaan Umum</h2>
        </div>

        <div class="space-y-3.5" x-data="{ activeFaq: null }">
            @foreach($faqs as $faq)
            <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs transition-all duration-200 hover:border-slate-300">
                <button @click="activeFaq === {{ $faq->id }} ? activeFaq = null : activeFaq = {{ $faq->id }}"
                        class="w-full flex items-center justify-between px-6 py-5 text-left group">
                    <span class="text-base font-semibold text-slate-900 pr-4 group-hover:text-blue-600 transition-colors">{{ $faq->question }}</span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-300" :class="activeFaq === {{ $faq->id }} ? 'rotate-180 text-blue-600' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === {{ $faq->id }}" x-collapse>
                    <div class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        {{ $faq->answer }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     FINAL STRIKING DARK CTA SECTION (High-contrast focal point)
     ================================================================ --}}
<section class="py-20 lg:py-28 relative bg-[#090D16] text-white overflow-hidden">
    {{-- Ambient light glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-blue-600/15 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-900/60 border border-blue-700/50 text-blue-300 mb-6 shadow-xs">
            Let's Collaborate
        </span>

        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Have an idea? Let's build it.
        </h2>

        <p class="text-slate-300 text-lg lg:text-xl mb-10 max-w-2xl mx-auto reveal reveal-delay-1 leading-relaxed">
            Turn your business challenges into scalable, high-performance digital solutions. Konsultasikan kebutuhan sistem Anda secara gratis.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 reveal reveal-delay-2">
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-lg">
                <span>Start a Conversation</span>
                <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl border border-slate-700 bg-slate-900/60 text-white font-semibold text-sm hover:bg-slate-800 hover:border-slate-600 transition-all">
                Contact Form
            </a>
        </div>
    </div>
</section>
@endsection
