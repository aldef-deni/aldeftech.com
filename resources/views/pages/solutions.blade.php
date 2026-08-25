@extends('layouts.app')
@section('content')
@php $pageTitle = 'Solutions — Aldef Tech'; @endphp

<section class="section-padding pt-24 lg:pt-32 relative overflow-hidden">
    <div class="hero-orb hero-orb-1 opacity-50"></div>
    <div class="hero-orb hero-orb-2 opacity-50"></div>
    <div class="absolute inset-0 hero-grid opacity-30"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Solutions</span>
            <h1 class="text-4xl sm:text-5xl lg:text-display-sm font-display text-gradient-hero mb-6 reveal reveal-delay-1">Sistem yang Kami Bangun</h1>
            <p class="text-text-secondary text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">Solusi software yang dirancang untuk menyelesaikan masalah bisnis nyata — mulai dari ERP, SaaS, sistem inventori, hingga automasi berbasis AI.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($solutions as $solution)
            <div class="premium-card p-8 lg:p-10 group reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-accent/15 via-brand-magenta/10 to-brand-cyan/10 border border-accent/20 flex items-center justify-center text-accent text-2xl mb-6 transition-all duration-500 group-hover:scale-110 group-hover:shadow-[0_0_25px_rgba(168,85,247,0.18)]">
                    {{ $solution->icon ?? '📦' }}
                </div>
                <h2 class="text-2xl font-display font-bold text-text-primary mb-3 group-hover:text-accent-light transition-colors duration-300">{{ $solution->title }}</h2>
                <p class="text-text-secondary text-base leading-relaxed mb-6">{{ $solution->short_description }}</p>
                @if($solution->features && count($solution->features))
                <div class="space-y-3 pt-2 border-t border-brand-border/60">
                    @foreach($solution->features as $feature)
                    <div class="flex items-center gap-3 text-sm text-text-muted">
                        <div class="w-5 h-5 rounded-full bg-accent/10 border border-accent/20 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-text-secondary font-medium">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-2 text-center py-20 text-text-muted">Solutions akan segera tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-padding bg-brand-surface relative overflow-hidden">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-4 reveal">Tidak Menemukan Solusi yang Dibutuhkan?</h2>
        <p class="text-text-muted text-lg mb-8 reveal reveal-delay-1">Kami membangun solusi custom sesuai kebutuhan unik dan alur kerja bisnis Anda.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-gradient btn-lg magnetic reveal reveal-delay-2 px-10 py-4">Diskusikan Kebutuhan Anda →</a>
    </div>
</section>
@endsection
