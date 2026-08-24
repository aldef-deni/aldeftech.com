@extends('layouts.admin')
@php $pageTitle = 'SEO Settings'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.settings.seo.update') }}">
        @csrf @method('PUT')
        <x-admin.form.input label="Default Meta Title" name="seo_default_title" :value="\App\Models\SiteSetting::get('seo_default_title', config('aldeftech.seo.default_title'))" />
        <x-admin.form.textarea label="Default Meta Description" name="seo_default_description" :value="\App\Models\SiteSetting::get('seo_default_description', config('aldeftech.seo.default_description'))" :rows="3" />
        <x-admin.form.input label="Default OG Image" name="seo_default_image" :value="\App\Models\SiteSetting::get('seo_default_image', config('aldeftech.seo.default_image'))" placeholder="images/og-default.jpg" />
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save SEO Settings</button>
        </div>
    </form>
</div>
@endsection
