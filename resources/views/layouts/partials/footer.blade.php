<footer class="bg-brand-surface border-t border-brand-border">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        {{-- Main Footer --}}
        <div class="py-16 lg:py-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
            {{-- Brand Column --}}
            <div class="lg:col-span-5">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-5 group">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent to-accent-dark flex items-center justify-center shadow-glow transition-shadow duration-300 group-hover:shadow-glow-lg">
                        <span class="text-white font-bold text-sm font-display">A</span>
                    </div>
                    <span class="font-semibold text-text-primary text-base tracking-tight">Aldef Tech</span>
                </a>
                <p class="text-text-muted text-sm leading-relaxed mb-6 max-w-sm">
                    Premium Digital Technology Partner yang membantu bisnis membangun sistem digital sesuai kebutuhan.
                </p>
                @php $socialLinks = \App\Models\SocialLink::active()->ordered()->get(); @endphp
                @if($socialLinks->count())
                <div class="flex gap-2">
                    @foreach($socialLinks as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-lg bg-brand-surface-2 border border-brand-border flex items-center justify-center text-text-muted hover:text-accent hover:border-accent/30 hover:bg-accent/5 transition-all duration-200 text-[0.65rem] font-semibold uppercase tracking-wider">
                        {{ strtoupper(substr($social->platform, 0, 2)) }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Services --}}
            <div class="lg:col-span-3">
                <h3 class="font-display font-semibold text-text-primary text-[0.8125rem] mb-5 tracking-tight">Services</h3>
                <ul class="space-y-3">
                    @foreach(['Custom Software', 'Web Application', 'SaaS Development', 'AI Development', 'Business Automation', 'IT Consulting'] as $service)
                    <li>
                        <a href="{{ route('services') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors duration-200">{{ $service }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Company --}}
            <div class="lg:col-span-2">
                <h3 class="font-display font-semibold text-text-primary text-[0.8125rem] mb-5 tracking-tight">Company</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors duration-200">About</a></li>
                    <li><a href="{{ route('portfolio') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors duration-200">Portfolio</a></li>
                    <li><a href="{{ route('blog') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors duration-200">Insights</a></li>
                    <li><a href="{{ route('contact') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors duration-200">Contact</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-2">
                <h3 class="font-display font-semibold text-text-primary text-[0.8125rem] mb-5 tracking-tight">Contact</h3>
                <ul class="space-y-3">
                    @if($email = \App\Models\SiteSetting::get('email'))
                    <li>
                        <a href="mailto:{{ $email }}" class="text-sm text-text-muted hover:text-text-primary transition-colors duration-200 inline-flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $email }}
                        </a>
                    </li>
                    @endif
                    @if($whatsapp = \App\Models\SiteSetting::get('whatsapp_number'))
                    <li>
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="text-sm text-text-muted hover:text-text-primary transition-colors duration-200 inline-flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            WhatsApp
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="py-6 border-t border-brand-border flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-text-dark">
                © {{ date('Y') }} {{ config('aldeftech.name', 'Aldef Tech') }}. All rights reserved.
            </p>
            <div class="flex gap-6">
                <a href="#" class="text-xs text-text-dark hover:text-text-muted transition-colors duration-200">Privacy Policy</a>
                <a href="#" class="text-xs text-text-dark hover:text-text-muted transition-colors duration-200">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
