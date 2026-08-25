@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-brand-bg px-4 relative overflow-hidden">
    {{-- Background orbs --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[radial-gradient(circle,rgba(168,85,247,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[radial-gradient(circle,rgba(6,182,212,0.04)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-[radial-gradient(circle,rgba(245,158,11,0.03)_0%,transparent_70%)] pointer-events-none"></div>

    {{-- Grid pattern --}}
    <div class="absolute inset-0 hero-grid opacity-20"></div>

    <div class="w-full max-w-md relative z-10">
        {{-- Logo --}}
        <div class="text-center mb-10">
            <img src="{{ asset('images/logo-square.png') }}" alt="Aldef Tech" class="w-16 h-16 mx-auto mb-5 drop-shadow-[0_0_20px_rgba(168,85,247,0.2)]">
            <h1 class="text-2xl font-display font-bold text-text-primary">Admin Panel</h1>
            <p class="text-text-muted text-sm mt-1.5">Sign in to manage your CMS</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-brand-surface/80 backdrop-blur-xl border border-brand-border rounded-2xl p-8 lg:p-10 shadow-elevated relative">
            {{-- Top gradient accent --}}
            <div class="absolute top-0 left-0 right-0 h-[2px] rounded-t-2xl bg-gradient-to-r from-brand-orange via-accent to-brand-cyan opacity-40"></div>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-5">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="form-input @error('email') has-error @enderror" placeholder="admin@aldeftech.com">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="form-label">Password</label>
                    <div class="relative" x-data="{ showPassword: false }">
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                               class="form-input pr-10 @error('password') has-error @enderror" placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-text-dark hover:text-text-muted transition-colors">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-7">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent focus:ring-accent/20 cursor-pointer">
                        <span class="text-sm text-text-muted group-hover:text-text-secondary transition-colors">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-primary font-semibold py-3.5 rounded-xl text-base">
                    Sign In
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-text-dark mt-8 font-medium">
            &copy; {{ date('Y') }} {{ config('app.name') }} · Powered by Aldef Tech
        </p>
    </div>
</div>
@endsection
