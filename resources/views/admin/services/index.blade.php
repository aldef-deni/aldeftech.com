@extends('layouts.admin')
@php $pageTitle = 'Services'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $services->count() }} services</p>
    <a href="{{ route('admin.services.create') }}" class="btn-primary text-sm py-2 px-4">+ Add Service</a>
</div>

<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-brand-border">
                    <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Title</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Icon</th>
                    <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Sort</th>
                    <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-border">
                @forelse($services as $service)
                <tr class="hover:bg-brand-surface-2/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-medium text-sm text-text-primary">{{ $service->title }}</div>
                        <div class="text-xs text-text-muted mt-0.5 line-clamp-1">{{ $service->short_description }}</div>
                    </td>
                    <td class="px-5 py-4 text-sm text-text-muted">{{ $service->icon ?? '—' }}</td>
                    <td class="px-5 py-4 text-center text-sm text-text-muted">{{ $service->sort_order }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-xs px-2 py-1 rounded-full {{ $service->is_published ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                            {{ $service->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.services.edit', $service) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-text-muted text-sm">No services yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
