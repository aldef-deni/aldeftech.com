@extends('layouts.app')
@section('content')
@php $pageTitle = 'Contact — Aldef Tech'; @endphp

<section class="section-padding pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            {{-- Left: Info --}}
            <div class="reveal">
                <span class="text-xs font-semibold tracking-[0.2em] text-accent uppercase mb-4 block">Contact</span>
                <h1 class="text-display-sm text-text-primary mb-6">Mari Diskusikan Project Anda</h1>
                <p class="text-text-secondary text-body-lg leading-relaxed mb-10">
                    Ceritakan kebutuhan bisnis Anda, dan kami akan membantu menemukan solusi digital yang tepat.
                </p>

                <div class="space-y-6">
                    @if($email = \App\Models\SiteSetting::get('email'))
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center text-accent">📧</div>
                        <div>
                            <div class="text-xs text-text-dark uppercase tracking-wider">Email</div>
                            <a href="mailto:{{ $email }}" class="text-sm text-text-primary hover:text-accent transition-colors">{{ $email }}</a>
                        </div>
                    </div>
                    @endif

                    @if($whatsapp = \App\Models\SiteSetting::get('whatsapp_number'))
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center text-green-400">💬</div>
                        <div>
                            <div class="text-xs text-text-dark uppercase tracking-wider">WhatsApp</div>
                            <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" class="text-sm text-text-primary hover:text-accent transition-colors">{{ $whatsapp }}</a>
                        </div>
                    </div>
                    @endif

                    @if($address = \App\Models\SiteSetting::get('address'))
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center text-accent">📍</div>
                        <div>
                            <div class="text-xs text-text-dark uppercase tracking-wider">Address</div>
                            <div class="text-sm text-text-primary">{{ $address }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Form --}}
            <div class="reveal reveal-delay-2">
                <div class="bg-brand-surface border border-brand-border rounded-2xl p-8">
                    @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm mb-6">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-text-secondary mb-1.5">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors @error('name') border-danger @enderror">
                                @error('name')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-text-secondary mb-1.5">Perusahaan</label>
                                <input type="text" name="company" value="{{ old('company') }}"
                                       class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-text-secondary mb-1.5">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors @error('email') border-danger @enderror">
                                @error('email')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-text-secondary mb-1.5">WhatsApp</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="6281234567890"
                                       class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-text-secondary mb-1.5">Jenis Project</label>
                                <select name="project_type" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent transition-colors">
                                    <option value="">Pilih...</option>
                                    @foreach(config('aldeftech.lead.project_types') as $type)
                                    <option value="{{ $type }}" {{ old('project_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-text-secondary mb-1.5">Budget Range</label>
                                <select name="budget_range" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent transition-colors">
                                    <option value="">Pilih...</option>
                                    @foreach(config('aldeftech.lead.budget_ranges') as $budget)
                                    <option value="{{ $budget }}" {{ old('budget_range') === $budget ? 'selected' : '' }}>{{ $budget }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-text-secondary mb-1.5">Pesan <span class="text-danger">*</span></label>
                            <textarea name="message" rows="4" required
                                      class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors resize-y @error('message') border-danger @enderror"
                                      placeholder="Ceritakan kebutuhan project Anda...">{{ old('message') }}</textarea>
                            @error('message')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full btn-primary text-sm font-semibold py-3.5 rounded-xl">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
