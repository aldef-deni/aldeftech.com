<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 navbar-blur">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="flex items-center justify-between h-16 lg:h-[4.5rem]">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent to-accent-dark flex items-center justify-center shadow-glow transition-shadow duration-300 group-hover:shadow-glow-lg">
                    <span class="text-white font-bold text-sm font-display">A</span>
                </div>
                <span class="font-semibold text-text-primary text-base tracking-tight hidden sm:inline">Aldef Tech</span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-1">
                @php
                $navLinks = [
                    ['route' => 'home', 'label' => 'Home'],
                    ['route' => 'services', 'label' => 'Services'],
                    ['route' => 'solutions', 'label' => 'Solutions'],
                    ['route' => 'portfolio', 'label' => 'Portfolio'],
                    ['route' => 'about', 'label' => 'About'],
                    ['route' => 'blog', 'label' => 'Insights'],
                    ['route' => 'contact', 'label' => 'Contact'],
                ];
                @endphp
                @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="text-[0.8125rem] font-medium px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs($link['route'].'*') ? 'text-text-primary bg-brand-surface-2' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface/50' }}">
                    {{ $link['label'] }}
                </a>
                @endforeach
            </div>

            {{-- CTA + Mobile Menu --}}
            <div class="flex items-center gap-3">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                   class="hidden sm:inline-flex btn-primary btn-sm magnetic">
                    Start a Project
                    <svg class="w-3.5 h-3.5 ml-0.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>

                {{-- Mobile Hamburger --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-text-muted hover:text-text-primary p-2 -mr-2 rounded-lg transition-colors hover:bg-brand-surface-2">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-brand-surface/95 backdrop-blur-xl border-t border-brand-border">
        <div class="px-5 py-5 space-y-1">
            @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="block px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs($link['route'].'*') ? 'text-text-primary bg-brand-surface-2 border border-brand-border' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2/50' }}">
                {{ $link['label'] }}
            </a>
            @endforeach
            <div class="pt-3 mt-2 border-t border-brand-border">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                   class="block text-center btn-primary w-full py-3 mt-2">
                    Start a Project →
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="h-16 lg:h-[4.5rem]"></div>
