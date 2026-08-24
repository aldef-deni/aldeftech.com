<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Admin' }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .admin-scroll::-webkit-scrollbar { width: 6px; }
        .admin-scroll::-webkit-scrollbar-track { background: transparent; }
        .admin-scroll::-webkit-scrollbar-thumb { background: #2A2D35; border-radius: 3px; }
    </style>
    @stack('styles')
</head>
<body class="bg-brand-bg text-text-primary font-sans antialiased">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: @js(!request()->is('admin/login')) }">

        {{-- Sidebar --}}
        @if(request()->is('admin/login'))
            @yield('content')
        @else
        <aside class="admin-sidebar fixed inset-y-0 left-0 z-40 w-64 transform transition-transform duration-300 lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               x-show="true">
            <div class="flex flex-col h-full">
                {{-- Logo --}}
                <div class="flex items-center gap-3 px-6 py-5 border-b border-brand-border">
                    <div class="w-8 h-8 rounded-lg bg-accent flex items-center justify-center">
                        <span class="text-white font-bold text-sm">A</span>
                    </div>
                    <div>
                        <div class="font-semibold text-sm text-text-primary">Aldef Tech</div>
                        <div class="text-xs text-text-muted">Admin Panel</div>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 overflow-y-auto admin-scroll py-4 px-3">
                    <div class="space-y-1">
                        {{-- Dashboard --}}
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>

                        {{-- CONTENT --}}
                        <div class="pt-4 pb-2 px-3">
                            <span class="text-xs font-semibold text-text-dark uppercase tracking-wider">Content</span>
                        </div>
                        @php
                        $contentLinks = [
                            ['route' => 'admin.homepage.index', 'label' => 'Homepage', 'icon' => 'home'],
                            ['route' => 'admin.about.edit', 'label' => 'About', 'icon' => 'info'],
                            ['route' => 'admin.services.index', 'label' => 'Services', 'icon' => 'cog'],
                            ['route' => 'admin.solutions.index', 'label' => 'Solutions', 'icon' => 'lightbulb'],
                            ['route' => 'admin.portfolio.index', 'label' => 'Portfolio', 'icon' => 'briefcase'],
                            ['route' => 'admin.testimonials.index', 'label' => 'Testimonials', 'icon' => 'star'],
                            ['route' => 'admin.faq.index', 'label' => 'FAQ', 'icon' => 'question'],
                            ['route' => 'admin.blog.index', 'label' => 'Blog', 'icon' => 'pencil'],
                            ['route' => 'admin.categories.index', 'label' => 'Categories', 'icon' => 'tag'],
                            ['route' => 'admin.tags.index', 'label' => 'Tags', 'icon' => 'bookmark'],
                        ];
                        @endphp
                        @foreach($contentLinks as $link)
                        <a href="{{ route($link['route']) }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs($link['route'].'*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            {{ $link['label'] }}
                        </a>
                        @endforeach

                        {{-- LEADS --}}
                        <div class="pt-4 pb-2 px-3">
                            <span class="text-xs font-semibold text-text-dark uppercase tracking-wider">Leads</span>
                        </div>
                        <a href="{{ route('admin.leads.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.leads*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Leads
                        </a>

                        {{-- COMPANY --}}
                        <div class="pt-4 pb-2 px-3">
                            <span class="text-xs font-semibold text-text-dark uppercase tracking-wider">Company</span>
                        </div>
                        <a href="{{ route('admin.ceo.edit') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.ceo.*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            CEO Profile
                        </a>
                        <a href="{{ route('admin.process-steps.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.process-steps*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Process Steps
                        </a>
                        <a href="{{ route('admin.social-media.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.social-media*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Social Media
                        </a>

                        {{-- SETTINGS --}}
                        <div class="pt-4 pb-2 px-3">
                            <span class="text-xs font-semibold text-text-dark uppercase tracking-wider">Settings</span>
                        </div>
                        <a href="{{ route('admin.settings.site') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.settings.site') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Site Settings
                        </a>
                        <a href="{{ route('admin.settings.seo') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.settings.seo') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            SEO Settings
                        </a>
                        <a href="{{ route('admin.settings.whatsapp') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.settings.whatsapp') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            WhatsApp
                        </a>
                        <a href="{{ route('admin.settings.analytics') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.settings.analytics') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Analytics
                        </a>
                        <a href="{{ route('admin.media.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.media*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Media
                        </a>

                        {{-- SYSTEM --}}
                        <div class="pt-4 pb-2 px-3">
                            <span class="text-xs font-semibold text-text-dark uppercase tracking-wider">System</span>
                        </div>
                        @if(auth()->user()->hasRole('super-admin'))
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.users*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Users
                        </a>
                        @endif
                        <a href="{{ route('admin.activity-logs.index') }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.activity-logs*') ? 'bg-accent/10 text-accent' : 'text-text-muted hover:text-text-primary hover:bg-brand-surface-2' }}">
                            <span class="w-5 h-5 shrink-0 text-center text-xs opacity-60">●</span>
                            Activity Logs
                        </a>
                    </div>
                </nav>

                {{-- User Info --}}
                <div class="border-t border-brand-border px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center text-accent text-sm font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-text-primary truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-text-muted truncate">{{ ucfirst(str_replace('-', ' ', auth()->user()->roles->first()->name ?? 'user')) }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-text-muted hover:text-danger transition-colors" title="Logout">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-brand-bg/80 backdrop-blur-lg border-b border-brand-border">
                <div class="flex items-center justify-between px-4 lg:px-6 py-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-text-muted hover:text-text-primary">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex-1 lg:flex-none">
                        <h1 class="text-lg font-semibold">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" target="_blank" class="text-text-muted hover:text-accent text-sm flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Site
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-4 lg:p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-4 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        @endif
    </div>

    @stack('scripts')
</body>
</html>
