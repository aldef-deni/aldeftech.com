@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 relative overflow-hidden">
    {{-- Subtle background grid and ambient lighting --}}
    <div class="absolute inset-0 subtle-grid opacity-50 pointer-events-none"></div>
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-blue-500/5 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        {{-- Logo Header --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-square.png') }}" alt="Aldef Tech" class="w-14 h-14 mx-auto mb-4 drop-shadow-sm">
            <h1 class="text-2xl font-display font-bold text-slate-900">Admin Control Panel</h1>
            <p class="text-slate-500 text-sm mt-1">Sign in to manage Aldef Tech CMS</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-8 lg:p-10 shadow-elevated">
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
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-7">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-slate-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-primary font-semibold py-3.5 rounded-xl text-base shadow-md">
                    <span>Sign In to Dashboard</span>
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-8 font-medium">
            &copy; {{ date('Y') }} {{ config('app.name') }} • Enterprise Studio CMS
        </p>
    </div>
</div>
@endsection
