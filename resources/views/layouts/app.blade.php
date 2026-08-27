<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    <title>{{ $pageTitle ?? $metaTitle ?? config('aldeftech.seo.default_title') }}</title>
    <meta name="description" content="{{ $metaDescription ?? config('aldeftech.seo.default_description') }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $pageTitle ?? $metaTitle ?? config('aldeftech.seo.default_title') }}">
    <meta property="og:description" content="{{ $metaDescription ?? config('aldeftech.seo.default_description') }}">
    <meta property="og:image" content="{{ $ogImage ?? asset(config('aldeftech.seo.default_image')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle ?? $metaTitle ?? config('aldeftech.seo.default_title') }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? config('aldeftech.seo.default_description') }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset(config('aldeftech.seo.default_image')) }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo-square.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-square.png') }}">

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
    {!! json_encode([
        '@' . 'context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => 'Aldef Tech',
        'alternateName' => 'Aldef Technology Studio',
        'description' => 'Aldef Tech is a premium software engineering and AI technology company building custom applications, software systems, SaaS platforms, AI solutions, and business automation.',
        'url' => config('app.url'),
        'logo' => asset('images/logo.png'),
        'image' => asset('images/logo.png'),
        'email' => \App\Models\SiteSetting::get('email', 'info@aldeftech.com'),
        'telephone' => '+' . preg_replace('/\D/', '', \App\Models\SiteSetting::get('whatsapp_number', '628128968609')),
        'address' => [
            '@type' => 'PostalAddress',
            'addressCountry' => 'ID',
            'addressLocality' => \App\Models\SiteSetting::get('address', 'Indonesia')
        ],
        'priceRange' => '$$$',
        'sameAs' => array_values(array_filter([
            \App\Models\SiteSetting::get('linkedin'),
            \App\Models\SiteSetting::get('github'),
            \App\Models\SiteSetting::get('instagram')
        ]))
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('schema')

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Google Tag Manager --}}
    @if($gtmId = \App\Models\SiteSetting::get('google_tag_manager_id'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    {{-- Google Analytics --}}
    @if($gaId = \App\Models\SiteSetting::get('google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
    @endif
</head>
<body class="surface-ivory font-sans antialiased" data-ambient-glow>
    {{-- GTM Noscript --}}
    @if($gtmId = \App\Models\SiteSetting::get('google_tag_manager_id'))
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
