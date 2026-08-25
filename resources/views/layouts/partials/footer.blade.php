{{-- Premium Footer --}}
<footer class="relative border-t border-brand-border bg-brand-surface">
    {{-- Top gradient line --}}
    <div class="gradient-line w-full"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Main Footer --}}
        <div class="py-16 lg:py-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
            {{-- Brand Column --}}
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                </a>
                <p class="text-text-muted text-sm leading-relaxed mb-6 max-w-xs">
                    {{ \App\Models\SiteSetting::get('description', 'Premium Digital Technology Partner yang membantu bisnis membangun sistem digital sesuai kebutuhan.') }}
                </p>
                {{-- Social Links --}}
                <div class="flex items-center gap-2.5">
                    @foreach(\App\Models\SocialLink::active()->ordered()->get() as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer"
                       class="w-9 h-9 rounded-lg bg-brand-surface-3 border border-brand-border flex items-center justify-center text-text-muted hover:text-accent-light hover:border-accent/30 hover:bg-accent/5 transition-all duration-200">
                        @if(strtolower($social->platform) === 'linkedin')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        @elseif(strtolower($social->platform) === 'github')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                        @elseif(strtolower($social->platform) === 'instagram')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        @else
                        <span class="text-xs font-semibold">{{ strtoupper(substr($social->platform, 0, 2)) }}</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Services --}}
            <div class="lg:col-span-3">
                <h4 class="text-text-primary font-semibold text-sm mb-6 uppercase tracking-wider">Services</h4>
                <ul class="space-y-3">
                    @foreach(\App\Models\Service::published()->ordered()->take(6)->get() as $service)
                    <li>
                        <a href="{{ route('services') }}" class="text-text-muted text-sm hover:text-accent-light transition-colors duration-200">{{ $service->title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Quick Links --}}
            <div class="lg:col-span-2">
                <h4 class="text-text-primary font-semibold text-sm mb-6 uppercase tracking-wider">Company</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-text-muted text-sm hover:text-accent-light transition-colors duration-200">About</a></li>
                    <li><a href="{{ route('portfolio') }}" class="text-text-muted text-sm hover:text-accent-light transition-colors duration-200">Portfolio</a></li>
                    <li><a href="{{ route('blog') }}" class="text-text-muted text-sm hover:text-accent-light transition-colors duration-200">Insights</a></li>
                    <li><a href="{{ route('contact') }}" class="text-text-muted text-sm hover:text-accent-light transition-colors duration-200">Contact</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-3">
                <h4 class="text-text-primary font-semibold text-sm mb-6 uppercase tracking-wider">Contact</h4>
                <ul class="space-y-4">
                    @if($email = \App\Models\SiteSetting::get('email'))
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-accent mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $email }}" class="text-text-muted text-sm hover:text-accent-light transition-colors">{{ $email }}</a>
                    </li>
                    @endif
                    @if($phone = \App\Models\SiteSetting::get('phone'))
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-accent mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $phone }}" class="text-text-muted text-sm hover:text-accent-light transition-colors">{{ $phone }}</a>
                    </li>
                    @endif
                    @if($address = \App\Models\SiteSetting::get('address'))
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-accent mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-text-muted text-sm">{{ $address }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="py-6 border-t border-brand-border flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-text-dark text-sm">
                © {{ date('Y') }} {{ \App\Models\SiteSetting::get('copyright', config('app.name') . '. All rights reserved.') }}
            </p>
            <p class="text-text-dark text-xs font-medium tracking-wider uppercase">
                Premium Digital Technology Partner
            </p>
        </div>
    </div>
</footer>
