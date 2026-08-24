<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 navbar-blur transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-accent flex items-center justify-center">
                    <span class="text-white font-bold text-sm">A</span>
                </div>
                <span class="font-semibold text-text-primary text-lg tracking-tight">Aldef Tech</span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-8">
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
                   class="text-sm font-medium transition-colors {{ request()->routeIs($link['route'].'*') ? 'text-accent' : 'text-text-muted hover:text-text-primary' }}">
                    {{ $link['label'] }}
                </a>
                @endforeach
            </div>

            {{-- CTA + Mobile Menu --}}
            <div class="flex items-center gap-4">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                   class="hidden sm:inline-flex btn-primary text-sm">
                    Start a Project
                    <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>

                {{-- Mobile Hamburger --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-text-muted hover:text-text-primary p-1">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
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
         class="lg:hidden bg-brand-surface border-t border-brand-border">
        <div class="px-4 py-4 space-y-1">
            @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="block px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($link['route'].'*') ? 'text-accent bg-accent/5' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                {{ $link['label'] }}
            </a>
            @endforeach
            <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
               class="block px-3 py-2.5 rounded-lg text-sm font-medium text-accent mt-2">
                Start a Project →
            </a>
        </div>
    </div>
</nav>
<div class="h-16 lg:h-20"></div>
