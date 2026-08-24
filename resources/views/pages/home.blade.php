@extends('layouts.app')

@section('content')
{{-- HERO --}}
<section class="hero-gradient relative min-h-[90vh] flex items-center overflow-hidden">
    <div class="absolute inset-0 grid-pattern opacity-50"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20">
        <div class="max-w-4xl">
            <div class="reveal">
                <span class="inline-block text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-6">Aldef Tech — Digital Technology Partner</span>
            </div>
            <h1 class="text-display-xl md:text-display-xl text-gradient mb-6 reveal reveal-delay-1">
                Bangun Sistem Digital<br>yang Menggerakkan Bisnis.
            </h1>
            <p class="text-text-secondary text-body-lg max-w-2xl mb-10 reveal reveal-delay-2">
                Custom software, SaaS, AI, website, dan automasi bisnis yang dirancang berdasarkan kebutuhan nyata bisnis Anda.
            </p>
            <div class="flex flex-wrap gap-4 reveal reveal-delay-3">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary">
                    Konsultasi Project
                    <svg class="w-4 h-4 ml-1.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('portfolio') }}" class="btn-secondary">
                    Lihat Portfolio
                </a>
            </div>
        </div>
    </div>
</section>

{{-- TRUST BAR --}}
<section class="py-16 border-t border-brand-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center reveal">
                <div class="text-3xl font-bold text-text-primary mb-1" data-counter="50">0</div>
                <div class="text-sm text-text-muted">Project Experience</div>
            </div>
            <div class="text-center reveal reveal-delay-1">
                <div class="text-3xl font-bold text-text-primary mb-1">100%</div>
                <div class="text-sm text-text-muted">Custom Solutions</div>
            </div>
            <div class="text-center reveal reveal-delay-2">
                <div class="text-3xl font-bold text-text-primary mb-1">24/7</div>
                <div class="text-sm text-text-muted">Technical Support</div>
            </div>
            <div class="text-center reveal reveal-delay-3">
                <div class="text-3xl font-bold text-text-primary mb-1">10+</div>
                <div class="text-sm text-text-muted">Years Experience</div>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES --}}
@if($services->count())
<section class="section-padding">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Our Services</span>
            <h2 class="text-display-sm text-text-primary mb-4">Solusi Digital untuk Bisnis Anda</h2>
            <p class="text-text-muted max-w-2xl mx-auto">Kami menyediakan layanan teknologi komprehensif yang dirancang untuk mengakselerasi transformasi digital bisnis Anda.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($services as $service)
            <div class="premium-card p-6 reveal reveal-delay-{{ $loop->iteration < 5 ? $loop->iteration : 1 }}">
                <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center text-accent text-xl mb-4">
                    {{ $service->icon ?? '⚡' }}
                </div>
                <h3 class="text-heading-sm text-text-primary mb-2">{{ $service->title }}</h3>
                <p class="text-text-muted text-sm leading-relaxed">{{ $service->short_description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- SOLUTIONS --}}
@if($solutions->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Solutions</span>
            <h2 class="text-display-sm text-text-primary mb-4">Sistem yang Kami Bangun</h2>
            <p class="text-text-muted max-w-2xl mx-auto">Solusi software yang telah terbukti membantu bisnis beroperasi lebih efisien.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($solutions as $solution)
            <div class="premium-card p-6 flex items-start gap-4 reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center text-accent shrink-0">
                    {{ $solution->icon ?? '📦' }}
                </div>
                <div>
                    <h3 class="font-semibold text-text-primary mb-1">{{ $solution->title }}</h3>
                    <p class="text-text-muted text-sm">{{ $solution->short_description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURED PORTFOLIO --}}
@if($portfolios->count())
<section class="section-padding">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12 reveal">
            <div>
                <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Portfolio</span>
                <h2 class="text-display-sm text-text-primary">Project Unggulan</h2>
            </div>
            <a href="{{ route('portfolio') }}" class="hidden sm:inline-flex text-sm text-accent hover:text-accent-light transition-colors">
                View All →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($portfolios as $item)
            <a href="{{ route('portfolio.show', $item->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ $loop->iteration }}">
                <div class="aspect-video bg-brand-surface-2 overflow-hidden">
                    @if($item->featured_image)
                    <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-lg">{{ $item->category->name ?? 'Project' }}</div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="text-xs text-accent mb-2">{{ $item->category->name ?? 'Project' }}</div>
                    <h3 class="font-semibold text-text-primary mb-2 group-hover:text-accent transition-colors">{{ $item->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2">{{ $item->short_description }}</p>
                    @if($item->technologies)
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach(array_slice($item->technologies, 0, 3) as $tech)
                        <span class="text-xs px-2 py-0.5 bg-brand-surface-2 text-text-dark rounded-full">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- PROCESS --}}
@if($processSteps->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Our Process</span>
            <h2 class="text-display-sm text-text-primary mb-4">Cara Kami Bekerja</h2>
            <p class="text-text-muted max-w-2xl mx-auto">Proses yang terstruktur untuk memastikan project berjalan sesuai rencana.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($processSteps as $step)
            <div class="relative reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                <div class="text-5xl font-bold text-brand-surface-2 absolute -top-2 -left-2">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="relative bg-brand-surface-2 border border-brand-border rounded-xl p-6 pt-8">
                    <div class="text-accent font-bold text-sm mb-2">STEP {{ $step->step_number }}</div>
                    <h3 class="font-semibold text-text-primary mb-2">{{ $step->title }}</h3>
                    <p class="text-text-muted text-sm">{{ $step->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CEO --}}
@if($ceoProfile)
<section class="section-padding">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="reveal">
                <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Leadership</span>
                <h2 class="text-display-sm text-text-primary mb-4">{{ $ceoProfile->name }}</h2>
                <p class="text-accent font-medium mb-4">{{ $ceoProfile->position }}</p>
                <p class="text-text-secondary leading-relaxed mb-6">{{ $ceoProfile->short_bio }}</p>
                @if($ceoProfile->skills && count($ceoProfile->skills))
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($ceoProfile->skills as $skill)
                    <span class="text-xs px-3 py-1 bg-brand-surface border border-brand-border rounded-full text-text-muted">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary inline-flex">
                    Diskusikan Project Anda dengan {{ $ceoProfile->name }}
                </a>
            </div>
            <div class="reveal reveal-delay-2">
                @if($ceoProfile->profile_photo)
                <img src="{{ asset('storage/' . $ceoProfile->profile_photo) }}" alt="{{ $ceoProfile->name }}" class="rounded-2xl w-full max-w-md mx-auto">
                @else
                <div class="aspect-square bg-brand-surface border border-brand-border rounded-2xl flex items-center justify-center max-w-md mx-auto">
                    <div class="text-center">
                        <div class="w-32 h-32 rounded-full bg-accent/10 flex items-center justify-center text-accent text-4xl font-bold mx-auto mb-4">
                            {{ substr($ceoProfile->name, 0, 1) }}
                        </div>
                        <div class="text-text-muted text-sm">{{ $ceoProfile->name }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- TESTIMONIALS --}}
@if($testimonials->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Testimonials</span>
            <h2 class="text-display-sm text-text-primary">Kata Mereka</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($testimonials as $testimonial)
            <div class="premium-card p-6 reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="text-yellow-400 text-sm mb-3">{{ str_repeat('★', $testimonial->rating) }}</div>
                <p class="text-text-secondary text-sm leading-relaxed mb-4">"{{ $testimonial->testimonial }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent text-sm font-semibold">
                        {{ substr($testimonial->client_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-text-primary">{{ $testimonial->client_name }}</div>
                        <div class="text-xs text-text-muted">{{ $testimonial->position }} {{ $testimonial->company ? '@ ' . $testimonial->company : '' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- LATEST BLOG --}}
@if($latestPosts->count())
<section class="section-padding">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12 reveal">
            <div>
                <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Insights</span>
                <h2 class="text-display-sm text-text-primary">Latest Articles</h2>
            </div>
            <a href="{{ route('blog') }}" class="hidden sm:inline-flex text-sm text-accent hover:text-accent-light transition-colors">View All →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ $loop->iteration }}">
                <div class="aspect-video bg-brand-surface-2 overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-sm">Insight</div>
                    @endif
                </div>
                <div class="p-6">
                    @if($post->category)
                    <span class="text-xs text-accent">{{ $post->category->name }}</span>
                    @endif
                    <h3 class="font-semibold text-text-primary mt-2 mb-2 group-hover:text-accent transition-colors">{{ $post->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2">{{ $post->excerpt }}</p>
                    <div class="text-xs text-text-dark mt-3">{{ $post->published_at->format('d M Y') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FAQ --}}
@if($faqs->count())
<section class="section-padding bg-brand-surface">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">FAQ</span>
            <h2 class="text-display-sm text-text-primary">Pertanyaan Umum</h2>
        </div>
        <div class="space-y-3" x-data="{ activeFaq: null }">
            @foreach($faqs as $faq)
            <div class="bg-brand-bg border border-brand-border rounded-xl overflow-hidden reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <button @click="activeFaq === {{ $faq->id }} ? activeFaq = null : activeFaq = {{ $faq->id }}"
                        class="w-full flex items-center justify-between px-6 py-4 text-left">
                    <span class="text-sm font-medium text-text-primary pr-4">{{ $faq->question }}</span>
                    <svg class="w-5 h-5 text-text-muted shrink-0 transition-transform" :class="activeFaq === {{ $faq->id }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === {{ $faq->id }}" x-collapse>
                    <div class="px-6 pb-4 text-sm text-text-secondary leading-relaxed">{{ $faq->answer }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FINAL CTA --}}
<section class="section-padding">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="text-display-sm text-text-primary mb-4">Punya Ide, Masalah, atau Proses Bisnis yang Ingin Didigitalisasi?</h2>
        <p class="text-text-muted text-body-lg mb-8 max-w-2xl mx-auto">Mari diskusikan bagaimana teknologi dapat membantu bisnis Anda bekerja lebih efektif.</p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary text-lg py-4 px-8">
            Mulai Konsultasi →
        </a>
    </div>
</section>
@endsection
