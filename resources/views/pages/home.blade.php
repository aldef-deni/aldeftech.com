@extends('layouts.app')

@section('content')
{{-- ================================================================
     HERO
     ================================================================ --}}
<section class="hero-gradient relative min-h-[92vh] flex items-center overflow-hidden">
    {{-- Decorative orbs --}}
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>

    {{-- Grid overlay --}}
    <div class="absolute inset-0 hero-grid opacity-40"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10 py-20 lg:py-28">
        <div class="max-w-[52rem]">
            {{-- Eyebrow --}}
            <div class="reveal">
                <span class="section-eyebrow">
                    <span class="accent-dot"></span>
                    Digital Technology Partner
                </span>
            </div>

            {{-- Headline --}}
            <h1 class="text-display-xl md:text-display-xl font-display text-gradient-warm mb-7 reveal reveal-delay-1 leading-[1.05]">
                Bangun Sistem Digital<br>
                yang Menggerakkan<br>
                Bisnis.
            </h1>

            {{-- Subheadline --}}
            <p class="text-text-secondary text-body-lg max-w-xl mb-12 reveal reveal-delay-2 leading-relaxed">
                Custom software, SaaS, AI, website, dan automasi bisnis yang dirancang berdasarkan kebutuhan nyata bisnis Anda.
            </p>

            {{-- CTAs --}}
            <div class="flex flex-wrap items-center gap-4 reveal reveal-delay-3">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary btn-lg magnetic">
                    Konsultasi Project
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('portfolio') }}" class="btn-secondary btn-lg">
                    Lihat Portfolio
                </a>
            </div>
        </div>
    </div>

    {{-- Bottom fade --}}
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-brand-bg to-transparent"></div>
</section>

{{-- ================================================================
     TRUST BAR
     ================================================================ --}}
<section class="py-16 lg:py-20 border-t border-brand-border relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12">
            <div class="text-center reveal">
                <div class="text-3xl lg:text-4xl font-display font-bold text-text-primary mb-1.5 tracking-tight" data-counter="50" data-counter-suffix="+">0</div>
                <div class="text-sm text-text-muted">Project Experience</div>
            </div>
            <div class="text-center reveal reveal-delay-1">
                <div class="text-3xl lg:text-4xl font-display font-bold text-text-primary mb-1.5 tracking-tight">100%</div>
                <div class="text-sm text-text-muted">Custom Solutions</div>
            </div>
            <div class="text-center reveal reveal-delay-2">
                <div class="text-3xl lg:text-4xl font-display font-bold text-text-primary mb-1.5 tracking-tight">24/7</div>
                <div class="text-sm text-text-muted">Technical Support</div>
            </div>
            <div class="text-center reveal reveal-delay-3">
                <div class="text-3xl lg:text-4xl font-display font-bold text-text-primary mb-1.5 tracking-tight" data-counter="10" data-counter-suffix="+">0</div>
                <div class="text-sm text-text-muted">Years Experience</div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     SERVICES
     ================================================================ --}}
@if($services->count())
<section class="section-padding relative">
    {{-- Mesh gradient bg --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        {{-- Section Header --}}
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Services</span>
            <h2 class="text-display-sm font-display text-text-primary mb-5 reveal reveal-delay-1">Solusi Digital<br>untuk Bisnis Anda</h2>
            <p class="text-text-muted text-body-lg reveal reveal-delay-2">Kami menyediakan layanan teknologi komprehensif yang dirancang untuk mengakselerasi transformasi digital bisnis Anda.</p>
        </div>

        {{-- Service Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($services as $service)
            <div class="premium-card p-7 group reveal reveal-delay-{{ $loop->iteration < 5 ? $loop->iteration : 1 }}">
                <div class="w-11 h-11 rounded-xl bg-accent/8 border border-accent/15 flex items-center justify-center text-accent text-lg mb-5 transition-all duration-300 group-hover:bg-accent/15 group-hover:border-accent/25 group-hover:scale-110">
                    {{ $service->icon ?? '⚡' }}
                </div>
                <h3 class="text-heading-sm font-display text-text-primary mb-2.5">{{ $service->title }}</h3>
                <p class="text-text-muted text-sm leading-relaxed">{{ $service->short_description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     SOLUTIONS
     ================================================================ --}}
@if($solutions->count())
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Solutions</span>
            <h2 class="text-display-sm font-display text-text-primary mb-5 reveal reveal-delay-1">Sistem yang<br>Kami Bangun</h2>
            <p class="text-text-muted text-body-lg reveal reveal-delay-2">Solusi software yang telah terbukti membantu bisnis beroperasi lebih efisien.</p>
        </div>

        {{-- Solutions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($solutions as $solution)
            <div class="premium-card p-7 flex items-start gap-5 group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="w-10 h-10 rounded-lg bg-accent/8 border border-accent/15 flex items-center justify-center text-accent shrink-0 transition-all duration-300 group-hover:scale-110">
                    {{ $solution->icon ?? '📦' }}
                </div>
                <div>
                    <h3 class="font-display font-semibold text-text-primary mb-1.5">{{ $solution->title }}</h3>
                    <p class="text-text-muted text-sm leading-relaxed">{{ $solution->short_description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     FEATURED PORTFOLIO
     ================================================================ --}}
@if($portfolios->count())
<section class="section-padding relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="flex items-end justify-between mb-14 lg:mb-16">
            <div class="max-w-xl">
                <span class="section-eyebrow reveal">Portfolio</span>
                <h2 class="text-display-sm font-display text-text-primary reveal reveal-delay-1">Project Unggulan</h2>
            </div>
            <a href="{{ route('portfolio') }}" class="btn-link hidden sm:inline-flex reveal reveal-delay-2">
                View All
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Portfolio Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($portfolios as $item)
            <a href="{{ route('portfolio.show', $item->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ $loop->iteration }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden relative">
                    @if($item->featured_image)
                    <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-sm font-medium">{{ $item->category->name ?? 'Project' }}</div>
                    @endif
                    {{-- Gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-surface/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <div class="p-6">
                    <div class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase mb-2.5">{{ $item->category->name ?? 'Project' }}</div>
                    <h3 class="font-display font-semibold text-text-primary mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $item->title }}</h3>
                    <p class="text-text-muted text-sm leading-relaxed line-clamp-2">{{ $item->short_description }}</p>
                    @if($item->technologies)
                    <div class="flex flex-wrap gap-1.5 mt-4">
                        @foreach(array_slice($item->technologies, 0, 3) as $tech)
                        <span class="tag text-[0.625rem]">{{ $tech }}</span>
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

{{-- ================================================================
     WHY ALDEF TECH — Process
     ================================================================ --}}
@if($processSteps->count())
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Our Process</span>
            <h2 class="text-display-sm font-display text-text-primary mb-5 reveal reveal-delay-1">Cara Kami<br>Bekerja</h2>
            <p class="text-text-muted text-body-lg reveal reveal-delay-2">Proses yang terstruktur untuk memastikan project berjalan sesuai rencana.</p>
        </div>

        {{-- Steps Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($processSteps as $step)
            <div class="relative reveal reveal-delay-{{ min($loop->iteration, 4) }}">
                {{-- Large number watermark --}}
                <div class="text-[5rem] font-display font-bold text-brand-surface-3 absolute -top-6 -left-2 leading-none select-none pointer-events-none">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="relative bg-brand-surface-2 border border-brand-border rounded-2xl p-6 pt-10 transition-all duration-300 hover:border-accent/20 hover:shadow-glow">
                    <div class="text-accent font-display font-bold text-[0.6875rem] tracking-[0.15em] uppercase mb-3">Step {{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</div>
                    <h3 class="font-display font-semibold text-text-primary text-lg mb-2">{{ $step->title }}</h3>
                    <p class="text-text-muted text-sm leading-relaxed">{{ $step->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     CEO PROFILE
     ================================================================ --}}
@if($ceoProfile)
<section class="section-padding relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-20 items-center">
            {{-- Content --}}
            <div class="reveal">
                <span class="section-eyebrow">Leadership</span>
                <h2 class="text-display-sm font-display text-text-primary mb-3">{{ $ceoProfile->name }}</h2>
                <p class="text-accent font-display font-semibold mb-6">{{ $ceoProfile->position }}</p>
                <p class="text-text-secondary leading-relaxed mb-8">{{ $ceoProfile->short_bio }}</p>

                @if($ceoProfile->skills && count($ceoProfile->skills))
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($ceoProfile->skills as $skill)
                    <span class="tag">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif

                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary magnetic">
                    Diskusikan Project Anda dengan {{ $ceoProfile->name }}
                    <svg class="w-4 h-4 ml-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Photo --}}
            <div class="reveal-right reveal-delay-2">
                @if($ceoProfile->profile_photo)
                <div class="relative">
                    <img src="{{ asset('storage/' . $ceoProfile->profile_photo) }}" alt="{{ $ceoProfile->name }}" class="rounded-2xl w-full max-w-md mx-auto border border-brand-border">
                    <div class="absolute -inset-4 rounded-3xl border border-accent/10 -z-10"></div>
                </div>
                @else
                <div class="aspect-[4/3] bg-brand-surface border border-brand-border rounded-2xl flex items-center justify-center max-w-md mx-auto relative">
                    <div class="absolute -inset-4 rounded-3xl border border-accent/10 -z-10"></div>
                    <div class="text-center">
                        <div class="w-28 h-28 rounded-full bg-gradient-to-br from-accent/15 to-accent/5 border border-accent/20 flex items-center justify-center text-accent text-4xl font-display font-bold mx-auto mb-4">
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

{{-- ================================================================
     TESTIMONIALS
     ================================================================ --}}
@if($testimonials->count())
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Testimonials</span>
            <h2 class="text-display-sm font-display text-text-primary reveal reveal-delay-1">Kata Mereka</h2>
        </div>

        {{-- Testimonials Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($testimonials as $testimonial)
            <div class="premium-card p-7 flex flex-col reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                {{-- Rating --}}
                <div class="flex gap-0.5 mb-4">
                    @for($i = 0; $i < $testimonial->rating; $i++)
                    <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>

                {{-- Quote --}}
                <p class="text-text-secondary text-sm leading-relaxed flex-1 mb-6">"{{ $testimonial->testimonial }}"</p>

                {{-- Author --}}
                <div class="flex items-center gap-3 pt-5 border-t border-brand-border">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent/20 to-accent/5 border border-accent/15 flex items-center justify-center text-accent text-sm font-display font-semibold">
                        {{ substr($testimonial->client_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-text-primary">{{ $testimonial->client_name }}</div>
                        <div class="text-xs text-text-muted">{{ $testimonial->position }}{{ $testimonial->company ? ' @ ' . $testimonial->company : '' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     LATEST BLOG / INSIGHTS
     ================================================================ --}}
@if($latestPosts->count())
<section class="section-padding relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="flex items-end justify-between mb-14 lg:mb-16">
            <div class="max-w-xl">
                <span class="section-eyebrow reveal">Insights</span>
                <h2 class="text-display-sm font-display text-text-primary reveal reveal-delay-1">Latest Articles</h2>
            </div>
            <a href="{{ route('blog') }}" class="btn-link hidden sm:inline-flex reveal reveal-delay-2">
                View All
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Blog Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ $loop->iteration }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-text-dark text-sm">Insight</div>
                    @endif
                </div>
                <div class="p-6">
                    @if($post->category)
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $post->category->name }}</span>
                    @endif
                    <h3 class="font-display font-semibold text-text-primary mt-2.5 mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $post->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                    <div class="text-xs text-text-dark mt-4">{{ $post->published_at->format('d M Y') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     FAQ
     ================================================================ --}}
@if($faqs->count())
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-3xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Section Header --}}
        <div class="text-center mb-14">
            <span class="section-eyebrow justify-center reveal">FAQ</span>
            <h2 class="text-display-sm font-display text-text-primary reveal reveal-delay-1">Pertanyaan Umum</h2>
        </div>

        {{-- FAQ Accordion --}}
        <div class="space-y-3" x-data="{ activeFaq: null }">
            @foreach($faqs as $faq)
            <div class="bg-brand-bg border border-brand-border rounded-xl overflow-hidden transition-all duration-300 {{ 'border-accent/20' }}">
                <button @click="activeFaq === {{ $faq->id }} ? activeFaq = null : activeFaq = {{ $faq->id }}"
                        class="w-full flex items-center justify-between px-6 py-5 text-left group">
                    <span class="text-sm font-medium text-text-primary pr-4 group-hover:text-accent-light transition-colors duration-200">{{ $faq->question }}</span>
                    <svg class="w-4 h-4 text-text-muted shrink-0 transition-transform duration-300" :class="activeFaq === {{ $faq->id }} ? 'rotate-180 text-accent' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === {{ $faq->id }}" x-collapse>
                    <div class="px-6 pb-5 text-sm text-text-secondary leading-relaxed border-t border-brand-border pt-4">{{ $faq->answer }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     FINAL CTA
     ================================================================ --}}
<section class="section-padding relative overflow-hidden">
    {{-- Background glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.08)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-display-sm font-display text-text-primary mb-5 reveal leading-tight">
            Punya Ide, Masalah, atau<br>
            Proses Bisnis yang Ingin Didigitalisasi?
        </h2>
        <p class="text-text-muted text-body-lg mb-10 max-w-2xl mx-auto reveal reveal-delay-1">
            Mari diskusikan bagaimana teknologi dapat membantu bisnis Anda bekerja lebih efektif.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary btn-lg magnetic reveal reveal-delay-2">
            Mulai Konsultasi
            <svg class="w-4 h-4 ml-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
