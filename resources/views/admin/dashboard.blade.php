@extends('layouts.admin')
@section('content')
@php $pageTitle = 'Dashboard'; @endphp

{{-- Welcome Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-surface via-brand-surface-2 to-brand-surface border border-brand-border mb-8 p-6 lg:p-8">
    <div class="absolute top-0 right-0 w-[400px] h-[300px] bg-[radial-gradient(ellipse,rgba(168,85,247,0.06)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[300px] h-[200px] bg-[radial-gradient(ellipse,rgba(6,182,212,0.04)_0%,transparent_70%)] pointer-events-none"></div>
    <div class="relative z-10">
        <h2 class="text-xl lg:text-2xl font-display font-bold text-text-primary mb-2">
            Welcome back, {{ auth()->user()->name }}
        </h2>
        <p class="text-text-muted text-sm">Here's an overview of your Aldef Tech CMS dashboard.</p>
    </div>
    {{-- Gradient top line --}}
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-brand-orange via-brand-magenta to-brand-cyan opacity-30"></div>
</div>

{{-- Primary Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="admin-stat-card" style="--stat-color: #A855F7">
        <div class="flex items-center justify-between mb-4">
            <span class="text-text-muted text-sm font-medium">Total Leads</span>
            <span class="w-10 h-10 rounded-xl bg-accent/8 border border-accent/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-text-primary tracking-tight">{{ $stats['leads'] }}</div>
        <div class="text-xs text-text-muted mt-1">All time leads</div>
    </div>

    <div class="admin-stat-card" style="--stat-color: #34D399">
        <div class="flex items-center justify-between mb-4">
            <span class="text-text-muted text-sm font-medium">New Leads</span>
            <span class="w-10 h-10 rounded-xl bg-success/8 border border-success/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-text-primary tracking-tight">{{ $stats['new_leads'] }}</div>
        <div class="text-xs text-success mt-1">Awaiting response</div>
    </div>

    <div class="admin-stat-card" style="--stat-color: #EC4899">
        <div class="flex items-center justify-between mb-4">
            <span class="text-text-muted text-sm font-medium">Portfolio</span>
            <span class="w-10 h-10 rounded-xl bg-brand-magenta/8 border border-brand-magenta/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-magenta" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-text-primary tracking-tight">{{ $stats['portfolios'] }}</div>
        <div class="text-xs text-text-muted mt-1">Published projects</div>
    </div>

    <div class="admin-stat-card" style="--stat-color: #F59E0B">
        <div class="flex items-center justify-between mb-4">
            <span class="text-text-muted text-sm font-medium">Blog Posts</span>
            <span class="w-10 h-10 rounded-xl bg-brand-orange/8 border border-brand-orange/15 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-text-primary tracking-tight">{{ $stats['blog_posts'] }}</div>
        <div class="text-xs text-text-muted mt-1">Published articles</div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    @php
    $secondaryStats = [
        ['label' => 'Services', 'value' => $stats['services'], 'color' => '#3B82F6'],
        ['label' => 'Solutions', 'value' => $stats['solutions'], 'color' => '#06B6D4'],
        ['label' => 'Testimonials', 'value' => $stats['testimonials'], 'color' => '#C084FC'],
        ['label' => 'FAQs', 'value' => $stats['faqs'], 'color' => '#F87171'],
    ];
    @endphp
    @foreach($secondaryStats as $stat)
    <div class="bg-brand-surface border border-brand-border rounded-xl p-4 hover:border-brand-border-light transition-colors">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-2 h-2 rounded-full" style="background: {{ $stat['color'] }}"></div>
            <span class="text-text-muted text-xs font-medium">{{ $stat['label'] }}</span>
        </div>
        <div class="text-xl font-display font-bold text-text-primary">{{ $stat['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Tables --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Leads --}}
    <div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-brand-border flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-accent animate-pulse"></div>
                <h2 class="font-display font-semibold text-text-primary">Recent Leads</h2>
            </div>
            <a href="{{ route('admin.leads.index') }}" class="text-xs text-accent hover:text-accent-light font-medium transition-colors">View All →</a>
        </div>
        <div class="divide-y divide-brand-border">
            @forelse($recentLeads as $lead)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-brand-surface-2/30 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent/15 to-brand-cyan/10 border border-accent/10 flex items-center justify-center text-accent text-xs font-semibold">
                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-text-primary">{{ $lead->name }}</div>
                        <div class="text-xs text-text-muted">{{ $lead->email }} · {{ $lead->project_type ?? 'N/A' }}</div>
                    </div>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    @if($lead->status === 'new') bg-success/10 text-success border border-success/15
                    @elseif($lead->status === 'won') bg-accent/10 text-accent border border-accent/15
                    @else bg-brand-surface-3 text-text-muted border border-brand-border @endif">
                    {{ $lead->status_label }}
                </span>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
                <svg class="w-8 h-8 text-text-dark/30 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <div class="text-text-muted text-sm">No leads yet</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Activity Log --}}
    <div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-brand-border flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-brand-cyan"></div>
                <h2 class="font-display font-semibold text-text-primary">Recent Activity</h2>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-accent hover:text-accent-light font-medium transition-colors">View All →</a>
        </div>
        <div class="divide-y divide-brand-border">
            @forelse($recentActivity as $log)
            <div class="px-6 py-4 hover:bg-brand-surface-2/30 transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-brand-surface-3 border border-brand-border flex items-center justify-center mt-0.5">
                        <svg class="w-3.5 h-3.5 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm text-text-primary">{{ $log->description }}</div>
                        <div class="text-xs text-text-muted mt-1">
                            {{ $log->user->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
                <svg class="w-8 h-8 text-text-dark/30 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-text-muted text-sm">No activity yet</div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
