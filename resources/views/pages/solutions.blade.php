@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Solusi Software Enterprise & AI — Aldef Tech';
$metaDescription = 'Solusi sistem manajemen bisnis, SaaS platform, AI agent, otomasi workflow, inventory, dan custom ERP.';
@endphp

{{-- Hero Section --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">Enterprise Solutions</span>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Sistem yang Kami Bangun
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Solusi perangkat lunak terbukti yang dirancang untuk mengotomasi proses kerja, mengeliminasi inefisiensi manual, dan memberikan visibilitas data real-time.
            </p>
        </div>
    </div>
</section>

{{-- Solutions Grid --}}
<section class="section-padding bg-slate-50/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse($solutions as $solution)
            <div class="premium-card p-8 group flex flex-col justify-between reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div>
                    {{-- Icon Container --}}
                    <div class="w-13 h-13 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-2xl mb-6 shadow-2xs group-hover:scale-105 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        {{ $solution->icon ?? '📦' }}
                    </div>

                    <h2 class="text-xl font-display font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">
                        {{ $solution->title }}
                    </h2>

                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        {{ $solution->short_description }}
                    </p>

                    @if($solution->features && count($solution->features))
                    <div class="space-y-2.5 pt-4 border-t border-slate-100 mb-6">
                        @foreach($solution->features as $feature)
                        <div class="flex items-start gap-2.5 text-xs text-slate-700">
                            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-medium leading-normal">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- CTA Button --}}
                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20tertarik%20dengan%20solusi%20{{ urlencode($solution->title) }}" target="_blank" rel="noopener" class="text-xs font-semibold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1.5">
                        <span>Konsultasi Solusi Ini</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20 text-slate-500 bg-white rounded-2xl border border-slate-200">
                Solusi akan segera diperbarui.
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Striking Dark CTA Section --}}
<section class="py-20 lg:py-28 relative bg-[#090D16] text-white overflow-hidden">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-white mb-6 leading-tight tracking-tight reveal">
            Membutuhkan Sistem yang Disesuaikan Khusus?
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Kami siap mengembangkan custom software yang menyatu dengan SOP dan infrastruktur IT perusahaan Anda.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn-primary btn-lg shadow-lg reveal reveal-delay-2">
            <span>Diskusikan Kebutuhan Custom</span>
            <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
