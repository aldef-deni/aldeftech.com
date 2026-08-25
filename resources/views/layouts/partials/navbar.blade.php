{{-- Premium Light Navbar --}}
<nav id="navbar" x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-200/80 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="flex items-center justify-between h-20 lg:h-22">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group py-2">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}"
                     class="h-11 sm:h-12 lg:h-14 w-auto transition-transform duration-300 group-hover:scale-[1.02]">
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-1 bg-slate-100/70 border border-slate-200/80 p-1.5 rounded-full shadow-2xs">
                @php
                $navLinks = [
                    ['route' => 'home', 'label' => 'Home'],
                    ['route' => 'services', 'label' => 'Services'],
                    ['route' => 'solutions', 'label' => 'Solutions'],
                    ['route' => 'portfolio', 'label' => 'Portfolio'],
                    ['route' => 'about', 'label' => 'About'],
                    ['route' => 'faq', 'label' => 'FAQ'],
                    ['route' => 'contact', 'label' => 'Contact'],
                ];
                @endphp
                @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="text-[0.8125rem] font-semibold px-4 py-1.5 rounded-full transition-all duration-200 {{ request()->routeIs($link['route'].'*') ? 'text-blue-600 bg-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    {{ $link['label'] }}
                </a>
                @endforeach
            </div>

            {{-- CTA + Mobile Menu --}}
            <div class="flex items-center gap-3">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                   class="hidden sm:inline-flex btn-primary btn-sm shadow-sm font-semibold">
                    <span>Let's Talk</span>
                    <svg class="w-3.5 h-3.5 ml-0.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>

                {{-- Mobile Hamburger Button --}}
                <button type="button"
                        id="mobile-menu-toggle"
                        @click.stop="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden text-slate-700 hover:text-slate-900 p-2.5 -mr-2 rounded-xl transition-colors hover:bg-slate-100/80 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                        aria-label="Toggle Navigation">
                    <svg x-show="!mobileMenuOpen" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Drawer --}}
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.outside="mobileMenuOpen = false"
         class="lg:hidden bg-white border-t border-slate-200 shadow-2xl">
        <div class="px-5 py-5 space-y-1.5">
            @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               @click="mobileMenuOpen = false"
               class="block px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs($link['route'].'*') ? 'text-blue-600 bg-blue-50/90 font-bold' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50' }}">
                {{ $link['label'] }}
            </a>
            @endforeach
            <div class="pt-3 mt-2 border-t border-slate-100">
                <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                   class="block text-center btn-primary w-full py-3.5 shadow-md text-sm font-bold">
                    Start a Project →
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="h-20 lg:h-22"></div>
