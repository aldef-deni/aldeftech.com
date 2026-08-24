@extends('layouts.admin')
@php $pageTitle = 'FAQ'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $faqs->count() }} FAQs</p>
    <a href="{{ route('admin.faq.create') }}" class="btn-primary text-sm py-2 px-4">+ Add FAQ</a>
</div>
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Question</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Category</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($faqs as $item)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4 text-sm text-text-primary">{{ $item->question }}</td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $item->category ?? '—' }}</td>
                <td class="px-5 py-4 text-center">
                    <span class="text-xs px-2 py-1 rounded-full {{ $item->is_published ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                        {{ $item->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.faq.edit', $item) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                        <form method="POST" action="{{ route('admin.faq.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-8 text-center text-text-muted text-sm">No FAQs yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
