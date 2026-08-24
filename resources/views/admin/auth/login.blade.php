@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-brand-bg px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-accent flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-xl">A</span>
            </div>
            <h1 class="text-2xl font-bold text-text-primary">Aldef Tech Admin</h1>
            <p class="text-text-muted text-sm mt-1">Sign in to your admin panel</p>
        </div>

        <div class="bg-brand-surface border border-brand-border rounded-2xl p-8">
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-text-secondary mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors @error('email') border-danger @enderror">
                    @error('email')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-text-secondary mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-3 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors @error('password') border-danger @enderror">
                    @error('password')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent focus:ring-accent/20">
                        <span class="text-sm text-text-muted">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full btn-primary text-sm font-semibold py-3 rounded-xl">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-text-dark mt-6">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</div>
@endsection
