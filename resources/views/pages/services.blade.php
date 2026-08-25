@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Layanan Software Development, SaaS & AI — Aldef Tech';
$metaDescription = 'Layanan lengkap pembuatan custom software, SaaS platform, AI & Machine Learning, automasi bisnis, dan integrasi sistem enterprise.';
@endphp

{{-- Hero Section --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">Our Services</span>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Comprehensive Software & AI Engineering
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Dari analisis kebutuhan bisnis, arsitektur data, hingga implementasi kecerdasan buatan, kami membangun software berkualitas tinggi untuk pertumbuhan perusahaan Anda.
            </p>
        </div>
    </div>
</section>

{{-- Services Grid --}}
<section class="section-padding bg-slate-50/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="space-y-8">
            @forelse($services as $service)
            <div class="premium-card p-8 lg:p-12 reveal reveal-delay-{{ min($loop->iteration, 3) }}" id="{{ $service->slug }}">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- Service Header & Icon --}}
                    <div class="lg:col-span-4">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-3xl mb-6 shadow-xs">
                            {{ $service->icon ?? '⚡' }}
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-display font-bold text-slate-900 mb-3">
                            {{ $service->title }}
                        </h2>
                        <p class="text-slate-600 text-base leading-relaxed mb-6">
                            {{ $service->short_description }}
                        </p>
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20dengan%20layanan%20{{ urlencode($service->title) }}" target="_blank" rel="noopener" class="btn-secondary btn-sm inline-flex items-center gap-1.5 font-semibold text-blue-600 border-blue-200 hover:bg-blue-50">
                            <span>Konsultasikan Layanan Ini</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>

                    {{-- Service Details & Features --}}
                    <div class="lg:col-span-8 bg-slate-50/80 border border-slate-200/80 rounded-2xl p-6 lg:p-8">
                        @if($service->description)
                        <div class="text-slate-700 text-sm leading-relaxed mb-6">
                            {!! nl2br(e($service->description)) !!}
                        </div>
                        @endif

                        @if($service->features && count($service->features))
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Key Capabilities & Features</div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($service->features as $feature)
                                <div class="flex items-center gap-2.5 text-sm text-slate-800 bg-white border border-slate-200/80 px-3.5 py-2 rounded-xl shadow-2xs">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span class="font-medium">{{ $feature }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            @empty
            <div class="text-center py-20 text-slate-500 bg-white rounded-2xl border border-slate-200">
                Layanan akan segera diperbarui.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Striking Dark CTA Section --}}
<section class="py-20 lg:py-28 relative bg-[#090D16] text-white overflow-hidden">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Butuh Spesifikasi Sistem Khusus?
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Setiap bisnis memiliki keunikan alur kerja. Diskusikan rancangan sistem impian Anda bersama lead engineer kami.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-lg reveal reveal-delay-2">
            <span>Jadwalkan Diskusi Teknis</span>
            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
