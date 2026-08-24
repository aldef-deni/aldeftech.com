@extends('layouts.admin')
@php $pageTitle = 'Analytics Settings'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.settings.analytics.update') }}">
        @csrf @method('PUT')
        <x-admin.form.input label="Google Analytics ID" name="google_analytics_id" :value="\App\Models\SiteSetting::get('google_analytics_id', '')" placeholder="G-XXXXXXXXXX" />
        <x-admin.form.input label="Google Tag Manager ID" name="google_tag_manager_id" :value="\App\Models\SiteSetting::get('google_tag_manager_id', '')" placeholder="GTM-XXXXXXX" />
        <x-admin.form.input label="Meta Pixel ID" name="meta_pixel_id" :value="\App\Models\SiteSetting::get('meta_pixel_id', '')" placeholder="000000000000000" />
        <x-admin.form.input label="Google Search Console Verification" name="google_search_console_verification" :value="\App\Models\SiteSetting::get('google_search_console_verification', '')" placeholder="Verification code" />
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Analytics Settings</button>
        </div>
    </form>
</div>
@endsection
