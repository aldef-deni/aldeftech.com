@extends('layouts.admin')
@php $pageTitle = 'Blog Tags'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $tags->count() }} tags</p>
    <a href="{{ route('admin.tags.create') }}" class="btn-primary text-sm py-2 px-4">+ Add Tag</a>
</div>
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Name</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Posts</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($tags as $tag)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4 text-sm text-text-primary">{{ $tag->name }}</td>
                <td class="px-5 py-4 text-center text-sm text-text-muted">{{ $tag->posts_count }}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-5 py-8 text-center text-text-muted text-sm">No tags yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
