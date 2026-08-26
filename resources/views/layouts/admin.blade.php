<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Admin' }} — {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-square.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: true, sidebarCollapsed: false }">

        {{-- Sidebar --}}
        @if(request()->is('admin/login'))
            @yield('content')
        @else
        <aside class="admin-sidebar fixed inset-y-0 left-0 z-40 transform transition-all duration-300 lg:translate-x-0 overflow-hidden shadow-xl"
               :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', sidebarCollapsed ? 'w-[4.5rem]' : 'w-[16.5rem]']"
               x-show="true">
            <div class="flex flex-col h-full bg-white border-r border-slate-200">
                {{-- Logo --}}
                <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-100 shrink-0">
                    <img src="{{ asset('images/logo-square.png') }}" alt="Aldef Tech" class="w-9 h-9 rounded-lg object-contain shrink-0">
                    <div x-show="!sidebarCollapsed" x-transition class="overflow-hidden">
                        <div class="font-display font-bold text-sm text-slate-900 tracking-tight whitespace-nowrap">Aldef Tech</div>
                        <div class="text-[0.65rem] text-blue-600 font-bold uppercase tracking-wider whitespace-nowrap">Admin Control</div>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1" style="scrollbar-width: thin; scrollbar-color: #CBD5E1 transparent;">
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 12a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/></svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">Dashboard</span>
                    </a>

                    {{-- CONTENT Section --}}
                    <div class="pt-5 pb-2 px-2">
                        <span class="text-[0.6rem] font-bold text-slate-500 uppercase tracking-[0.2em]" x-show="!sidebarCollapsed" x-transition>Content Management</span>
                        <div class="h-px bg-slate-200 mt-2" x-show="sidebarCollapsed"></div>
                    </div>

                    @php
                    $contentLinks = [
                        ['route' => 'admin.homepage.index', 'label' => 'Homepage', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                        ['route' => 'admin.about.edit', 'label' => 'About', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                        ['route' => 'admin.services.index', 'label' => 'Services', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                        ['route' => 'admin.solutions.index', 'label' => 'Solutions', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>'],
                        ['route' => 'admin.portfolio.index', 'label' => 'Portfolio', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                        ['route' => 'admin.testimonials.index', 'label' => 'Testimonials', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
                        ['route' => 'admin.faq.index', 'label' => 'FAQ', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                        ['route' => 'admin.blog.index', 'label' => 'Blog', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
                        ['route' => 'admin.marketing.index', 'label' => 'AI Marketing', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v3m0 12v3m9-9h-3M6 12H3m14.95-6.95l-2.12 2.12M8.17 15.83l-2.12 2.12m11.9 0l-2.12-2.12M8.17 8.17L6.05 6.05M12 8a4 4 0 100 8 4 4 0 000-8z"/>'],
                        ['route' => 'admin.categories.index', 'label' => 'Categories', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>'],
                        ['route' => 'admin.tags.index', 'label' => 'Tags', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>'],
                    ];
                    @endphp

                    @foreach($contentLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs($link['route'].'*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $link['icon'] !!}</svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">{{ $link['label'] }}</span>
                    </a>
                    @endforeach

                    {{-- LEADS --}}
                    <div class="pt-5 pb-2 px-2">
                        <span class="text-[0.6rem] font-bold text-slate-500 uppercase tracking-[0.2em]" x-show="!sidebarCollapsed" x-transition>CRM & Leads</span>
                        <div class="h-px bg-slate-200 mt-2" x-show="sidebarCollapsed"></div>
                    </div>
                    <a href="{{ route('admin.leads.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.leads*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">Leads</span>
                    </a>

                    {{-- COMPANY --}}
                    <div class="pt-5 pb-2 px-2">
                        <span class="text-[0.6rem] font-bold text-slate-500 uppercase tracking-[0.2em]" x-show="!sidebarCollapsed" x-transition>Company</span>
                        <div class="h-px bg-slate-200 mt-2" x-show="sidebarCollapsed"></div>
                    </div>
                    <a href="{{ route('admin.ceo.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.ceo.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">CEO Profile</span>
                    </a>
                    <a href="{{ route('admin.process-steps.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.process-steps*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">Process Steps</span>
                    </a>
                    <a href="{{ route('admin.social-media.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.social-media*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">Social Media</span>
                    </a>

                    {{-- SETTINGS --}}
                    <div class="pt-5 pb-2 px-2">
                        <span class="text-[0.6rem] font-bold text-slate-500 uppercase tracking-[0.2em]" x-show="!sidebarCollapsed" x-transition>Settings</span>
                        <div class="h-px bg-slate-200 mt-2" x-show="sidebarCollapsed"></div>
                    </div>

                    @php
                    $settingsLinks = [
                        ['route' => 'admin.settings.site', 'label' => 'Site Settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>'],
                        ['route' => 'admin.settings.seo', 'label' => 'SEO Settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>'],
                        ['route' => 'admin.settings.whatsapp', 'label' => 'WhatsApp', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'],
                        ['route' => 'admin.settings.analytics', 'label' => 'Analytics', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
                        ['route' => 'admin.media.index', 'label' => 'Media', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                    ];
                    @endphp

                    @foreach($settingsLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs($link['route']) ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $link['icon'] !!}</svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">{{ $link['label'] }}</span>
                    </a>
                    @endforeach

                    {{-- SYSTEM --}}
                    <div class="pt-5 pb-2 px-2">
                        <span class="text-[0.6rem] font-bold text-slate-500 uppercase tracking-[0.2em]" x-show="!sidebarCollapsed" x-transition>System</span>
                        <div class="h-px bg-slate-200 mt-2" x-show="sidebarCollapsed"></div>
                    </div>
                    @if(auth()->user()->hasRole('super-admin'))
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.users*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">Users</span>
                    </a>
                    @endif
                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.activity-logs*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap">Activity Logs</span>
                    </a>
                </nav>

                {{-- User Info --}}
                <div class="border-t border-slate-100 px-4 py-3.5 shrink-0 bg-slate-50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 text-sm font-display font-bold shrink-0">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0" x-show="!sidebarCollapsed" x-transition>
                            <div class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</div>
                            <div class="text-[0.65rem] text-slate-500 truncate">{{ ucfirst(str_replace('-', ' ', auth()->user()->roles->first()->name ?? 'user')) }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}" x-show="!sidebarCollapsed" x-transition>
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors p-1 rounded-lg hover:bg-red-500/10" title="Logout">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 transition-all duration-300" :class="sidebarCollapsed ? 'lg:ml-[4.5rem]' : 'lg:ml-[16.5rem]'">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-200">
                <div class="flex items-center justify-between px-5 lg:px-8 py-3.5">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:block text-slate-600 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4 transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-display font-bold text-slate-900">{{ $pageTitle ?? 'Dashboard' }}</h1>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" target="_blank" class="text-slate-600 hover:text-blue-600 text-sm font-medium flex items-center gap-1.5 transition-colors px-3 py-1.5 rounded-lg hover:bg-blue-50 border border-slate-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Site
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-5 lg:p-8">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
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
