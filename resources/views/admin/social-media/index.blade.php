@extends('layouts.admin')
@php $pageTitle = 'Social Media Links'; @endphp
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Existing Links --}}
    <div class="bg-brand-surface border border-brand-border rounded-xl">
        <div class="px-5 py-4 border-b border-brand-border">
            <h2 class="font-semibold text-text-primary text-sm">Current Links</h2>
        </div>
        <div class="divide-y divide-brand-border">
            @forelse($links as $link)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-text-primary">{{ ucfirst($link->platform) }}</div>
                    <div class="text-xs text-text-muted truncate max-w-xs">{{ $link->url }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-1 rounded-full {{ $link->is_active ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                        {{ $link->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <form method="POST" action="{{ route('admin.social-media.destroy', $link) }}" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-text-muted text-sm">No social links yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Add New --}}
    <div class="bg-brand-surface border border-brand-border rounded-xl p-6">
        <h2 class="font-semibold text-text-primary text-sm mb-4">Add Social Link</h2>
        <form method="POST" action="{{ route('admin.social-media.store') }}">
            @csrf
            <x-admin.form.input label="Platform" name="platform" required placeholder="e.g. LinkedIn, GitHub, Instagram" />
            <x-admin.form.input label="URL" name="url" type="url" required placeholder="https://..." />
            <x-admin.form.input label="Icon" name="icon" placeholder="Optional icon class" />
            <div class="flex items-center gap-2 mb-4">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
                <label class="text-sm text-text-secondary">Active</label>
            </div>
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Add Link</button>
        </form>
    </div>
</div>
@endsection
