@extends('layouts.app')
@section('content')
@php
$pageTitle = 'Hubungi Aldef Tech — Konsultasi Project & Solusi Software';
$metaDescription = 'Diskusikan kebutuhan custom software, SaaS, AI, dan sistem otomasi bisnis bersama tim engineer Aldef Tech.';
@endphp

{{-- Hero Section --}}
<section class="hero-light-gradient section-padding pt-16 lg:pt-24 relative overflow-hidden border-b border-slate-200/60">
    <div class="absolute inset-0 subtle-grid opacity-60 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="section-eyebrow justify-center reveal">Contact Us</span>
            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-display font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 reveal reveal-delay-1">
                Mari Diskusikan Project Anda
            </h1>
            <p class="text-slate-600 text-lg lg:text-xl leading-relaxed reveal reveal-delay-2">
                Ceritakan kebutuhan bisnis atau tantangan sistem yang ingin Anda selesaikan. Tim engineer kami siap memberikan rekomendasi solusi terbaik.
            </p>
        </div>
    </div>
</section>

{{-- Main Form & Contact Info Section --}}
<section class="section-padding bg-slate-50/80 relative">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            
            {{-- Left: Contact Details --}}
            <div class="lg:col-span-5 reveal">
                <h2 class="text-2xl font-display font-bold text-slate-900 mb-4">
                    Hubungi Kami Langsung
                </h2>
                <p class="text-slate-600 text-base leading-relaxed mb-8">
                    Kami biasanya merespons dalam waktu kurang dari 24 jam kerja. Anda juga dapat menghubungi kami langsung melalui WhatsApp untuk respon instan.
                </p>

                <div class="space-y-4">
                    {{-- Email --}}
                    @if($email = \App\Models\SiteSetting::get('email'))
                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center gap-4 group hover:border-blue-400 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.6875rem] font-bold text-slate-400 uppercase tracking-wider">Email Inquiry</div>
                            <a href="mailto:{{ $email }}" class="text-base font-semibold text-slate-900 hover:text-blue-600 transition-colors">{{ $email }}</a>
                        </div>
                    </div>
                    @endif

                    {{-- WhatsApp --}}
                    @if($whatsapp = \App\Models\SiteSetting::get('whatsapp_number'))
                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center gap-4 group hover:border-emerald-400 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.6875rem] font-bold text-slate-400 uppercase tracking-wider">Fast Response WhatsApp</div>
                            <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="text-base font-semibold text-slate-900 hover:text-emerald-600 transition-colors">{{ $whatsapp }}</a>
                        </div>
                    </div>
                    @endif

                    {{-- Location --}}
                    @if($address = \App\Models\SiteSetting::get('address'))
                    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.6875rem] font-bold text-slate-400 uppercase tracking-wider">Headquarters / Studio</div>
                            <div class="text-base font-semibold text-slate-900">{{ $address }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Contact Form --}}
            <div class="lg:col-span-7 reveal-right reveal-delay-2">
                <div class="bg-white border border-slate-200/90 rounded-2xl p-8 lg:p-10 shadow-elevated">
                    <h3 class="text-xl font-display font-bold text-slate-900 mb-2">
                        Kirimkan Detail Proyek
                    </h3>
                    <p class="text-slate-500 text-sm mb-6">
                        Isi formulir di bawah ini untuk konsultasi spesifikasi teknis dan estimasi timeline.
                    </p>

                    @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3.5 rounded-xl text-sm mb-6 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="form-input @error('name') has-error @enderror" placeholder="cth. Deni Afrizal">
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Perusahaan / Organisasi</label>
                                <input type="text" name="company" value="{{ old('company') }}"
                                       class="form-input" placeholder="cth. PT Maju Bersama">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Email Perusahaan <span class="required">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="form-input @error('email') has-error @enderror" placeholder="cth. nama@perusahaan.com">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="cth. 6281234567890"
                                       class="form-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Jenis Project</label>
                                <select name="project_type" class="form-input form-select">
                                    <option value="">Pilih Jenis Project...</option>
                                    @foreach(config('aldeftech.lead.project_types') as $type)
                                    <option value="{{ $type }}" {{ old('project_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Estimasi Budget</label>
                                <select name="budget_range" class="form-input form-select">
                                    <option value="">Pilih Range Budget...</option>
                                    @foreach(config('aldeftech.lead.budget_ranges') as $budget)
                                    <option value="{{ $budget }}" {{ old('budget_range') === $budget ? 'selected' : '' }}>{{ $budget }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Ceritakan Kebutuhan Proyek Anda <span class="required">*</span></label>
                            <textarea name="message" rows="4" required
                                      class="form-input resize-y @error('message') has-error @enderror"
                                      placeholder="Jelaskan gambaran sistem, fitur utama, atau masalah operasional yang ingin diselesaikan...">{{ old('message') }}</textarea>
                            @error('message')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full btn-primary btn-lg font-semibold py-4 text-base mt-2 shadow-md">
                            <span>Kirimkan Permintaan Konsultasi</span>
                            <svg class="w-4 h-4 ml-1 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
