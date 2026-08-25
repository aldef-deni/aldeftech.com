@extends('layouts.app')
@section('content')
@php
$pageTitle = 'About Aldef Tech';
$metaDescription = 'Aldef Tech adalah premium digital technology partner yang membantu bisnis membangun sistem digital sesuai kebutuhan.';
@endphp

{{-- Hero --}}
<section class="section-padding pt-24 lg:pt-32 relative overflow-hidden">
    <div class="hero-orb hero-orb-1 opacity-50"></div>
    <div class="hero-orb hero-orb-2 opacity-50"></div>
    <div class="absolute inset-0 hero-grid opacity-30"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">About Us</span>
            <h1 class="text-4xl sm:text-5xl lg:text-display-sm font-display text-gradient-hero mb-8 reveal reveal-delay-1">
                {{ \App\Models\SiteSetting::get('about_title', 'About Aldef Tech') }}
            </h1>
            <p class="text-text-secondary text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                {{ \App\Models\SiteSetting::get('about_subtitle', 'Aldef Tech adalah premium digital technology partner yang membantu bisnis membangun sistem digital sesuai kebutuhan. Dengan fokus pada engineering, business process, dan automation, kami membantu bisnis Anda bergerak lebih cepat dan efisien.') }}
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="premium-card p-8 lg:p-12 reveal">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-orange/15 to-brand-red/10 border border-brand-orange/20 flex items-center justify-center text-brand-orange text-xl mb-6">
                    🎯
                </div>
                <div class="section-eyebrow mb-4 text-brand-orange">Our Mission</div>
                <h3 class="text-2xl font-display font-bold text-text-primary mb-4">Empowering Digital Growth</h3>
                <p class="text-text-secondary leading-relaxed text-base">{{ \App\Models\SiteSetting::get('about_mission', 'Membantu bisnis membangun sistem digital yang efektif, scalable, dan sesuai dengan kebutuhan operasional mereka.') }}</p>
            </div>
            <div class="premium-card p-8 lg:p-12 reveal reveal-delay-1">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent/15 to-brand-cyan/10 border border-accent/20 flex items-center justify-center text-accent text-xl mb-6">
                    🔭
                </div>
                <div class="section-eyebrow mb-4">Our Vision</div>
                <h3 class="text-2xl font-display font-bold text-text-primary mb-4">Leading Technology Partner</h3>
                <p class="text-text-secondary leading-relaxed text-base">{{ \App\Models\SiteSetting::get('about_vision', 'Menjadi technology partner terpercaya yang membantu transformasi digital bisnis Indonesia melalui solusi software berkualitas tinggi.') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Full Content --}}
@if(\App\Models\SiteSetting::get('about_content'))
<section class="section-padding relative">
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="prose-dark leading-relaxed text-lg reveal">
            {!! \App\Models\SiteSetting::get('about_content') !!}
        </div>
    </div>
</section>
@endif

{{-- CEO / Leadership --}}
@if($ceoProfile)
<section class="section-padding bg-brand-surface relative overflow-hidden">
    <div class="absolute top-1/2 right-0 -translate-y-1/2 w-[500px] h-[500px] bg-[radial-gradient(circle,rgba(168,85,247,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
            <div class="reveal">
                <span class="section-eyebrow">Leadership</span>
                <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-2">{{ $ceoProfile->name }}</h2>
                <p class="text-accent-light font-display font-semibold text-lg mb-8">{{ $ceoProfile->position }}</p>
                <div class="text-text-secondary leading-relaxed whitespace-pre-line mb-8 text-base lg:text-lg">{{ $ceoProfile->full_bio ?? $ceoProfile->short_bio }}</div>

                @if($ceoProfile->skills && count($ceoProfile->skills))
                <div class="mb-10">
                    <h3 class="text-sm font-display font-semibold text-text-primary mb-3">Skills & Expertise</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($ceoProfile->skills as $skill)
                        <span class="tag-accent tag">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary btn-lg magnetic">
                    Diskusikan Project Anda
                    <svg class="w-4 h-4 ml-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="reveal-right reveal-delay-2">
                @if($ceoProfile->profile_photo)
                <div class="relative">
                    <img src="{{ asset('storage/' . $ceoProfile->profile_photo) }}" alt="{{ $ceoProfile->name }}" class="rounded-2xl w-full max-w-md mx-auto border border-brand-border shadow-elevated">
                    <div class="absolute -inset-3 rounded-3xl border border-accent/10 -z-10"></div>
                </div>
                @else
                <div class="aspect-[4/3] bg-gradient-to-br from-brand-surface-2 to-brand-surface-3 border border-brand-border rounded-2xl flex items-center justify-center max-w-md mx-auto relative overflow-hidden">
                    <div class="absolute -inset-3 rounded-3xl border border-accent/8 -z-10"></div>
                    <div class="text-center relative z-10">
                        <div class="w-28 h-28 rounded-full bg-gradient-to-br from-accent/15 to-brand-cyan/10 border border-accent/20 flex items-center justify-center mx-auto mb-4 shadow-[0_0_40px_rgba(168,85,247,0.1)]">
                            <span class="text-accent text-4xl font-display font-bold">{{ substr($ceoProfile->name, 0, 1) }}</span>
                        </div>
                        <div class="text-text-secondary font-medium">{{ $ceoProfile->name }}</div>
                        <div class="text-text-muted text-sm">{{ $ceoProfile->position }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="section-padding relative overflow-hidden">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-4 reveal">Siap Memulai Project?</h2>
        <p class="text-text-muted text-lg mb-10 reveal reveal-delay-1">Mari diskusikan bagaimana kami dapat membantu bisnis Anda berkembang melalui teknologi digital.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-gradient btn-lg magnetic reveal reveal-delay-2 px-10 py-4">Mulai Konsultasi →</a>
    </div>
</section>
@endsection
