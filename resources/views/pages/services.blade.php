@extends('layouts.app')
@section('content')
@php $pageTitle = 'Services — Aldef Tech'; @endphp

<section class="section-padding pt-24 lg:pt-32 relative overflow-hidden">
    <div class="hero-orb hero-orb-1 opacity-50"></div>
    <div class="hero-orb hero-orb-2 opacity-50"></div>
    <div class="absolute inset-0 hero-grid opacity-30"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Services</span>
            <h1 class="text-4xl sm:text-5xl lg:text-display-sm font-display text-gradient-hero mb-6 reveal reveal-delay-1">Layanan Kami</h1>
            <p class="text-text-secondary text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">Kami menyediakan layanan teknologi komprehensif untuk membantu bisnis Anda membangun solusi digital yang tepat sasaran, cepat, dan scalable.</p>
        </div>

        <div class="space-y-6">
            @forelse($services as $service)
            <div class="premium-card p-8 lg:p-12 group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="flex flex-col sm:flex-row items-start gap-6 lg:gap-8">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-accent/15 via-brand-magenta/10 to-brand-cyan/10 border border-accent/20 flex items-center justify-center text-accent text-3xl shrink-0 transition-all duration-500 group-hover:scale-110 group-hover:shadow-[0_0_30px_rgba(168,85,247,0.2)]">
                        {{ $service->icon ?? '⚡' }}
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h2 class="text-2xl font-display font-bold text-text-primary group-hover:text-accent-light transition-colors duration-300">{{ $service->title }}</h2>
                        </div>
                        <p class="text-text-secondary leading-relaxed mb-5 text-base lg:text-lg">{{ $service->short_description }}</p>
                        @if($service->description)
                        <div class="text-text-muted text-sm leading-relaxed mb-6 bg-brand-surface-2/60 border border-brand-border/60 rounded-xl p-5">{!! nl2br(e($service->description)) !!}</div>
                        @endif
                        @if($service->features && count($service->features))
                        <div class="flex flex-wrap gap-2 pt-2">
                            @foreach($service->features as $feature)
                            <span class="tag tag-accent text-xs">{{ $feature }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20 text-text-muted">Layanan akan segera tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-padding bg-brand-surface relative overflow-hidden">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-4 reveal">Butuh Solusi Khusus?</h2>
        <p class="text-text-muted text-lg mb-8 reveal reveal-delay-1">Kami siap mendiskusikan kebutuhan spesifik dan arsitektur sistem bisnis Anda.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-gradient btn-lg magnetic reveal reveal-delay-2 px-10 py-4">Konsultasi Gratis →</a>
    </div>
</section>
@endsection
