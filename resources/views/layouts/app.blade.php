<!DOCTYPE html>
<html lang="{{ config('locales.available.'.app()->getLocale().'.html', str_replace('_', '-', app()->getLocale())) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta
         Resolution order: what an editor set in Pengaturan > SEO Halaman, then
         whatever this page set for itself, then the site-wide default. A blank
         override falls through rather than blanking the tag. --}}
    @php
        $seoOverride = \App\Models\PageSeo::forCurrentRoute();

        $resolvedTitle = filled($seoOverride?->meta_title)
            ? $seoOverride->meta_title
            : ($pageTitle ?? $metaTitle ?? config('aldeftech.seo.default_title'));

        $resolvedDescription = filled($seoOverride?->meta_description)
            ? $seoOverride->meta_description
            : ($metaDescription ?? config('aldeftech.seo.default_description'));

        $resolvedImage = filled($seoOverride?->og_image)
            ? media_url($seoOverride->og_image)
            : ($ogImage ?? asset(config('aldeftech.seo.default_image')));

        $canonicalUrl = $canonical ?? url()->current();
        $gtmId = \App\Models\SiteSetting::get('google_tag_manager_id')
            ?: config('aldeftech.analytics.google_tag_manager_id', '');
        $gaId = \App\Models\SiteSetting::get('google_analytics_id')
            ?: config('aldeftech.analytics.google_analytics_id', '');
        // GTM owns the page view and events whenever it is configured. This
        // prevents a GA4 tag in GTM plus standalone gtag from double counting.
        $analyticsMode = filled($gtmId) ? 'gtm' : (filled($gaId) ? 'gtag' : 'none');
        $analyticsConfig = [
            'mode' => $analyticsMode,
            'debug' => app()->environment(['local', 'testing']),
        ];
    @endphp

    <title>{{ $resolvedTitle }}</title>
    <meta name="description" content="{{ $resolvedDescription }}">
    @if(($noindex ?? false) || $seoOverride?->noindex)
        <meta name="robots" content="noindex, nofollow">
    @endif
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Language alternates. Without these Google treats /services and
         /en/services as unrelated pages competing with each other. --}}
    @foreach(locale_alternates() as $code => $href)
        <link rel="alternate" hreflang="{{ config('locales.available.'.$code.'.html', $code) }}" href="{{ $href }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ locale_url(config('locales.default', 'id')) }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $resolvedTitle }}">
    <meta property="og:description" content="{{ $resolvedDescription }}">
    <meta property="og:image" content="{{ $resolvedImage }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="{{ config('locales.available.'.app()->getLocale().'.og', 'id_ID') }}">
    @foreach(locale_alternates() as $code => $href)
        @if($code !== app()->getLocale())
    <meta property="og:locale:alternate" content="{{ config('locales.available.'.$code.'.og') }}">
        @endif
    @endforeach

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $resolvedTitle }}">
    <meta name="twitter:description" content="{{ $resolvedDescription }}">
    <meta name="twitter:image" content="{{ $resolvedImage }}">

    {{-- Favicon
         An upload from Pengaturan > Situs wins. Otherwise the bundled set is
         kept: it carries proper 16/32/180px crops, which one uploaded file
         cannot, and logo-square.png at 1.3 MB is far too heavy to serve here. --}}
    @if($customFavicon = site_favicon())
        <link rel="icon" href="{{ $customFavicon }}">
        <link rel="apple-touch-icon" href="{{ $customFavicon }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    @endif

    {{-- Google Search Console --}}
    @if($googleVerification = \App\Models\SiteSetting::get('google_search_console_verification'))
        <meta name="google-site-verification" content="{{ $googleVerification }}">
    @endif

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Schema.org (JSON-LD) --}}
    <script type="application/ld+json">
    {!! json_encode(array_filter([
        '@' . 'context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => rtrim(config('app.url'), '/') . '#organization',
        'name' => 'Aldef Tech',
        'alternateName' => 'Aldef Technology Studio',
        'description' => 'Aldef Tech is a premium software engineering and AI technology company building custom applications, software systems, SaaS platforms, AI solutions, and business automation.',
        'url' => config('app.url'),
        'logo' => asset('images/logo.png'),
        'image' => asset('images/logo.png'),
        'email' => \App\Models\SiteSetting::get('email', 'info@aldeftech.com'),
        'telephone' => '+' . preg_replace('/\D/', '', \App\Models\SiteSetting::get('whatsapp_number', '628128968609')),
        // Filled from Pengaturan > Situs. array_filter drops anything still
        // blank, so an unset postcode never ships as an empty schema field.
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => \App\Models\SiteSetting::get('address_street'),
            'addressLocality' => \App\Models\SiteSetting::get('address_locality'),
            'addressRegion' => \App\Models\SiteSetting::get('address_region'),
            'postalCode' => \App\Models\SiteSetting::get('postal_code'),
            'addressCountry' => 'ID',
        ]),
        // Should mirror the service areas set on the Google Business Profile.
        'areaServed' => collect(explode(',', (string) \App\Models\SiteSetting::get('service_areas')))
            ->map(fn ($a) => trim($a))->filter()->values()->all(),
        // Reads the Media Sosial screen. It used to read site settings keys that
        // nothing ever wrote, so sameAs shipped empty no matter what was saved.
        'sameAs' => \Illuminate\Support\Facades\Cache::remember('schema.same_as', 3600, fn () => \App\Models\SocialLink::query()
            ->where('is_active', true)->orderBy('sort_order')
            ->pluck('url')->filter()->values()->all())
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode([
        '@' . 'context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => rtrim(config('app.url'), '/') . '#website',
        'name' => 'Aldef Tech',
        'url' => rtrim(config('app.url'), '/') . '/',
        'publisher' => ['@id' => rtrim(config('app.url'), '/') . '#organization'],
        'inLanguage' => [config('locales.available.id.html', 'id'), config('locales.available.en.html', 'en')],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('schema')

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Analytics bootstrap. Only one vendor configuration is emitted. --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        window.__aldefAnalytics = {!! json_encode($analyticsConfig, JSON_UNESCAPED_SLASHES) !!};
    </script>

    {{-- Google Tag Manager --}}
    @if($analyticsMode === 'gtm')
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    {{-- Standalone Google Analytics --}}
    @if($analyticsMode === 'gtag')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
    @endif

    @if(!empty($analyticsLeadConversion))
    <script>window.__aldefLeadConversion = @json($analyticsLeadConversion);</script>
    @endif
</head>
<body class="surface-ivory font-sans antialiased" data-ambient-glow
      data-analytics-page-type="{{ $analyticsPageType ?? '' }}"
      data-analytics-item="{{ $analyticsItem ?? '' }}">
    {{-- GTM Noscript --}}
    @if($analyticsMode === 'gtm')
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- Main Content --}}
    <main class="min-h-screen">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    {{-- Floating WhatsApp Widget --}}
    @include('layouts.partials.whatsapp-button')

    {{-- Flash Toast Notification --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="toast toast-success fixed top-24 right-5 sm:right-8 z-[100] max-w-sm">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="toast toast-error fixed top-24 right-5 sm:right-8 z-[100] max-w-sm">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif




    @stack('scripts')
</body>
</html>
