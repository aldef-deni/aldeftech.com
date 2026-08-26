@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Login - Aldef Tech')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/@form-validation/popular.js',
'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
@vite(['resources/assets/js/pages-auth.js'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-6">
      <!-- Login -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-6">
            <a href="{{ url('/') }}" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="{{ asset('images/logo.png') }}" alt="Aldef Tech" style="height: 30px;">
              </span>
              <span class="app-brand-text demo text-heading fw-bold ms-2">Aldef Tech CMS</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Welcome to Aldef Tech👋</h4>
          <p class="mb-6">Please sign-in to your account.</p>

          <form id="formAuthentication" class="mb-4" action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            
            <div class="mb-6 form-control-validation">
              <label for="email" class="form-label">Email Address</label>
              <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}"
                placeholder="Enter your email" autofocus required />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-6 form-password-toggle form-control-validation">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" required />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
              @error('password')
                  <div class="text-danger mt-1"><small>{{ $message }}</small></div>
              @enderror
            </div>
            
            <div class="my-4">
              <div class="d-flex justify-content-between">
                <div class="form-check mb-0 ms-2">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                  <label class="form-check-label" for="remember"> Remember Me </label>
                </div>
              </div>
            </div>
            
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign In</button>
            </div>
          </form>

        </div>
      </div>
      <!-- /Login -->
    </div>
  </div>
</div>
@endsection
