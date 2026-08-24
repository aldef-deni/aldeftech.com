@extends('layouts.admin')
@php $pageTitle = 'CEO Profile'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.ceo.update') }}">
        @csrf @method('PUT')

        <x-admin.form.input label="Full Name" name="name" :value="$profile->name" required />
        <x-admin.form.input label="Position" name="position" :value="$profile->position" required />
        <x-admin.form.input label="Profile Photo Path" name="profile_photo" :value="$profile->profile_photo ?? ''" placeholder="images/ceo/..." />
        <x-admin.form.textarea label="Short Bio" name="short_bio" :value="$profile->short_bio ?? ''" :rows="2" />
        <x-admin.form.textarea label="Full Bio" name="full_bio" :value="$profile->full_bio ?? ''" :rows="6" />

        <div class="mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-1.5">Skills (one per line)</label>
            <textarea name="skills[]" rows="4" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors resize-y">{{ is_array($profile->skills) ? implode("\n", $profile->skills) : '' }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-1.5">Experience (one per line)</label>
            <textarea name="experience[]" rows="4" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors resize-y">{{ is_array($profile->experience) ? implode("\n", $profile->experience) : '' }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <x-admin.form.input label="LinkedIn" name="linkedin" type="url" :value="$profile->linkedin ?? ''" placeholder="https://linkedin.com/in/..." />
            <x-admin.form.input label="GitHub" name="github" type="url" :value="$profile->github ?? ''" placeholder="https://github.com/..." />
        </div>
        <div class="grid grid-cols-2 gap-4">
            <x-admin.form.input label="Instagram" name="instagram" :value="$profile->instagram ?? ''" placeholder="https://instagram.com/..." />
            <x-admin.form.input label="Email" name="email" type="email" :value="$profile->email ?? ''" />
        </div>

        <div class="flex items-center gap-2 mt-2">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ $profile->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
            <label for="is_active" class="text-sm text-text-secondary">Active</label>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Profile</button>
        </div>
    </form>
</div>
@endsection
