@extends('layouts.admin')
@php $pageTitle = 'Testimonials'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $testimonials->count() }} testimonials</p>
    <a href="{{ route('admin.testimonials.create') }}" class="btn-primary text-sm py-2 px-4">+ Add Testimonial</a>
</div>
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Client</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Testimonial</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Rating</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($testimonials as $item)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4">
                    <div class="font-medium text-sm text-text-primary">{{ $item->client_name }}</div>
                    <div class="text-xs text-text-muted">{{ $item->company }} {{ $item->position ? '· ' . $item->position : '' }}</div>
                </td>
                <td class="px-5 py-4 text-sm text-text-muted line-clamp-2 max-w-xs">{{ $item->testimonial }}</td>
                <td class="px-5 py-4 text-center text-sm text-yellow-400">{{ str_repeat('★', $item->rating) }}</td>
                <td class="px-5 py-4 text-center">
                    <span class="text-xs px-2 py-1 rounded-full {{ $item->is_published ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                        {{ $item->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.testimonials.edit', $item) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted text-sm">No testimonials yet. Add testimonials from the admin.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
