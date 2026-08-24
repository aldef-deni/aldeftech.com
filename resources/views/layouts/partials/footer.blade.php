{{-- Footer --}}
<footer class="bg-brand-surface border-t border-brand-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Main Footer --}}
        <div class="py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                    <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">A</span>
                    </div>
                    <span class="text-text-primary font-bold text-xl tracking-tight">ALDEF<span class="text-accent">TECH</span></span>
                </a>
                <p class="text-text-muted text-sm leading-relaxed mb-6">
                    {{ \App\Models\SiteSetting::get('description', 'Premium Digital Technology Partner yang membantu bisnis membangun sistem digital sesuai kebutuhan.') }}
                </p>
                {{-- Social Links --}}
                <div class="flex items-center gap-3">
                    @foreach(\App\Models\SocialLink::active()->ordered()->get() as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 bg-brand-surface-2 border border-brand-border rounded-lg flex items-center justify-center text-text-muted hover:text-accent hover:border-accent transition-all">
                        <span class="text-sm">{{ strtoupper(substr($social->platform, 0, 2)) }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-text-primary font-semibold mb-6">Services</h4>
                <ul class="space-y-3">
                    @foreach(\App\Models\Service::published()->ordered()->take(6)->get() as $service)
                    <li>
                        <a href="{{ route('services') }}" class="text-text-muted text-sm hover:text-accent transition-colors">{{ $service->title }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-text-primary font-semibold mb-6">Quick Links</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-text-muted text-sm hover:text-accent transition-colors">About</a></li>
                    <li><a href="{{ route('portfolio') }}" class="text-text-muted text-sm hover:text-accent transition-colors">Portfolio</a></li>
                    <li><a href="{{ route('blog') }}" class="text-text-muted text-sm hover:text-accent transition-colors">Insights</a></li>
                    <li><a href="{{ route('contact') }}" class="text-text-muted text-sm hover:text-accent transition-colors">Contact</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-text-primary font-semibold mb-6">Contact</h4>
                <ul class="space-y-4">
                    @if($email = \App\Models\SiteSetting::get('email'))
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-accent mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $email }}" class="text-text-muted text-sm hover:text-accent transition-colors">{{ $email }}</a>
                    </li>
                    @endif
                    @if($phone = \App\Models\SiteSetting::get('phone'))
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-accent mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $phone }}" class="text-text-muted text-sm hover:text-accent transition-colors">{{ $phone }}</a>
                    </li>
                    @endif
                    @if($address = \App\Models\SiteSetting::get('address'))
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-accent mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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
            <p class="text-text-dark text-xs">
                Premium Digital Technology Partner
            </p>
        </div>
    </div>
</footer>
