@php
    use App\Models\SiteSetting;
    use App\Models\SocialLink;
    use App\Models\Service;
    use App\Services\WhatsAppService;

    try { $footerSocials = SocialLink::active()->ordered()->get(); }
    catch (\Throwable $e) { $footerSocials = collect(); }

    try { $footerServices = Service::published()->ordered()->take(6)->get(); }
    catch (\Throwable $e) { $footerServices = collect(); }

    $footerEmail    = SiteSetting::get('email', 'aldeftech@gmail.com');
    $footerWhatsapp = SiteSetting::get('whatsapp_number', '+62 812-8968-609');
    $footerAddress  = SiteSetting::get('address', 'Rumah Chiara 2, Jl. Curug Induk, Bojong Kulur, Kec. Gunung Putri, Kab. Bogor');
    $footerMapsUrl  = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($footerAddress);
    $footerAbout    = SiteSetting::get('footer_description', SiteSetting::get('description', __('site.footer.about_default')));
@endphp

<footer class="surface-obsidian-deep relative overflow-hidden">
    <div class="bloom bloom-gold w-[34rem] h-[34rem] -top-64 left-1/2 -translate-x-1/2 opacity-30" aria-hidden="true"></div>

    <div class="shell relative z-10">

        {{-- Upper --}}
        <div class="pt-16 lg:pt-24 pb-12 lg:pb-16 grid grid-cols-2 lg:grid-cols-12 gap-x-8 gap-y-12">

            {{-- Brand --}}
            <div class="col-span-2 lg:col-span-4">
                <a href="{{ lroute('home') }}" class="inline-block group" aria-label="{{ config('app.name') }}">
                    <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}"
                         width="880" height="341" loading="lazy" decoding="async"
                         class="h-20 sm:h-24 lg:h-28 w-auto transition-transform duration-700 ease-[cubic-bezier(.22,1,.36,1)] group-hover:scale-[1.03]">
                </a>

                <p class="mt-6 text-sm leading-relaxed text-graphite-400 max-w-sm">
                    {{ $footerAbout }}
                </p>

                <p class="mt-5 font-serif-accent italic text-lg text-gold-300 leading-snug">
                    {{ __('site.footer.tagline') }}
                </p>

                @if($footerSocials->isNotEmpty())
                <div class="flex items-center gap-2.5 mt-7">
                    @foreach($footerSocials as $social)
                    <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer"
                       class="social-dot" title="{{ $social->platform }}" aria-label="{{ $social->platform }}">
                        @switch(strtolower($social->platform))
                            @case('linkedin')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                @break
                            @case('github')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                                @break
                            @case('instagram')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @break
                            @default
                                <span class="text-[0.65rem] font-semibold tracking-wide">{{ strtoupper(substr($social->platform, 0, 2)) }}</span>
                        @endswitch
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Services --}}
            <div class="lg:col-span-3 lg:pl-6">
                <h4 class="font-display text-[0.6875rem] font-semibold uppercase tracking-[0.18em] text-gold-400">{{ __('site.footer.services') }}</h4>
                <ul class="mt-5 space-y-3">
                    @forelse($footerServices as $service)
                        <li><a href="{{ lroute('services') }}#{{ $service->slug ?? '' }}" class="footer-link">{{ $service->title }}</a></li>
                    @empty
                        <li><a href="{{ lroute('services') }}" class="footer-link">{{ __('site.footer.all_services') }}</a></li>
                    @endforelse
                </ul>
            </div>

            {{-- Company --}}
            <div class="lg:col-span-2">
                <h4 class="font-display text-[0.6875rem] font-semibold uppercase tracking-[0.18em] text-gold-400">{{ __('site.footer.company') }}</h4>
                <ul class="mt-5 space-y-3">
                    <li><a href="{{ lroute('about') }}" class="footer-link">{{ __('site.footer.about_us') }}</a></li>
                    <li><a href="{{ lroute('solutions') }}" class="footer-link">{{ __('site.footer.industry_solutions') }}</a></li>
                    <li><a href="{{ lroute('portfolio') }}" class="footer-link">{{ __('site.nav.portfolio') }}</a></li>
                    <li><a href="{{ lroute('blog') }}" class="footer-link">{{ __('site.nav.blog') }}</a></li>
                    <li><a href="{{ lroute('faq') }}" class="footer-link">{{ __('site.nav.faq') }}</a></li>
                    <li><a href="{{ lroute('contact') }}" class="footer-link">{{ __('site.nav.contact') }}</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-span-2 lg:col-span-3">
                <h4 class="font-display text-[0.6875rem] font-semibold uppercase tracking-[0.18em] text-gold-400">{{ __('site.footer.contact') }}</h4>
                <ul class="mt-5 space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-gold-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $footerEmail }}" class="footer-link">{{ $footerEmail }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-gold-500 mt-1 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <a href="{{ WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="footer-link tabular">{{ $footerWhatsapp }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-gold-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <a href="{{ $footerMapsUrl }}" target="_blank" rel="noopener" class="footer-link leading-relaxed">
                            {{ $footerAddress }}
                            <span class="block text-[0.6875rem] text-gold-400 mt-1">{{ __('site.footer.view_on_maps') }}</span>
                        </a>
                    </li>
                </ul>

                <a href="{{ lroute('contact') }}" class="btn btn-ghost btn-sm mt-6">
                    <span>{{ __('site.cta.start_project') }}</span>
                    <svg class="btn-arrow w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        <hr class="rule-fade-dark">

        {{-- Lower --}}
        <div class="py-7 text-center">
            <p class="text-xs text-graphite-500">
                {{ __('site.footer.rights', ['year' => date('Y'), 'name' => config('app.name', 'Aldef Tech')]) }}
            </p>
        </div>
    </div>
</footer>
