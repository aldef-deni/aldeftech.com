@extends('layouts.admin')
@section('content')
@php $pageTitle = 'Dashboard'; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-brand-surface border border-brand-border rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-text-muted text-sm">Total Leads</span>
            <span class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center text-accent text-xs">📧</span>
        </div>
        <div class="text-2xl font-bold text-text-primary">{{ $stats['leads'] }}</div>
    </div>
    <div class="bg-brand-surface border border-brand-border rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-text-muted text-sm">New Leads</span>
            <span class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center text-green-400 text-xs">🆕</span>
        </div>
        <div class="text-2xl font-bold text-text-primary">{{ $stats['new_leads'] }}</div>
    </div>
    <div class="bg-brand-surface border border-brand-border rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-text-muted text-sm">Portfolio</span>
            <span class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400 text-xs">💼</span>
        </div>
        <div class="text-2xl font-bold text-text-primary">{{ $stats['portfolios'] }}</div>
    </div>
    <div class="bg-brand-surface border border-brand-border rounded-xl p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-text-muted text-sm">Blog Posts</span>
            <span class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center text-yellow-400 text-xs">📝</span>
        </div>
        <div class="text-2xl font-bold text-text-primary">{{ $stats['blog_posts'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-brand-surface border border-brand-border rounded-xl p-4">
        <span class="text-text-muted text-xs">Services</span>
        <div class="text-xl font-bold text-text-primary mt-1">{{ $stats['services'] }}</div>
    </div>
    <div class="bg-brand-surface border border-brand-border rounded-xl p-4">
        <span class="text-text-muted text-xs">Solutions</span>
        <div class="text-xl font-bold text-text-primary mt-1">{{ $stats['solutions'] }}</div>
    </div>
    <div class="bg-brand-surface border border-brand-border rounded-xl p-4">
        <span class="text-text-muted text-xs">Testimonials</span>
        <div class="text-xl font-bold text-text-primary mt-1">{{ $stats['testimonials'] }}</div>
    </div>
    <div class="bg-brand-surface border border-brand-border rounded-xl p-4">
        <span class="text-text-muted text-xs">FAQs</span>
        <div class="text-xl font-bold text-text-primary mt-1">{{ $stats['faqs'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Leads --}}
    <div class="bg-brand-surface border border-brand-border rounded-xl">
        <div class="px-5 py-4 border-b border-brand-border flex items-center justify-between">
            <h2 class="font-semibold text-text-primary">Recent Leads</h2>
            <a href="{{ route('admin.leads.index') }}" class="text-xs text-accent hover:text-accent-light">View All</a>
        </div>
        <div class="divide-y divide-brand-border">
            @forelse($recentLeads as $lead)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-text-primary">{{ $lead->name }}</div>
                    <div class="text-xs text-text-muted">{{ $lead->email }} · {{ $lead->project_type ?? 'N/A' }}</div>
                </div>
                <span class="text-xs px-2 py-1 rounded-full
                    @if($lead->status === 'new') bg-green-500/10 text-green-400
                    @elseif($lead->status === 'won') bg-accent/10 text-accent
                    @else bg-brand-surface-2 text-text-muted @endif">
                    {{ $lead->status_label }}
                </span>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-text-muted text-sm">No leads yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Activity Log --}}
    <div class="bg-brand-surface border border-brand-border rounded-xl">
        <div class="px-5 py-4 border-b border-brand-border flex items-center justify-between">
            <h2 class="font-semibold text-text-primary">Recent Activity</h2>
            <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-accent hover:text-accent-light">View All</a>
        </div>
        <div class="divide-y divide-brand-border">
            @forelse($recentActivity as $log)
            <div class="px-5 py-3">
                <div class="text-sm text-text-primary">{{ $log->description }}</div>
                <div class="text-xs text-text-muted mt-0.5">
                    {{ $log->user->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                </div>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-text-muted text-sm">No activity yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
