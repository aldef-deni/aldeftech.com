@extends('layouts.admin')

@section('content')
<div class="min-h-screen w-full flex relative overflow-hidden bg-[#090E1A]">
    <!-- Ambient glowing orbs for premium feel -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/30 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-600/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[60%] h-[60%] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none"></div>

    <!-- Grid Pattern overlay -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTTAgNDBoNDBWMEgwem0zOSAzdjM4SDFWMWhMNDBaIiBmaWxsPSJub25lIi8+PHBhdGggZD0iTTAgM2g0MHYxSDB6bTAgN2g0MHYxSDB6bTAgN2g0MHYxSDB6bTAgN2g0MHYxSDB6bTAgN2g0MHYxSDB6bTMgMHY0MGgxdjQwem03IDB2NDBoMXY0MHptNyAwdjQwaDF2NDB6bTcgMHY0MGgxdjQwem03IDB2NDBoMXY0MHoiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-[0.05] pointer-events-none"></div>

    <div class="w-full flex items-center justify-center relative z-10 p-4">
        
        <div class="w-full max-w-5xl flex flex-col lg:flex-row overflow-hidden bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] min-h-[600px]">
            
            <!-- Left Branding Side -->
            <div class="hidden lg:flex w-1/2 flex-col justify-between p-12 relative overflow-hidden bg-gradient-to-br from-[#0C1427] to-[#090E1A] border-r border-white/10">
                <!-- Inner glow for the left side -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 blur-[80px] rounded-full"></div>
                
                <div class="relative z-10">
                    <img src="{{ asset('images/logo.png') }}" alt="Aldef Tech" class="h-10 mb-10 object-contain">
                    <h2 class="text-3xl font-display font-bold text-white mb-5 leading-tight">
                        Enterprise<br>Content Management
                    </h2>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        Manage your digital presence, AI marketing campaigns, CRM leads, and portfolio seamlessly from one unified dashboard.
                    </p>
                </div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full border-2 border-[#0C1427] bg-blue-500 flex items-center justify-center text-xs font-bold text-white shadow-lg">A</div>
                            <div class="w-8 h-8 rounded-full border-2 border-[#0C1427] bg-emerald-500 flex items-center justify-center text-xs font-bold text-white shadow-lg">S</div>
                            <div class="w-8 h-8 rounded-full border-2 border-[#0C1427] bg-indigo-500 flex items-center justify-center text-xs font-bold text-white shadow-lg">M</div>
                        </div>
                        <p class="text-xs text-slate-500 font-medium tracking-wide uppercase">Trusted by leading companies</p>
                    </div>
                </div>
            </div>

            <!-- Right Form Side -->
            <div class="w-full lg:w-1/2 bg-white p-8 lg:p-14 flex flex-col justify-center relative">
                <div class="max-w-md w-full mx-auto">
                    <div class="lg:hidden text-center mb-8">
                        <img src="{{ asset('images/logo-square.png') }}" alt="Aldef Tech" class="w-16 h-16 mx-auto mb-4">
                    </div>
                    
                    <h1 class="text-2xl lg:text-3xl font-display font-bold text-slate-900 mb-2 tracking-tight">Welcome Back</h1>
                    <p class="text-slate-500 text-sm mb-8">Please enter your credentials to access your account.</p>

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf

                        <div class="mb-5">
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all outline-none text-sm text-slate-700 @error('email') border-red-500 @enderror" placeholder="admin@aldeftech.com">
                            @error('email')
                                <p class="text-red-500 text-xs font-medium mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                                <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">Forgot Password?</a>
                            </div>
                            <div class="relative" x-data="{ showPassword: false }">
                                <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all outline-none text-sm text-slate-700 pr-10 @error('password') border-red-500 @enderror" placeholder="••••••••">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs font-medium mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center mb-8">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                                <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Keep me signed in</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3.5 rounded-xl text-sm transition-all shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.2)] flex justify-center items-center gap-2">
                            <span>Sign In to Control Panel</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                    
                    <p class="text-center text-xs text-slate-400 mt-10 font-medium">
                        &copy; {{ date('Y') }} Aldef Tech Enterprise CMS. All rights reserved.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
