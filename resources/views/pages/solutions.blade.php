@extends('layouts.app')
@section('content')
@php $pageTitle = 'Solutions — Aldef Tech'; @endphp

<section class="section-padding pt-20 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Solutions</span>
            <h1 class="text-display-sm font-display text-text-primary mb-6 reveal reveal-delay-1">Sistem yang Kami Bangun</h1>
            <p class="text-text-secondary text-body-lg leading-relaxed reveal reveal-delay-2">Solusi software yang dirancang untuk menyelesaikan masalah bisnis nyata — dari inventory hingga AI.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse($solutions as $solution)
            <div class="premium-card p-8 lg:p-10 group reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                <div class="w-12 h-12 rounded-xl bg-accent/8 border border-accent/15 flex items-center justify-center text-accent text-xl mb-5 transition-all duration-300 group-hover:scale-110">
                    {{ $solution->icon ?? '📦' }}
                </div>
                <h2 class="text-heading-sm font-display text-text-primary mb-3">{{ $solution->title }}</h2>
                <p class="text-text-secondary text-sm leading-relaxed mb-5">{{ $solution->short_description }}</p>
                @if($solution->features && count($solution->features))
                <div class="space-y-2">
                    @foreach($solution->features as $feature)
                    <div class="flex items-center gap-2.5 text-sm text-text-muted">
                        <svg class="w-4 h-4 text-accent shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $feature }}
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
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-heading font-display text-text-primary mb-4 reveal">Tidak Menemukan Solusi yang Dibutuhkan?</h2>
        <p class="text-text-muted mb-8 reveal reveal-delay-1">Kami membangun solusi custom sesuai kebutuhan bisnis Anda.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary magnetic reveal reveal-delay-2">Diskusikan Kebutuhan Anda →</a>
    </div>
</section>
@endsection
