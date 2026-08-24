@extends('layouts.admin')
@php $pageTitle = 'Portfolio'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $portfolios->count() }} projects</p>
    <a href="{{ route('admin.portfolio.create') }}" class="btn-primary text-sm py-2 px-4">+ Add Portfolio</a>
</div>
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Project</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Category</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Featured</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($portfolios as $item)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4">
                    <div class="font-medium text-sm text-text-primary">{{ $item->title }}</div>
                    <div class="text-xs text-text-muted mt-0.5">{{ $item->client ?? 'Demo' }} · {{ $item->year ?? 'N/A' }}</div>
                </td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $item->category->name ?? '—' }}</td>
                <td class="px-5 py-4 text-center">
                    <form method="POST" action="{{ route('admin.portfolio.toggle-featured', $item) }}">
                        @csrf
                        <button type="submit" class="text-xs px-2 py-1 rounded-full {{ $item->is_featured ? 'bg-yellow-500/10 text-yellow-400' : 'bg-brand-surface-2 text-text-muted' }}">
                            {{ $item->is_featured ? '★ Featured' : 'Featured' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4 text-center">
                    <form method="POST" action="{{ route('admin.portfolio.toggle-published', $item) }}">
                        @csrf
                        <button type="submit" class="text-xs px-2 py-1 rounded-full {{ $item->is_published ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                            {{ $item->is_published ? 'Published' : 'Draft' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($item->is_published)
                        <a href="{{ route('portfolio.show', $item->slug) }}" target="_blank" class="text-xs text-text-muted hover:text-text-primary">View</a>
                        @endif
                        <a href="{{ route('admin.portfolio.edit', $item) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                        <form method="POST" action="{{ route('admin.portfolio.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted text-sm">No portfolios yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
