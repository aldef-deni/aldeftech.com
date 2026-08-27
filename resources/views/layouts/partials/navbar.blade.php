@php
    $navLinks = [
        ['route' => 'home',      'label' => 'Beranda'],
        ['route' => 'services',  'label' => 'Layanan'],
        ['route' => 'solutions', 'label' => 'Solusi'],
        ['route' => 'portfolio', 'label' => 'Portofolio'],
        ['route' => 'about',     'label' => 'Tentang'],
        ['route' => 'blog',      'label' => 'Insight'],
        ['route' => 'faq',       'label' => 'FAQ'],
    ];
    $waUrl = \App\Services\WhatsAppService::getUrl();
@endphp

<nav id="navbar"
     x-data="{ open: false }"
     @keydown.escape.window="open = false"
     :class="open && 'is-open'"
     class="nav-shell nav-over-dark">

    <div class="shell">
        <div class="flex items-center justify-between h-[4.75rem] lg:h-[5.5rem]">

            {{-- Wordmark --}}
            <a href="{{ route('home') }}" class="shrink-0 group flex items-center" aria-label="{{ config('app.name') }} — Beranda">
                <img src="{{ asset('images/logo.png') }}"
                     alt="{{ config('app.name') }}"
                     width="220" height="80"
                     class="h-9 sm:h-10 lg:h-11 w-auto transition-transform duration-700 ease-[cubic-bezier(.22,1,.36,1)] group-hover:scale-[1.03]">
            </a>

            {{-- Desktop rail --}}
            <div class="nav-rail" role="navigation" aria-label="Navigasi utama">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="nav-link {{ request()->routeIs($link['route'].'*') ? 'nav-link-active' : '' }}"
                       @if(request()->routeIs($link['route'].'*')) aria-current="page" @endif>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('contact') }}"
                   class="hidden xl:inline-flex nav-link {{ request()->routeIs('contact*') ? 'nav-link-active' : '' }}">
                    Kontak
                </a>

                <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                   class="hidden sm:inline-flex btn btn-primary btn-sm magnetic" data-magnetic="0.12">
                    <span>Konsultasi</span>
                    <svg class="btn-arrow w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <button type="button"
                        @click="open = !open"
                        class="nav-burger lg:hidden"
                        :aria-expanded="open.toString()"
                        aria-controls="mobile-drawer"
                        aria-label="Buka menu">
                    <svg x-show="!open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-width="1.75" d="M4 7h16M4 12h16M4 17h10"/>
                    </svg>
                    <svg x-show="open" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="lg:hidden fixed inset-0 top-[4.75rem] bg-ink-950/50 backdrop-blur-sm"
         aria-hidden="true"></div>

    <div id="mobile-drawer"
         x-show="open" x-cloak
         x-transition:enter="transition ease-[cubic-bezier(.22,1,.36,1)] duration-500"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="lg:hidden absolute inset-x-0 top-full mx-3 mb-3 rounded-2xl border border-line bg-ivory-50/97 backdrop-blur-2xl shadow-[0_40px_80px_-32px_rgba(13,20,32,0.45)] overflow-hidden">

        <div class="max-h-[calc(100dvh-7rem)] overflow-y-auto no-scrollbar p-3">
            <div class="space-y-0.5">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}" @click="open = false"
                       class="nav-row {{ request()->routeIs($link['route'].'*') ? 'nav-row-active' : '' }}">
                        <span>{{ $link['label'] }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
                <a href="{{ route('contact') }}" @click="open = false"
                   class="nav-row {{ request()->routeIs('contact*') ? 'nav-row-active' : '' }}">
                    <span>Kontak</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <hr class="rule-fade my-3">

            <div class="px-1.5 pb-1.5 space-y-3">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-block">
                    <span>Konsultasi Proyek Gratis</span>
                    <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <p class="text-center text-xs text-graphite-400 leading-relaxed">
                    Respons rata-rata &lt; 2 jam pada hari kerja
                </p>
            </div>
        </div>
    </div>
</nav>
