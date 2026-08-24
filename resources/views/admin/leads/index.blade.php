@extends('layouts.admin')
@php $pageTitle = 'Leads'; @endphp
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
    <p class="text-text-muted text-sm">{{ $leads->total() }} leads</p>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.leads.export') }}" class="btn-secondary text-sm py-2 px-4">Export CSV</a>
    </div>
</div>

{{-- Filters --}}
<div class="bg-brand-surface border border-brand-border rounded-xl p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, company..."
               class="bg-brand-surface-2 border border-brand-border rounded-lg px-3 py-2 text-sm text-text-primary focus:outline-none focus:border-accent w-64">
        <select name="status" class="bg-brand-surface-2 border border-brand-border rounded-lg px-3 py-2 text-sm text-text-primary focus:outline-none focus:border-accent">
            <option value="">All Status</option>
            @foreach(config('aldeftech.lead.statuses') as $key => $label)
            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="source" class="bg-brand-surface-2 border border-brand-border rounded-lg px-3 py-2 text-sm text-text-primary focus:outline-none focus:border-accent">
            <option value="">All Sources</option>
            @foreach(config('aldeftech.lead.sources') as $key => $label)
            <option value="{{ $key }}" {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <label class="flex items-center gap-1.5">
            <input type="checkbox" name="archived" value="1" {{ request('archived') ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
            <span class="text-sm text-text-muted">Show archived</span>
        </label>
        <button type="submit" class="btn-primary text-sm py-2 px-4">Filter</button>
    </form>
</div>

<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Name</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Project</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Budget</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Status</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Source</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($leads as $lead)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4">
                    <div class="font-medium text-sm text-text-primary">{{ $lead->name }}</div>
                    <div class="text-xs text-text-muted">{{ $lead->email }}</div>
                </td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $lead->project_type ?? '—' }}</td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $lead->budget_range ?? '—' }}</td>
                <td class="px-5 py-4 text-center">
                    @php
                    $statusColors = [
                        'new' => 'bg-green-500/10 text-green-400',
                        'contacted' => 'bg-blue-500/10 text-blue-400',
                        'qualified' => 'bg-accent/10 text-accent',
                        'proposal' => 'bg-purple-500/10 text-purple-400',
                        'won' => 'bg-green-500/10 text-green-400',
                        'lost' => 'bg-red-500/10 text-red-400',
                    ];
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full {{ $statusColors[$lead->status] ?? 'bg-brand-surface-2 text-text-muted' }}">
                        {{ $lead->status_label }}
                    </span>
                </td>
                <td class="px-5 py-4 text-center text-xs text-text-muted">{{ $lead->source_label }}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="text-xs text-accent hover:text-accent-light">View</a>
                        <form method="POST" action="{{ route('admin.leads.archive', $lead) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="text-xs text-text-muted hover:text-text-primary">{{ $lead->archived_at ? 'Restore' : 'Archive' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-text-muted text-sm">No leads found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $leads->links() }}
</div>
@endsection
