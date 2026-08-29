@php
    $navLinks = [
        ['route' => 'home',      'label' => __('site.nav.home')],
        ['route' => 'services',  'label' => __('site.nav.services')],
        ['route' => 'solutions', 'label' => __('site.nav.solutions')],
        ['route' => 'portfolio', 'label' => __('site.nav.portfolio')],
        ['route' => 'about',     'label' => __('site.nav.about')],
        ['route' => 'blog',      'label' => __('site.nav.blog')],
        ['route' => 'faq',       'label' => __('site.nav.faq')],
        ['route' => 'contact',   'label' => __('site.nav.contact')],
    ];
    $waUrl = \App\Services\WhatsAppService::getUrl();
@endphp

<nav id="navbar"
     x-data="{ open: false }"
     @keydown.escape.window="open = false"
     :class="open && 'is-open'"
     class="nav-shell nav-over-dark">

    <div class="shell">
        <div class="flex items-center justify-between h-[5.5rem] lg:h-[6.5rem]">

            {{-- Wordmark --}}
            <a href="{{ lroute('home') }}" class="shrink-0 group flex items-center"
               aria-label="{{ __('site.nav.to_home', ['name' => config('app.name')]) }}">
                @php $logo = site_logo(); @endphp
                <img src="{{ $logo['url'] }}"
                     alt="{{ config('app.name') }}"
                     @if($logo['width']) width="{{ $logo['width'] }}" height="{{ $logo['height'] }}" @endif
                     fetchpriority="high"
                     class="h-12 sm:h-14 lg:h-16 w-auto transition-transform duration-700 ease-[cubic-bezier(.22,1,.36,1)] group-hover:scale-[1.03]">
            </a>

            {{-- Desktop rail --}}
            <div class="nav-rail" role="navigation" aria-label="{{ __('site.nav.menu') }}">
                @foreach($navLinks as $link)
                    <a href="{{ lroute($link['route']) }}"
                       class="nav-link {{ is_current_page($link['route']) ? 'nav-link-active' : '' }}"
                       @if(is_current_page($link['route'])) aria-current="page" @endif>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="hidden sm:block">
                    <x-language-switcher />
                </div>

                <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                   class="hidden xl:inline-flex btn btn-primary btn-sm magnetic" data-magnetic="0.12">
                    <span>{{ __('site.cta.consult') }}</span>
                    <svg class="btn-arrow w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                <button type="button"
                        @click="open = !open"
                        class="nav-burger xl:hidden"
                        :aria-expanded="open.toString()"
                        aria-controls="mobile-drawer"
                        aria-label="{{ __('site.nav.open_menu') }}">
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
         class="xl:hidden fixed inset-0 top-[5.5rem] bg-ink-950/50 backdrop-blur-sm"
         aria-hidden="true"></div>

    <div id="mobile-drawer"
         x-show="open" x-cloak
         x-transition:enter="transition ease-[cubic-bezier(.22,1,.36,1)] duration-500"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="xl:hidden absolute inset-x-0 top-full mx-3 mb-3 rounded-2xl border border-line bg-ivory-50/97 backdrop-blur-2xl shadow-[0_40px_80px_-32px_rgba(13,20,32,0.45)] overflow-hidden">

        <div class="max-h-[calc(100dvh-8rem)] overflow-y-auto no-scrollbar p-3">
            <div class="space-y-0.5">
                @foreach($navLinks as $link)
                    <a href="{{ lroute($link['route']) }}" @click="open = false"
                       class="nav-row {{ is_current_page($link['route']) ? 'nav-row-active' : '' }}">
                        <span>{{ $link['label'] }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>

            <hr class="rule-fade my-3">

            <div class="px-1.5 pb-1.5 space-y-4">
                <x-language-switcher variant="mobile" />

                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-block">
                    <span>{{ __('site.cta.consult_free') }}</span>
                    <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <p class="text-center text-xs text-graphite-400 leading-relaxed">
                    {{ __('site.cta.response_short') }}
                </p>
            </div>
        </div>
    </div>
</nav>
