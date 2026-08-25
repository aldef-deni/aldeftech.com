@extends('layouts.app')

@section('content')
{{-- ================================================================
     HERO — Ultra Premium
     ================================================================ --}}
<section class="hero-gradient relative min-h-[95vh] flex items-center overflow-hidden">
    {{-- Decorative orbs --}}
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    {{-- Grid overlay --}}
    <div class="absolute inset-0 hero-grid opacity-40"></div>

    {{-- Floating geometric particles --}}
    <div class="absolute top-1/4 right-[15%] w-px h-32 bg-gradient-to-b from-transparent via-accent/20 to-transparent animate-float hidden lg:block"></div>
    <div class="absolute top-1/3 right-[30%] w-px h-24 bg-gradient-to-b from-transparent via-brand-cyan/15 to-transparent animate-float hidden lg:block" style="animation-delay: -2s"></div>
    <div class="absolute bottom-1/4 right-[20%] w-1 h-1 rounded-full bg-accent/30 animate-pulse-glow hidden lg:block"></div>
    <div class="absolute top-[40%] right-[25%] w-1.5 h-1.5 rounded-full bg-brand-orange/25 animate-float hidden lg:block" style="animation-delay: -3s"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10 py-24 lg:py-32">
        <div class="max-w-[56rem]">
            {{-- Eyebrow --}}
            <div class="reveal">
                <span class="section-eyebrow">
                    <span class="accent-dot"></span>
                    ALDEF TECH — Digital Technology Partner
                </span>
            </div>

            {{-- Headline --}}
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-display-xl font-display text-gradient-hero mb-8 reveal reveal-delay-1 leading-[1.05] tracking-tight">
                Bangun Sistem Digital<br>
                yang Menggerakkan<br>
                <span class="text-gradient-brand">Bisnis.</span>
            </h1>

            {{-- Subheadline --}}
            <p class="text-text-secondary text-lg lg:text-xl max-w-xl mb-14 reveal reveal-delay-2 leading-relaxed">
                Custom software, SaaS, AI, website, dan automasi bisnis yang dirancang berdasarkan kebutuhan nyata bisnis Anda.
            </p>

            {{-- CTAs --}}
            <div class="flex flex-wrap items-center gap-5 reveal reveal-delay-3">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-primary btn-lg magnetic">
                    Konsultasi Project
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('portfolio') }}" class="btn-secondary btn-lg">
                    Lihat Portfolio
                </a>
            </div>

            {{-- Trust indicators --}}
            <div class="mt-16 lg:mt-20 flex items-center gap-8 reveal reveal-delay-4">
                <div class="flex -space-x-2">
                    @for($i = 0; $i < 4; $i++)
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-surface-3 to-brand-elevated border-2 border-brand-bg flex items-center justify-center">
                        <span class="text-[0.6rem] font-bold text-accent/60">{{ ['AT', 'CL', 'PT', 'SY'][$i] }}</span>
                    </div>
                    @endfor
                </div>
                <div>
                    <div class="text-sm font-semibold text-text-primary">Trusted by growing businesses</div>
                    <div class="text-xs text-text-muted">Custom solutions for each unique need</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom fade --}}
    <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-brand-bg via-brand-bg/60 to-transparent"></div>
</section>

{{-- ================================================================
     TRUST BAR — Stats
     ================================================================ --}}
<section class="py-16 lg:py-24 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-16">
            <div class="text-center reveal group">
                <div class="text-3xl lg:text-5xl font-display font-bold text-gradient-brand mb-2 tracking-tight" data-counter="50" data-counter-suffix="+">0</div>
                <div class="text-sm text-text-muted group-hover:text-text-secondary transition-colors">Project Experience</div>
            </div>
            <div class="text-center reveal reveal-delay-1 group">
                <div class="text-3xl lg:text-5xl font-display font-bold text-text-primary mb-2 tracking-tight">100%</div>
                <div class="text-sm text-text-muted group-hover:text-text-secondary transition-colors">Custom Solutions</div>
            </div>
            <div class="text-center reveal reveal-delay-2 group">
                <div class="text-3xl lg:text-5xl font-display font-bold text-text-primary mb-2 tracking-tight">24/7</div>
                <div class="text-sm text-text-muted group-hover:text-text-secondary transition-colors">Technical Support</div>
            </div>
            <div class="text-center reveal reveal-delay-3 group">
                <div class="text-3xl lg:text-5xl font-display font-bold text-gradient-brand mb-2 tracking-tight" data-counter="10" data-counter-suffix="+">0</div>
                <div class="text-sm text-text-muted group-hover:text-text-secondary transition-colors">Years Experience</div>
            </div>
        </div>
    </div>
    {{-- Divider --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 mt-16 lg:mt-24">
        <div class="divider-fine"></div>
    </div>
</section>

{{-- ================================================================
     SERVICES
     ================================================================ --}}
@if($services->count())
<section class="section-padding relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.03)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Services</span>
            <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-5 reveal reveal-delay-1">Solusi Digital<br>untuk Bisnis Anda</h2>
            <p class="text-text-muted text-body-lg reveal reveal-delay-2">Kami menyediakan layanan teknologi komprehensif yang dirancang untuk mengakselerasi transformasi digital bisnis Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($services as $service)
            <div class="premium-card p-7 lg:p-8 group reveal reveal-delay-{{ $loop->iteration < 5 ? $loop->iteration : 1 }}">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent/10 to-accent/5 border border-accent/15 flex items-center justify-center text-accent text-lg mb-6 transition-all duration-500 group-hover:bg-accent/15 group-hover:border-accent/30 group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(168,85,247,0.15)]">
                    {{ $service->icon ?? '⚡' }}
                </div>
                <h3 class="text-heading-sm font-display text-text-primary mb-3 group-hover:text-accent-light transition-colors duration-300">{{ $service->title }}</h3>
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
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Solutions</span>
            <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-5 reveal reveal-delay-1">Sistem yang<br>Kami Bangun</h2>
            <p class="text-text-muted text-body-lg reveal reveal-delay-2">Solusi software yang telah terbukti membantu bisnis beroperasi lebih efisien.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($solutions as $solution)
            <div class="premium-card p-7 flex items-start gap-5 group reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-accent/10 to-brand-cyan/5 border border-accent/15 flex items-center justify-center text-accent shrink-0 transition-all duration-500 group-hover:scale-110 group-hover:shadow-[0_0_15px_rgba(168,85,247,0.12)]">
                    {{ $solution->icon ?? '📦' }}
                </div>
                <div>
                    <h3 class="font-display font-semibold text-text-primary mb-1.5 group-hover:text-accent-light transition-colors duration-300">{{ $solution->title }}</h3>
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
        <div class="flex items-end justify-between mb-14 lg:mb-16">
            <div class="max-w-xl">
                <span class="section-eyebrow reveal">Portfolio</span>
                <h2 class="text-3xl md:text-display-sm font-display text-text-primary reveal reveal-delay-1">Project Unggulan</h2>
            </div>
            <a href="{{ route('portfolio') }}" class="btn-link hidden sm:inline-flex reveal reveal-delay-2">
                View All
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($portfolios as $item)
            <a href="{{ route('portfolio.show', $item->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ $loop->iteration }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden relative">
                    @if($item->featured_image)
                    <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-surface-2 to-brand-surface-3">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-xl bg-accent/5 border border-accent/10 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-accent/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-text-dark text-xs font-medium">{{ $item->category->name ?? 'Project' }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-bg/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <div class="p-6 lg:p-7">
                    <div class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase mb-2.5">{{ $item->category->name ?? 'Project' }}</div>
                    <h3 class="font-display font-semibold text-text-primary text-lg mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $item->title }}</h3>
                    <p class="text-text-muted text-sm leading-relaxed line-clamp-2">{{ $item->short_description }}</p>
                    @if($item->technologies)
                    <div class="flex flex-wrap gap-1.5 mt-5">
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
     PROCESS
     ================================================================ --}}
@if($processSteps->count())
<section class="section-padding bg-brand-surface relative overflow-hidden">
    {{-- Background decoration --}}
    <div class="absolute top-1/2 left-0 w-[400px] h-[400px] bg-[radial-gradient(circle,rgba(245,158,11,0.02)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-[radial-gradient(circle,rgba(6,182,212,0.02)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Our Process</span>
            <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-5 reveal reveal-delay-1">Cara Kami<br>Bekerja</h2>
            <p class="text-text-muted text-body-lg reveal reveal-delay-2">Proses yang terstruktur untuk memastikan project berjalan sesuai rencana.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($processSteps as $step)
            <div class="relative reveal reveal-delay-{{ min($loop->iteration, 4) }} group">
                <div class="text-[5rem] font-display font-bold bg-gradient-to-b from-brand-surface-3/80 to-transparent bg-clip-text text-transparent absolute -top-6 -left-2 leading-none select-none pointer-events-none">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="relative bg-brand-surface-2/80 backdrop-blur-sm border border-brand-border rounded-2xl p-7 pt-12 transition-all duration-500 group-hover:border-accent/20 group-hover:shadow-[0_0_30px_rgba(168,85,247,0.06)]">
                    <div class="text-accent font-display font-bold text-[0.6875rem] tracking-[0.15em] uppercase mb-3">Step {{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</div>
                    <h3 class="font-display font-semibold text-text-primary text-lg mb-2.5 group-hover:text-accent-light transition-colors duration-300">{{ $step->title }}</h3>
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
<section class="section-padding relative overflow-hidden">
    {{-- Background glow --}}
    <div class="absolute top-1/2 right-0 -translate-y-1/2 w-[500px] h-[500px] bg-[radial-gradient(circle,rgba(168,85,247,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
            <div class="reveal">
                <span class="section-eyebrow">Leadership</span>
                <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-3">{{ $ceoProfile->name }}</h2>
                <p class="text-accent-light font-display font-semibold text-lg mb-8">{{ $ceoProfile->position }}</p>
                <p class="text-text-secondary leading-relaxed mb-10 text-lg">{{ $ceoProfile->short_bio }}</p>

                @if($ceoProfile->skills && count($ceoProfile->skills))
                <div class="flex flex-wrap gap-2 mb-10">
                    @foreach($ceoProfile->skills as $skill)
                    <span class="tag-accent tag">{{ $skill }}</span>
                    @endforeach
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
                    <div class="absolute -inset-3 rounded-3xl border border-accent/8 -z-10"></div>
                    <div class="absolute -inset-6 rounded-[2rem] border border-brand-border/30 -z-20"></div>
                </div>
                @else
                <div class="aspect-[4/3] bg-gradient-to-br from-brand-surface to-brand-surface-2 border border-brand-border rounded-2xl flex items-center justify-center max-w-md mx-auto relative overflow-hidden">
                    <div class="absolute -inset-3 rounded-3xl border border-accent/8 -z-10"></div>
                    {{-- Abstract pattern --}}
                    <div class="absolute inset-0 hero-grid opacity-30"></div>
                    <div class="text-center relative z-10">
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-accent/15 to-brand-cyan/10 border border-accent/20 flex items-center justify-center mx-auto mb-4 shadow-[0_0_40px_rgba(168,85,247,0.1)]">
                            <span class="text-accent text-5xl font-display font-bold">{{ substr($ceoProfile->name, 0, 1) }}</span>
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

{{-- ================================================================
     TESTIMONIALS
     ================================================================ --}}
@if($testimonials->count())
<section class="section-padding bg-brand-surface relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <span class="section-eyebrow reveal">Testimonials</span>
            <h2 class="text-3xl md:text-display-sm font-display text-text-primary reveal reveal-delay-1">Kata Mereka</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($testimonials as $testimonial)
            <div class="premium-card p-7 lg:p-8 flex flex-col reveal reveal-delay-{{ min($loop->iteration, 3) }}">
                {{-- Rating --}}
                <div class="flex gap-0.5 mb-5">
                    @for($i = 0; $i < $testimonial->rating; $i++)
                    <svg class="w-4 h-4 text-brand-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>

                <p class="text-text-secondary text-sm leading-relaxed flex-1 mb-6 italic">"{{ $testimonial->testimonial }}"</p>

                <div class="flex items-center gap-3.5 pt-5 border-t border-brand-border">
                    @if($testimonial->photo)
                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->client_name }}" class="w-10 h-10 rounded-full object-cover border border-brand-border">
                    @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent/15 to-brand-cyan/10 border border-accent/15 flex items-center justify-center text-accent text-sm font-display font-semibold">
                        {{ substr($testimonial->client_name, 0, 1) }}
                    </div>
                    @endif
                    <div>
                        <div class="text-sm font-semibold text-text-primary">{{ $testimonial->client_name }}</div>
                        <div class="text-xs text-text-muted">{{ $testimonial->position }}{{ $testimonial->company ? ' · ' . $testimonial->company : '' }}</div>
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
        <div class="flex items-end justify-between mb-14 lg:mb-16">
            <div class="max-w-xl">
                <span class="section-eyebrow reveal">Insights</span>
                <h2 class="text-3xl md:text-display-sm font-display text-text-primary reveal reveal-delay-1">Latest Articles</h2>
            </div>
            <a href="{{ route('blog') }}" class="btn-link hidden sm:inline-flex reveal reveal-delay-2">
                View All
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="premium-card overflow-hidden group reveal reveal-delay-{{ $loop->iteration }}">
                <div class="aspect-[16/10] bg-brand-surface-2 overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-surface-2 to-brand-surface-3">
                        <svg class="w-8 h-8 text-text-dark/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="p-6 lg:p-7">
                    @if($post->category)
                    <span class="text-[0.6875rem] font-semibold tracking-wider text-accent uppercase">{{ $post->category->name }}</span>
                    @endif
                    <h3 class="font-display font-semibold text-text-primary mt-2.5 mb-2 group-hover:text-accent-light transition-colors duration-300">{{ $post->title }}</h3>
                    <p class="text-text-muted text-sm line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                    <div class="text-xs text-text-dark mt-5 font-medium">{{ $post->published_at->format('d M Y') }}</div>
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
        <div class="text-center mb-14">
            <span class="section-eyebrow justify-center reveal">FAQ</span>
            <h2 class="text-3xl md:text-display-sm font-display text-text-primary reveal reveal-delay-1">Pertanyaan Umum</h2>
        </div>

        <div class="space-y-3" x-data="{ activeFaq: null }">
            @foreach($faqs as $faq)
            <div class="bg-brand-bg/60 backdrop-blur-sm border border-brand-border rounded-xl overflow-hidden transition-all duration-300 hover:border-accent/15 reveal reveal-delay-{{ min($loop->iteration, 3) }}">
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
    {{-- Background gradient orbs --}}
    <div class="absolute top-1/2 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute top-1/2 right-1/4 translate-x-1/2 -translate-y-1/2 w-[400px] h-[200px] bg-[radial-gradient(ellipse,rgba(6,182,212,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 text-center relative z-10">
        <h2 class="text-3xl md:text-display-sm font-display text-text-primary mb-6 reveal leading-tight">
            Punya Ide, Masalah, atau<br>
            Proses Bisnis yang Ingin Didigitalisasi?
        </h2>
        <p class="text-text-muted text-lg lg:text-xl mb-12 max-w-2xl mx-auto reveal reveal-delay-1">
            Mari diskusikan bagaimana teknologi dapat membantu bisnis Anda bekerja lebih efektif.
        </p>
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="btn-gradient btn-lg magnetic reveal reveal-delay-2 text-lg px-10 py-4">
            Mulai Konsultasi
            <svg class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
