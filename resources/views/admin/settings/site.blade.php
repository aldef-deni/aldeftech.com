@extends('layouts.admin')
@php $pageTitle = 'Site Settings'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.settings.site.update') }}">
        @csrf @method('PUT')
        <h2 class="text-lg font-semibold text-text-primary mb-4">General</h2>
        <x-admin.form.input label="Site Name" name="site_name" :value="\App\Models\SiteSetting::get('site_name', config('app.name'))" />
        <x-admin.form.input label="Tagline" name="site_tagline" :value="\App\Models\SiteSetting::get('site_tagline', config('aldeftech.tagline'))" />
        <x-admin.form.textarea label="Site Description" name="site_description" :value="\App\Models\SiteSetting::get('site_description', '')" :rows="3" />
        <x-admin.form.input label="Logo Path" name="site_logo" :value="\App\Models\SiteSetting::get('site_logo', '')" placeholder="images/logo.png" />
        <x-admin.form.input label="Favicon Path" name="site_favicon" :value="\App\Models\SiteSetting::get('site_favicon', '')" placeholder="images/favicon.ico" />

        <h2 class="text-lg font-semibold text-text-primary mb-4 mt-8">Contact</h2>
        <div class="grid grid-cols-2 gap-4">
            <x-admin.form.input label="Email" name="email" type="email" :value="\App\Models\SiteSetting::get('email', '')" />
            <x-admin.form.input label="Phone" name="phone" :value="\App\Models\SiteSetting::get('phone', '')" />
        </div>
        <x-admin.form.input label="Address" name="address" :value="\App\Models\SiteSetting::get('address', '')" />
        <x-admin.form.input label="Google Maps URL" name="google_maps_url" type="url" :value="\App\Models\SiteSetting::get('google_maps_url', '')" />

        <h2 class="text-lg font-semibold text-text-primary mb-4 mt-8">Footer</h2>
        <x-admin.form.textarea label="Footer Description" name="footer_description" :value="\App\Models\SiteSetting::get('footer_description', '')" :rows="2" />
        <x-admin.form.input label="Copyright Text" name="copyright" :value="\App\Models\SiteSetting::get('copyright', '')" />

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Settings</button>
        </div>
    </form>
</div>
@endsection
