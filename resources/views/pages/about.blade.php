@extends('layouts.app')
@section('content')
@php
$pageTitle = 'About Aldef Tech — Premium Software Engineering & AI Studio';
$metaDescription = 'Aldef Tech adalah software engineering studio yang membantu bisnis membangun custom software, SaaS, AI, dan sistem otomasi enterprise.';
@endphp

{{-- Hero Section --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">About Aldef Tech</span>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                {{ \App\Models\SiteSetting::get('about_title', 'Engineering Technology for Real Business Impact.') }}
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                {{ \App\Models\SiteSetting::get('about_subtitle', 'Aldef Tech adalah technology partner yang merancang dan membangun custom software, SaaS, aplikasi web, dan automasi AI untuk mengakselerasi transformasi digital bisnis Anda.') }}
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision Section --}}
<section class="section-padding bg-slate-50/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Mission --}}
            <div class="premium-card p-8 lg:p-10 reveal">
                <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold mb-6">
                    🎯
                </div>
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest block mb-2">Our Mission</span>
                <h3 class="text-2xl font-display font-bold text-slate-900 mb-4">Empowering Digital Operations</h3>
                <p class="text-slate-600 leading-relaxed text-base">
                    {{ \App\Models\SiteSetting::get('about_mission', 'Membantu bisnis membangun sistem digital yang handal, scalable, dan terintegrasi secara mulus dengan alur kerja nyata perusahaan.') }}
                </p>
            </div>

            {{-- Vision --}}
            <div class="premium-card p-8 lg:p-10 reveal reveal-delay-1">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-xl font-bold mb-6">
                    🔭
                </div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest block mb-2">Our Vision</span>
                <h3 class="text-2xl font-display font-bold text-slate-900 mb-4">Leading Technology Partner</h3>
                <p class="text-slate-600 leading-relaxed text-base">
                    {{ \App\Models\SiteSetting::get('about_vision', 'Menjadi mitra teknologi terpercaya yang memimpin inovasi software development dan automasi AI dengan standar kualitas enterprise.') }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Core Values / Principles --}}
<section class="section-padding bg-white relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Our Principles</span>
            <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 tracking-tight reveal reveal-delay-1">
                Standar Kualitas yang Kami Pegang
            </h2>
            <p class="text-slate-600 text-lg leading-relaxed reveal reveal-delay-2">
                Fondasi kerja kami dalam setiap proyek, dari baris kode pertama hingga pemeliharaan jangka panjang.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-7 rounded-2xl bg-slate-50 border border-slate-200/80 reveal">
                <div class="text-xl font-display font-bold text-slate-900 mb-2">01. Architectural Integrity</div>
                <p class="text-slate-600 text-sm leading-relaxed">Setiap sistem dirancang dengan clean architecture, modular, dan mudah dikembangkan seiring bertambahnya beban operasional.</p>
            </div>
            <div class="p-7 rounded-2xl bg-slate-50 border border-slate-200/80 reveal reveal-delay-1">
                <div class="text-xl font-display font-bold text-slate-900 mb-2">02. Business-First Mindset</div>
                <p class="text-slate-600 text-sm leading-relaxed">Teknologi adalah alat bantu bisnis. Kami selalu memprioritaskan efisiensi biaya, return on investment, dan simplifikasi proses.</p>
            </div>
            <div class="p-7 rounded-2xl bg-slate-50 border border-slate-200/80 reveal reveal-delay-2">
                <div class="text-xl font-display font-bold text-slate-900 mb-2">03. Transparent Collaboration</div>
                <p class="text-slate-600 text-sm leading-relaxed">Komunikasi terbuka, update progres berkala, dan dokumentasi lengkap agar Anda memiliki visibilitas 100% terhadap proyek.</p>
            </div>
        </div>
    </div>
</section>

{{-- CEO / Leadership Section --}}
@if($ceoProfile)
<section class="section-padding bg-slate-50/80 border-t border-slate-200/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            {{-- Bio --}}
            <div class="lg:col-span-7 reveal">
                <span class="section-eyebrow">Leadership</span>
                <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 mb-2">
                    {{ $ceoProfile->name }}
                </h2>
                <p class="text-blue-600 font-display font-semibold text-lg mb-6">
                    {{ $ceoProfile->position }}
                </p>
                <div class="text-slate-600 leading-relaxed whitespace-pre-line mb-8 text-base lg:text-lg">
                    {{ $ceoProfile->full_bio ?? $ceoProfile->short_bio }}
                </div>

                @if($ceoProfile->skills && count($ceoProfile->skills))
                <div class="mb-8">
                    <h3 class="text-sm font-display font-bold text-slate-800 mb-3">Skills & Technical Expertise</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($ceoProfile->skills as $skill)
                        <span class="text-xs font-mono font-medium px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/60">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary">
                    <span>Diskusikan Project Bersama {{ $ceoProfile->name }}</span>
                    <svg class="w-4 h-4 ml-1 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Photo --}}
            <div class="lg:col-span-5 reveal-right reveal-delay-2 flex justify-center">
                <div class="relative group w-full max-w-sm">
                    <div class="absolute -inset-2 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-3xl opacity-20 blur-md group-hover:opacity-30 transition duration-500"></div>
                    <div class="relative aspect-[4/5] rounded-2xl overflow-hidden border-2 border-white shadow-2xl bg-slate-900">
                        <img src="{{ $ceoProfile->profile_photo ? asset('storage/' . $ceoProfile->profile_photo) : asset('images/deni-afrizal.jpg') }}" alt="{{ $ceoProfile->name }}" class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute bottom-3.5 left-3.5 right-3.5 p-3 rounded-xl bg-slate-950/80 backdrop-blur-md border border-white/10 text-white flex items-center justify-between">
                            <div>
                                <p class="text-xs font-display font-bold text-white">{{ $ceoProfile->name }}</p>
                                <p class="text-[0.6875rem] text-blue-300 font-mono">{{ $ceoProfile->position }}</p>
                            </div>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endif

{{-- Striking Luxury Dark CTA Section (Black, Blue & Red Blend) --}}
<section class="py-20 lg:py-28 relative bg-[#060810] text-white overflow-hidden">
    <div class="absolute -top-32 -left-20 w-[450px] h-[450px] bg-gradient-to-br from-blue-600/30 via-indigo-600/20 to-transparent rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-20 w-[500px] h-[500px] bg-gradient-to-tl from-rose-600/25 via-red-600/20 to-transparent rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-radial from-blue-600/15 via-red-700/10 to-transparent blur-[120px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-blue-950/80 via-slate-900/90 to-red-950/80 border border-white/15 text-xs font-mono font-bold tracking-wider mb-6 shadow-lg reveal">
            <span class="w-2 h-2 rounded-full bg-gradient-to-r from-blue-400 to-rose-500 animate-pulse"></span>
            <span class="bg-gradient-to-r from-blue-300 via-white to-rose-300 bg-clip-text text-transparent uppercase">Mulai Transformasi</span>
        </div>

        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Siap Membangun <span class="bg-gradient-to-r from-blue-400 via-indigo-200 to-rose-400 bg-clip-text text-transparent">Sistem Digital Anda?</span>
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Mari jadwalkan sesi konsultasi gratis untuk mendiskusikan visi dan kebutuhan teknis bisnis Anda.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-600 border border-blue-400/30 reveal reveal-delay-2">
            <span>Mulai Konsultasi Gratis</span>
            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
