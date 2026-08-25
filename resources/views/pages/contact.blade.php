@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Hubungi Aldef Tech — Konsultasi Project & Solusi Software';
$metaDescription = 'Diskusikan kebutuhan custom software, SaaS, AI, dan sistem otomasi bisnis bersama tim engineer Aldef Tech.';

$whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number', '+62 822-7798-2997');
$emailAddress = \App\Models\SiteSetting::get('email', 'contact@aldeftech.com');
$fullAddress = 'Rumah Chiara 2, jalan Curug Induk, Bojong kulur, Kecamatan Gunung Putri, Kab Bogor';
@endphp

{{-- Hero Section (Signature Aldef Dark & Navy Tech Hero) --}}
<section class="hero-premium-dark section-padding pt-16 lg:pt-24 pb-20 lg:pb-28 relative overflow-hidden border-b border-slate-800/80">
    <div class="absolute inset-0 hero-grid-dark pointer-events-none opacity-60"></div>
    <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-blue-600/25 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] bg-cyan-500/20 blur-[130px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        {{-- Breadcrumb --}}
        <div class="flex items-center justify-center gap-2 text-xs font-mono text-slate-400 mb-6 reveal">
            <a href="{{ route('home') }}" class="hover:text-blue-400 transition-colors">HOME</a>
            <span>/</span>
            <span class="text-blue-400 font-semibold">CONTACT</span>
        </div>

        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/[0.08] border border-white/15 backdrop-blur-md shadow-2xs mb-6 reveal">
                <span class="status-dot status-dot-pulse"></span>
                <span class="text-xs font-semibold text-blue-200 tracking-wide uppercase">Start Your Transformation</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-white tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Mari Diskusikan <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-cyan-300 bg-clip-text text-transparent">Project Anda</span>
            </h1>
            <p class="text-slate-300 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Ceritakan kebutuhan bisnis atau tantangan sistem yang ingin Anda selesaikan. Tim engineer kami siap memberikan rancangan arsitektur dan estimasi terbaik.
            </p>
        </div>
    </div>
</section>

{{-- Main Form & Contact Info Section (Signature Aldef Dark & Navy Background) --}}
<section class="section-padding bg-gradient-to-b from-[#090E1A] via-[#0C1427] to-[#080D18] relative text-slate-300 border-b border-slate-800/80">
    {{-- Ambient Lighting --}}
    <div class="absolute top-1/4 left-0 w-96 h-96 bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute inset-0 subtle-grid opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            
            {{-- Left: Contact Details & Interactive Maps --}}
            <div class="lg:col-span-5 reveal space-y-6">
                <div>
                    <span class="text-xs font-mono font-bold text-blue-400 uppercase tracking-wider block mb-2">Direct Communication</span>
                    <h2 class="text-2xl lg:text-3xl font-display font-extrabold text-white mb-3">
                        Hubungi Kami Langsung
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Kami biasanya merespons dalam waktu kurang dari 24 jam kerja. Anda juga dapat menghubungi kami langsung melalui WhatsApp untuk respon instan.
                    </p>
                </div>

                <div class="space-y-4">
                    {{-- 1. WhatsApp Card (Dynamic Hover Effect) --}}
                    <a href="{{ \App\Services\WhatsAppService::getUrl() }}?text=Halo%20Aldef%20Tech,%20saya%20ingin%20berkonsultasi%20mengenai%20project%20software."
                       target="_blank" rel="noopener"
                       class="p-5 rounded-2xl bg-[#0F172A]/90 border border-white/10 shadow-xl flex items-center gap-4 group hover:bg-gradient-to-r hover:from-emerald-950/80 hover:via-slate-900 hover:to-emerald-950/60 hover:border-emerald-500/60 hover:-translate-y-1 hover:shadow-[0_14px_30px_-8px_rgba(16,185,129,0.25)] transition-all duration-300 cursor-pointer block">
                        <div class="flex items-center gap-4">
                            <div class="w-13 h-13 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-[0.6875rem] font-bold text-emerald-400 uppercase tracking-wider mb-0.5">Fast Response WhatsApp</div>
                                <div class="text-base font-bold text-white group-hover:text-emerald-300 transition-colors font-mono">{{ $whatsappNumber }}</div>
                            </div>
                            <span class="text-emerald-400 text-xs font-semibold group-hover:translate-x-1 transition-transform">Chat →</span>
                        </div>
                    </a>

                    {{-- 2. Email Card (Dynamic Hover Effect) --}}
                    <a href="mailto:{{ $emailAddress }}"
                       class="p-5 rounded-2xl bg-[#0F172A]/90 border border-white/10 shadow-xl flex items-center gap-4 group hover:bg-gradient-to-r hover:from-blue-950/80 hover:via-slate-900 hover:to-blue-950/60 hover:border-blue-500/60 hover:-translate-y-1 hover:shadow-[0_14px_30px_-8px_rgba(37,99,235,0.25)] transition-all duration-300 cursor-pointer block">
                        <div class="flex items-center gap-4">
                            <div class="w-13 h-13 rounded-2xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 shrink-0 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-[0.6875rem] font-bold text-blue-400 uppercase tracking-wider mb-0.5">Official Email Inquiry</div>
                                <div class="text-base font-bold text-white group-hover:text-blue-300 transition-colors">{{ $emailAddress }}</div>
                            </div>
                            <span class="text-blue-400 text-xs font-semibold group-hover:translate-x-1 transition-transform">Email →</span>
                        </div>
                    </a>

                    {{-- 3. Interactive Google Maps Card --}}
                    <div class="rounded-2xl bg-[#0F172A]/90 border border-white/10 shadow-xl overflow-hidden group hover:border-blue-500/50 hover:shadow-[0_16px_36px_-8px_rgba(37,99,235,0.25)] transition-all duration-300">
                        {{-- Location Info Header --}}
                        <div class="p-5 flex items-start gap-4 border-b border-white/10">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-[0.6875rem] font-bold text-amber-400 uppercase tracking-wider mb-0.5">Headquarters & Studio Office</div>
                                <div class="text-sm font-bold text-white leading-snug">{{ $fullAddress }}</div>
                            </div>
                        </div>

                        {{-- Interactive Map Preview with Direct Link Overlay --}}
                        <div class="relative aspect-[16/9] w-full bg-slate-900 overflow-hidden">
                            <iframe src="https://maps.google.com/maps?q={{ urlencode($fullAddress) }}&t=&z=16&ie=UTF8&iwloc=&output=embed"
                                    class="w-full h-full border-0 filter contrast-[1.05] opacity-85 group-hover:opacity-100 transition-opacity"
                                    loading="lazy"
                                    allowfullscreen></iframe>
                            
                            {{-- Clickable Direct Google Maps Button Overlay --}}
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($fullAddress) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="absolute bottom-3 right-3 px-4 py-2 rounded-xl bg-slate-950/90 hover:bg-blue-600 text-white border border-white/20 text-xs font-bold font-mono inline-flex items-center gap-2 shadow-lg backdrop-blur-md transition-all duration-200 group-hover:scale-105">
                                <span>Buka di Google Maps</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Contact Form --}}
            <div class="lg:col-span-7 reveal-right reveal-delay-2">
                <div class="bg-[#0F172A]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 lg:p-10 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.6)]">
                    <h3 class="text-2xl font-display font-extrabold text-white mb-2">
                        Kirimkan Detail Proyek
                    </h3>
                    <p class="text-slate-300 text-sm mb-8 leading-relaxed">
                        Isi formulir di bawah ini untuk konsultasi spesifikasi teknis, arsitektur data, dan estimasi timeline.
                    </p>

                    @if(session('success'))
                    <div class="bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 px-5 py-4 rounded-2xl text-sm mb-8 flex items-center gap-3 font-medium shadow-lg">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-mono font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-400">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-3 bg-slate-900/90 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm @error('name') border-rose-500 @enderror" placeholder="cth. Deni Afrizal">
                                @error('name')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-mono font-bold text-slate-300 uppercase tracking-wider mb-2">Perusahaan / Organisasi</label>
                                <input type="text" name="company" value="{{ old('company') }}"
                                       class="w-full px-4 py-3 bg-slate-900/90 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm" placeholder="cth. PT Maju Bersama">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-mono font-bold text-slate-300 uppercase tracking-wider mb-2">Email Perusahaan <span class="text-rose-400">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 bg-slate-900/90 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm @error('email') border-rose-500 @enderror" placeholder="cth. nama@perusahaan.com">
                                @error('email')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-mono font-bold text-slate-300 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="cth. 081234567890"
                                       class="w-full px-4 py-3 bg-slate-900/90 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-mono font-bold text-slate-300 uppercase tracking-wider mb-2">Jenis Project</label>
                                <select name="project_type" class="w-full px-4 py-3 bg-slate-900/90 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">
                                    <option value="" class="bg-slate-900 text-slate-400">Pilih Jenis Project...</option>
                                    @foreach(config('aldeftech.lead.project_types') as $type)
                                    <option value="{{ $type }}" {{ old('project_type') === $type ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-mono font-bold text-slate-300 uppercase tracking-wider mb-2">Estimasi Budget</label>
                                <select name="budget_range" class="w-full px-4 py-3 bg-slate-900/90 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm">
                                    <option value="" class="bg-slate-900 text-slate-400">Pilih Range Budget...</option>
                                    @foreach(config('aldeftech.lead.budget_ranges') as $budget)
                                    <option value="{{ $budget }}" {{ old('budget_range') === $budget ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $budget }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-mono font-bold text-slate-300 uppercase tracking-wider mb-2">Ceritakan Kebutuhan Proyek Anda <span class="text-rose-400">*</span></label>
                            <textarea name="message" rows="4" required
                                      class="w-full px-4 py-3 bg-slate-900/90 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all text-sm resize-y @error('message') border-rose-500 @enderror"
                                      placeholder="Jelaskan gambaran sistem, fitur utama, atau masalah operasional yang ingin diselesaikan...">{{ old('message') }}</textarea>
                            @error('message')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full btn-primary btn-lg font-bold py-4 text-base mt-4 shadow-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-500 hover:to-indigo-600 border border-blue-400/30">
                            <span>Kirimkan Permintaan Konsultasi</span>
                            <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
