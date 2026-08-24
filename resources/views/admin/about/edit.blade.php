@extends('layouts.admin')
@php $pageTitle = 'About Page'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.about.update') }}">
        @csrf @method('PUT')
        <x-admin.form.input label="Page Title" name="about_title" :value="\App\Models\SiteSetting::get('about_title', 'About Aldef Tech')" placeholder="Page heading" />
        <x-admin.form.input label="Subtitle" name="about_subtitle" :value="\App\Models\SiteSetting::get('about_subtitle', '')" placeholder="Short description" />
        <x-admin.form.textarea label="Full Content" name="about_content" :value="\App\Models\SiteSetting::get('about_content', '')" :rows="8" placeholder="About page content (HTML supported)" />
        <x-admin.form.textarea label="Mission" name="about_mission" :value="\App\Models\SiteSetting::get('about_mission', '')" :rows="3" />
        <x-admin.form.textarea label="Vision" name="about_vision" :value="\App\Models\SiteSetting::get('about_vision', '')" :rows="3" />
        <div class="mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-1.5">Values (one per line)</label>
            <textarea name="about_values[]" rows="4" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent resize-y">{{ implode("\n", json_decode(\App\Models\SiteSetting::get('about_values', '[]'), true) ?? []) }}</textarea>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save About Page</button>
        </div>
    </form>
</div>
@endsection
