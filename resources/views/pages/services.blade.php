@extends('layouts.app')
@section('content')
@php $pageTitle = 'Services — Aldef Tech'; @endphp

<section class="section-padding pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Services</span>
            <h1 class="text-display-sm text-text-primary mb-6">Layanan Kami</h1>
            <p class="text-text-secondary text-body-lg">Kami menyediakan layanan teknologi komprehensif untuk membantu bisnis Anda membangun solusi digital yang tepat sasaran.</p>
        </div>

        <div class="space-y-8">
            @forelse($services as $service)
            <div class="premium-card p-8 reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center text-accent text-2xl shrink-0">
                        {{ $service->icon ?? '⚡' }}
                    </div>
                    <div>
                        <h2 class="text-heading text-text-primary mb-3">{{ $service->title }}</h2>
                        <p class="text-text-secondary leading-relaxed mb-4">{{ $service->short_description }}</p>
                        @if($service->description)
                        <div class="text-text-muted text-sm leading-relaxed mb-4">{!! nl2br(e($service->description)) !!}</div>
                        @endif
                        @if($service->features && count($service->features))
                        <div class="flex flex-wrap gap-2">
                            @foreach($service->features as $feature)
                            <span class="text-xs px-3 py-1 bg-brand-surface-2 border border-brand-border rounded-full text-text-muted">{{ $feature }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16 text-text-muted">Layanan akan segera tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-padding bg-brand-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="text-heading text-text-primary mb-4">Butuh Solusi Khusus?</h2>
        <p class="text-text-muted mb-8">Kami siap mendiskusikan kebutuhan spesifik bisnis Anda.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary">Konsultasi Gratis →</a>
    </div>
</section>
@endsection
