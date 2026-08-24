@extends('layouts.app')
@section('content')
@php $pageTitle = 'Solutions — Aldef Tech'; @endphp

<section class="section-padding pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Solutions</span>
            <h1 class="text-display-sm text-text-primary mb-6">Sistem yang Kami Bangun</h1>
            <p class="text-text-secondary text-body-lg">Solusi software yang dirancang untuk menyelesaikan masalah bisnis nyata — dari inventory hingga AI.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($solutions as $solution)
            <div class="premium-card p-8 reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center text-accent text-xl mb-4">
                    {{ $solution->icon ?? '📦' }}
                </div>
                <h2 class="text-heading-sm text-text-primary mb-3">{{ $solution->title }}</h2>
                <p class="text-text-secondary text-sm leading-relaxed mb-4">{{ $solution->short_description }}</p>
                @if($solution->features && count($solution->features))
                <div class="space-y-1.5">
                    @foreach($solution->features as $feature)
                    <div class="flex items-center gap-2 text-sm text-text-muted">
                        <span class="text-accent">✓</span> {{ $feature }}
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-2 text-center py-16 text-text-muted">Solutions akan segera tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section-padding bg-brand-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="text-heading text-text-primary mb-4">Tidak Menemukan Solusi yang Dibutuhkan?</h2>
        <p class="text-text-muted mb-8">Kami membangun solusi custom sesuai kebutuhan bisnis Anda.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary">Diskusikan Kebutuhan Anda →</a>
    </div>
</section>
@endsection
