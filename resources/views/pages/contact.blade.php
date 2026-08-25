@extends('layouts.app')
@section('content')
@php $pageTitle = 'Contact — Aldef Tech'; @endphp

<section class="section-padding pt-20 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.04)_0%,transparent_70%)] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-20">
            {{-- Left: Info --}}
            <div class="reveal">
                <span class="section-eyebrow">Contact</span>
                <h1 class="text-display-sm font-display text-text-primary mb-6">Mari Diskusikan<br>Project Anda</h1>
                <p class="text-text-secondary text-body-lg leading-relaxed mb-12">
                    Ceritakan kebutuhan bisnis Anda, dan kami akan membantu menemukan solusi digital yang tepat.
                </p>

                <div class="space-y-7">
                    @if($email = \App\Models\SiteSetting::get('email'))
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-xl bg-accent/8 border border-accent/15 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-110 group-hover:bg-accent/12">
                            <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.6875rem] text-text-dark uppercase tracking-[0.15em] font-medium">Email</div>
                            <a href="mailto:{{ $email }}" class="text-sm text-text-primary hover:text-accent transition-colors duration-200 font-medium">{{ $email }}</a>
                        </div>
                    </div>
                    @endif

                    @if($whatsapp = \App\Models\SiteSetting::get('whatsapp_number'))
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-xl bg-green-500/8 border border-green-500/15 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-110 group-hover:bg-green-500/12">
                            <svg class="w-5 h-5 text-green-400" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.6875rem] text-text-dark uppercase tracking-[0.15em] font-medium">WhatsApp</div>
                            <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="text-sm text-text-primary hover:text-accent transition-colors duration-200 font-medium">{{ $whatsapp }}</a>
                        </div>
                    </div>
                    @endif

                    @if($address = \App\Models\SiteSetting::get('address'))
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-xl bg-accent/8 border border-accent/15 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:scale-110 group-hover:bg-accent/12">
                            <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.6875rem] text-text-dark uppercase tracking-[0.15em] font-medium">Address</div>
                            <div class="text-sm text-text-primary font-medium">{{ $address }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Form --}}
            <div class="reveal-right reveal-delay-2">
                <div class="bg-brand-surface border border-brand-border rounded-2xl p-7 lg:p-8 relative overflow-hidden">
                    {{-- Subtle glow --}}
                    <div class="absolute top-0 right-0 w-[250px] h-[250px] bg-[radial-gradient(ellipse,rgba(124,92,252,0.04)_0%,transparent_70%)] pointer-events-none"></div>

                    @if(session('success'))
                    <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-xl text-sm mb-6">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="relative z-10">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Nama <span class="required">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="form-input @error('name') has-error @enderror" placeholder="Nama lengkap">
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Perusahaan</label>
                                <input type="text" name="company" value="{{ old('company') }}"
                                       class="form-input" placeholder="Nama perusahaan">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Email <span class="required">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="form-input @error('email') has-error @enderror" placeholder="email@perusahaan.com">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="6281234567890"
                                       class="form-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Jenis Project</label>
                                <select name="project_type" class="form-input form-select">
                                    <option value="">Pilih...</option>
                                    @foreach(config('aldeftech.lead.project_types') as $type)
                                    <option value="{{ $type }}" {{ old('project_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Budget Range</label>
                                <select name="budget_range" class="form-input form-select">
                                    <option value="">Pilih...</option>
                                    @foreach(config('aldeftech.lead.budget_ranges') as $budget)
                                    <option value="{{ $budget }}" {{ old('budget_range') === $budget ? 'selected' : '' }}>{{ $budget }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="form-label">Pesan <span class="required">*</span></label>
                            <textarea name="message" rows="4" required
                                      class="form-input resize-y @error('message') has-error @enderror"
                                      placeholder="Ceritakan kebutuhan project Anda...">{{ old('message') }}</textarea>
                            @error('message')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full btn-primary font-semibold py-3.5 text-sm">
                            Kirim Pesan
                            <svg class="w-4 h-4 ml-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
