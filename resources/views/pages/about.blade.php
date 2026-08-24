@extends('layouts.app')
@section('content')
@php
$pageTitle = 'About Aldef Tech';
$metaDescription = 'Aldef Tech adalah technology partner yang membantu bisnis membangun sistem digital sesuai kebutuhan.';
@endphp

{{-- Hero --}}
<section class="section-padding pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">About Us</span>
            <h1 class="text-display-sm text-text-primary mb-6">{{ \App\Models\SiteSetting::get('about_title', 'About Aldef Tech') }}</h1>
            <p class="text-text-secondary text-body-lg leading-relaxed">
                {{ \App\Models\SiteSetting::get('about_subtitle', 'Aldef Tech adalah premium digital technology partner yang membantu bisnis membangun sistem digital sesuai kebutuhan. Dengan fokus pada engineering, business process, dan automation, kami membantu bisnis Anda bergerak lebih cepat dan efisien.') }}
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="premium-card p-8 reveal">
                <div class="text-accent font-semibold text-sm mb-4">Our Mission</div>
                <p class="text-text-secondary leading-relaxed">{{ \App\Models\SiteSetting::get('about_mission', 'Membantu bisnis membangun sistem digital yang efektif, scalable, dan sesuai dengan kebutuhan operasional mereka.') }}</p>
            </div>
            <div class="premium-card p-8 reveal reveal-delay-1">
                <div class="text-accent font-semibold text-sm mb-4">Our Vision</div>
                <p class="text-text-secondary leading-relaxed">{{ \App\Models\SiteSetting::get('about_vision', 'Menjadi technology partner terpercaya yang membantu transformasi digital bisnis Indonesia melalui solusi software berkualitas tinggi.') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Full Content --}}
@if(\App\Models\SiteSetting::get('about_content'))
<section class="section-padding">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose-dark leading-relaxed reveal">
            {!! \App\Models\SiteSetting::get('about_content') !!}
        </div>
    </div>
</section>
@endif

{{-- CEO --}}
@if($ceoProfile)
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="reveal">
                <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Leadership</span>
                <h2 class="text-heading text-text-primary mb-2">{{ $ceoProfile->name }}</h2>
                <p class="text-accent font-medium mb-6">{{ $ceoProfile->position }}</p>
                <div class="text-text-secondary leading-relaxed whitespace-pre-line mb-6">{{ $ceoProfile->full_bio ?? $ceoProfile->short_bio }}</div>
                @if($ceoProfile->skills && count($ceoProfile->skills))
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-text-primary mb-3">Skills & Expertise</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($ceoProfile->skills as $skill)
                        <span class="text-xs px-3 py-1 bg-brand-surface-2 border border-brand-border rounded-full text-text-muted">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary inline-flex">
                    Diskusikan Project Anda
                </a>
            </div>
            <div class="reveal reveal-delay-2">
                @if($ceoProfile->profile_photo)
                <img src="{{ asset('storage/' . $ceoProfile->profile_photo) }}" alt="{{ $ceoProfile->name }}" class="rounded-2xl w-full max-w-md mx-auto">
                @else
                <div class="aspect-square bg-brand-surface-2 border border-brand-border rounded-2xl flex items-center justify-center max-w-md mx-auto">
                    <div class="text-center">
                        <div class="w-32 h-32 rounded-full bg-accent/10 flex items-center justify-center text-accent text-4xl font-bold mx-auto mb-4">{{ substr($ceoProfile->name, 0, 1) }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="section-padding">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="text-display-sm text-text-primary mb-4">Siap Memulai Project?</h2>
        <p class="text-text-muted mb-8">Mari diskusikan bagaimana kami dapat membantu bisnis Anda.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary">Mulai Konsultasi →</a>
    </div>
</section>
@endsection
