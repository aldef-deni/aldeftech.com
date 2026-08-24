@extends('layouts.admin')
@php $pageTitle = 'Homepage Sections'; @endphp
@section('content')
<p class="text-text-muted text-sm mb-6">Manage the homepage sections. Toggle visibility and edit content for each section.</p>

<div class="space-y-4">
    @foreach($sections as $section)
    <div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden" x-data="{ open: false }">
        <div class="flex items-center justify-between px-5 py-4 cursor-pointer" @click="open = !open">
            <div class="flex items-center gap-3">
                <span class="text-text-dark text-sm">{{ $loop->iteration }}</span>
                <span class="font-medium text-text-primary text-sm">{{ $section->title ?? $section->section_key }}</span>
                @if($section->subtitle)
                <span class="text-xs text-text-muted hidden sm:inline">— {{ Str::limit($section->subtitle, 60) }}</span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs px-2 py-1 rounded-full {{ $section->is_visible ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                    {{ $section->is_visible ? 'Visible' : 'Hidden' }}
                </span>
                <svg class="w-4 h-4 text-text-muted transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
        <div x-show="open" x-collapse>
            <div class="px-5 pb-5 border-t border-brand-border pt-4">
                <form method="POST" action="{{ route('admin.homepage.update', $section) }}">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <x-admin.form.input label="Title" name="title" :value="$section->title ?? ''" />
                        <x-admin.form.input label="Subtitle" name="subtitle" :value="$section->subtitle ?? ''" />
                    </div>
                    <div class="flex items-center gap-2 mb-4">
                        <input type="checkbox" name="is_visible" value="1" {{ $section->is_visible ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
                        <label class="text-sm text-text-secondary">Visible on frontend</label>
                    </div>
                    <button type="submit" class="btn-primary text-sm py-2 px-4">Save</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
