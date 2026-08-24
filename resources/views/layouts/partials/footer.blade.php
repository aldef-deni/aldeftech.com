<footer class="bg-brand-surface border-t border-brand-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Main Footer --}}
        <div class="py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-accent flex items-center justify-center">
                        <span class="text-white font-bold text-sm">A</span>
                    </div>
                    <span class="font-semibold text-text-primary text-lg">Aldef Tech</span>
                </a>
                <p class="text-text-muted text-sm leading-relaxed mb-6">
                    Premium Digital Technology Partner yang membantu bisnis membangun sistem digital sesuai kebutuhan.
                </p>
                @php $socialLinks = \App\Models\SocialLink::active()->ordered()->get(); @endphp
                @if($socialLinks->count())
                <div class="flex gap-3">
                    @foreach($socialLinks as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-lg bg-brand-surface-2 border border-brand-border flex items-center justify-center text-text-muted hover:text-accent hover:border-accent/30 transition-all text-xs">
                        {{ strtoupper(substr($social->platform, 0, 1)) }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Services --}}
            <div>
                <h3 class="font-semibold text-text-primary text-sm mb-4">Services</h3>
                <ul class="space-y-2.5">
                    @foreach(['Custom Software', 'Web Application', 'SaaS Development', 'AI Development', 'Business Automation', 'IT Consulting'] as $service)
                    <li>
                        <a href="{{ route('services') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors">{{ $service }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h3 class="font-semibold text-text-primary text-sm mb-4">Company</h3>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('about') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors">About</a></li>
                    <li><a href="{{ route('portfolio') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors">Portfolio</a></li>
                    <li><a href="{{ route('blog') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors">Insights</a></li>
                    <li><a href="{{ route('contact') }}" class="text-sm text-text-muted hover:text-text-primary transition-colors">Contact</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="font-semibold text-text-primary text-sm mb-4">Contact</h3>
                <ul class="space-y-2.5">
                    @if($email = \App\Models\SiteSetting::get('email'))
                    <li class="flex items-center gap-2 text-sm text-text-muted">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $email }}" class="hover:text-text-primary transition-colors">{{ $email }}</a>
                    </li>
                    @endif
                    @if($whatsapp = \App\Models\SiteSetting::get('whatsapp_number'))
                    <li class="flex items-center gap-2 text-sm text-text-muted">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="hover:text-text-primary transition-colors">{{ $whatsapp }}</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="py-6 border-t border-brand-border flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-text-dark">
                © {{ date('Y') }} {{ config('aldeftech.name', 'Aldef Tech') }}. All rights reserved.
            </p>
            <div class="flex gap-6">
                <a href="#" class="text-xs text-text-dark hover:text-text-muted transition-colors">Privacy Policy</a>
                <a href="#" class="text-xs text-text-dark hover:text-text-muted transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
