@php
    // Without a blank layout the auth screen inherits the console's sidebar.
    $pageConfigs = ['myLayout' => 'blank'];
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Masuk')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
<style>
  .aldef-auth { min-height: 100dvh; }

  .aldef-auth-panel {
    position: relative;
    overflow: hidden;
    display: none;
  }

  @media (min-width: 992px) {
    .aldef-auth-panel { display: flex; }
  }

  .aldef-auth-panel::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
    background-size: 56px 56px;
    -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 40%, #000 20%, transparent 80%);
    mask-image: radial-gradient(ellipse 80% 70% at 50% 40%, #000 20%, transparent 80%);
  }

  .aldef-auth-panel-inner { position: relative; z-index: 1; }

  .aldef-auth-banner {
    border-radius: 0.875rem;
    border: 1px solid rgba(232, 211, 167, 0.2);
    box-shadow: 0 32px 70px -34px rgba(0, 0, 0, 0.8);
    overflow: hidden;
  }
  .aldef-auth-banner img { display: block; width: 100%; height: auto; }

  .aldef-auth-eyebrow {
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--aldef-gold-300, #E8D3A7);
  }

  .aldef-auth-form-col {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.25rem;
  }
</style>
@endsection

@section('content')
<div class="row g-0 aldef-auth">

    {{-- Brand panel --}}
    <div class="col-lg-6 col-xl-7 aldef-auth-panel aldef-auth-brand align-items-center justify-content-center p-5">
        <div class="aldef-auth-panel-inner text-center" style="max-width: 34rem;">
            <div class="aldef-auth-banner mb-5">
                <img src="{{ asset('images/aldef-tech-banner.webp') }}" alt="Aldef Tech" width="1376" height="768">
            </div>
            <p class="aldef-auth-eyebrow mb-3">Admin Console</p>
            <p class="aldef-auth-quote fs-4 mb-0" style="line-height: 1.4;">
                “Mitra Transformasi Digital Korporasi Anda”
            </p>
        </div>
    </div>

    {{-- Form --}}
    <div class="col-12 col-lg-6 col-xl-5 aldef-auth-form-col bg-body">
        <div class="w-100" style="max-width: 26rem;">

            <div class="app-brand mb-5">
                <a href="{{ route('home') }}" class="app-brand-link">
                    <span class="app-brand-logo">
                        <img src="{{ asset('images/logo.webp') }}" alt="Aldef Tech" style="height: 34px; width: auto;">
                    </span>
                </a>
            </div>

            <h4 class="mb-1">Selamat datang kembali</h4>
            <p class="text-body-secondary mb-5">Masuk untuk mengelola konten aldeftech.com.</p>

            @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                <i class="icon-base ti tabler-alert-circle mt-1"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="mb-4">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="nama@aldeftech.com" autocomplete="username" autofocus required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4 form-password-toggle">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group input-group-merge @error('password') is-invalid @enderror">
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••••" autocomplete="current-password" required>
                        <span class="input-group-text cursor-pointer" role="button" aria-label="Tampilkan kata sandi">
                            <i class="icon-base ti tabler-eye-off"></i>
                        </span>
                    </div>
                    @error('password')<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
                </div>

                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">Masuk</button>
            </form>

            <p class="text-center text-body-secondary mb-0">
                <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-1">
                    <i class="icon-base ti tabler-arrow-left icon-sm"></i>
                    <span>Kembali ke situs</span>
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
  // Vuexy's pages-auth.js also binds this, but the auth page here is standalone.
  document.querySelectorAll('.form-password-toggle .input-group-text').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      var input = toggle.parentElement.querySelector('input');
      var icon = toggle.querySelector('i');
      if (!input || !icon) return;
      var hidden = input.type === 'password';
      input.type = hidden ? 'text' : 'password';
      icon.classList.toggle('tabler-eye-off', !hidden);
      icon.classList.toggle('tabler-eye', hidden);
    });
  });
</script>
@endsection
