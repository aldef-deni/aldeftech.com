@extends('layouts.admin')
@php $pageTitle = 'Activity Logs'; @endphp
@section('content')
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">User</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Action</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Description</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Time</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($logs as $log)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-3 text-sm text-text-primary">{{ $log->user->name ?? 'System' }}</td>
                <td class="px-5 py-3">
                    <span class="text-xs px-2 py-1 rounded-full bg-brand-surface-2 text-text-muted">{{ $log->action }}</span>
                </td>
                <td class="px-5 py-3 text-sm text-text-secondary">{{ $log->description }}</td>
                <td class="px-5 py-3 text-xs text-text-muted text-right">{{ $log->created_at->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-8 text-center text-text-muted text-sm">No activity logs yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $logs->links() }}</div>
@endsection
