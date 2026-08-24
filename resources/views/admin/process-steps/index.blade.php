@extends('layouts.admin')
@php $pageTitle = 'Process Steps'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $steps->count() }} steps</p>
    <a href="{{ route('admin.process-steps.create') }}" class="btn-primary text-sm py-2 px-4">+ Add Step</a>
</div>
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Step</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Title</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Description</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($steps as $step)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4 text-center text-sm font-medium text-accent">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="px-5 py-4 text-sm text-text-primary font-medium">{{ $step->title }}</td>
                <td class="px-5 py-4 text-sm text-text-muted line-clamp-1">{{ $step->description }}</td>
                <td class="px-5 py-4 text-center">
                    <span class="text-xs px-2 py-1 rounded-full {{ $step->is_published ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                        {{ $step->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.process-steps.edit', $step) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                        <form method="POST" action="{{ route('admin.process-steps.destroy', $step) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted text-sm">No process steps yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
