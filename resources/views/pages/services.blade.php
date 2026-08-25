@extends('layouts.app')
@section('content')
@php $pageTitle = 'Services — Aldef Tech'; @endphp

<section class="section-padding pt-20 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Services</span>
            <h1 class="text-display-sm font-display text-text-primary mb-6 reveal reveal-delay-1">Layanan Kami</h1>
            <p class="text-text-secondary text-body-lg leading-relaxed reveal reveal-delay-2">Kami menyediakan layanan teknologi komprehensif untuk membantu bisnis Anda membangun solusi digital yang tepat sasaran.</p>
        </div>

        <div class="space-y-5">
            @forelse($services as $service)
            <div class="premium-card p-8 lg:p-10 group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="flex items-start gap-6 lg:gap-8">
                    <div class="w-14 h-14 rounded-xl bg-accent/8 border border-accent/15 flex items-center justify-center text-accent text-2xl shrink-0 transition-all duration-300 group-hover:scale-110 group-hover:bg-accent/12">
                        {{ $service->icon ?? '⚡' }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-heading font-display text-text-primary mb-3">{{ $service->title }}</h2>
                        <p class="text-text-secondary leading-relaxed mb-4">{{ $service->short_description }}</p>
                        @if($service->description)
                        <div class="text-text-muted text-sm leading-relaxed mb-5">{!! nl2br(e($service->description)) !!}</div>
                        @endif
                        @if($service->features && count($service->features))
                        <div class="flex flex-wrap gap-2">
                            @foreach($service->features as $feature)
                            <span class="tag">{{ $feature }}</span>
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
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-heading font-display text-text-primary mb-4 reveal">Butuh Solusi Khusus?</h2>
        <p class="text-text-muted mb-8 reveal reveal-delay-1">Kami siap mendiskusikan kebutuhan spesifik bisnis Anda.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary magnetic reveal reveal-delay-2">Konsultasi Gratis →</a>
    </div>
</section>
@endsection
