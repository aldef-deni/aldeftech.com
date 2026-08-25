@extends('layouts.admin')
@section('content')
@php $pageTitle = 'Dashboard Overview'; @endphp

{{-- Welcome Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-white border border-slate-200 mb-8 p-6 lg:p-8 shadow-2xs">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl lg:text-2xl font-display font-bold text-slate-900 mb-1">
                Welcome back, {{ auth()->user()->name }}
            </h2>
            <p class="text-slate-500 text-sm">Here's the latest operational overview of your Aldef Tech platform.</p>
        </div>
        <div>
            <a href="{{ route('admin.leads.index') }}" class="btn-primary btn-sm text-xs font-semibold">
                View All Leads →
            </a>
        </div>
    </div>
</div>

{{-- Primary Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Leads --}}
    <div class="admin-stat-card" style="--stat-color: #2563EB">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Leads</span>
            <span class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-slate-900 tracking-tight">{{ $stats['leads'] }}</div>
        <div class="text-xs text-slate-400 mt-1">Inbound consultations</div>
    </div>

    {{-- New Leads --}}
    <div class="admin-stat-card" style="--stat-color: #10B981">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">New Inquiries</span>
            <span class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-slate-900 tracking-tight">{{ $stats['new_leads'] }}</div>
        <div class="text-xs text-emerald-600 font-semibold mt-1">Requires response</div>
    </div>

    {{-- Portfolios --}}
    <div class="admin-stat-card" style="--stat-color: #4F46E5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Case Studies</span>
            <span class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-slate-900 tracking-tight">{{ $stats['portfolios'] }}</div>
        <div class="text-xs text-slate-400 mt-1">Published portfolio items</div>
    </div>

    {{-- Blog --}}
    <div class="admin-stat-card" style="--stat-color: #F59E0B">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Articles & Insights</span>
            <span class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </span>
        </div>
        <div class="text-3xl font-display font-bold text-slate-900 tracking-tight">{{ $stats['blog_posts'] }}</div>
        <div class="text-xs text-slate-400 mt-1">Published posts</div>
    </div>
</div>

{{-- Secondary Quick Counts --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    @php
    $secondaryStats = [
        ['label' => 'Services', 'value' => $stats['services'], 'color' => '#2563EB'],
        ['label' => 'Solutions', 'value' => $stats['solutions'], 'color' => '#4F46E5'],
        ['label' => 'Testimonials', 'value' => $stats['testimonials'], 'color' => '#7C3AED'],
        ['label' => 'FAQs', 'value' => $stats['faqs'], 'color' => '#0284C7'],
    ];
    @endphp
    @foreach($secondaryStats as $stat)
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs">
        <div class="flex items-center gap-2 mb-1.5">
            <div class="w-2 h-2 rounded-full" style="background: {{ $stat['color'] }}"></div>
            <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">{{ $stat['label'] }}</span>
        </div>
        <div class="text-2xl font-display font-bold text-slate-900">{{ $stat['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Activity & Leads Tables --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Recent Leads --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
                <h3 class="font-display font-bold text-slate-900 text-base">Recent Inbound Leads</h3>
            </div>
            <a href="{{ route('admin.leads.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">View All →</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentLeads as $lead)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-100">
                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">{{ $lead->name }}</div>
                        <div class="text-xs text-slate-500">{{ $lead->email }} • {{ $lead->project_type ?? 'General' }}</div>
                    </div>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                    @if($lead->status === 'new') bg-emerald-50 text-emerald-700 border border-emerald-200
                    @elseif($lead->status === 'won') bg-blue-50 text-blue-700 border border-blue-200
                    @else bg-slate-100 text-slate-600 border border-slate-200 @endif">
                    {{ $lead->status_label }}
                </span>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-slate-400 text-sm">
                No recent leads.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Activity Logs --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                <h3 class="font-display font-bold text-slate-900 text-base">System Activity Logs</h3>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">View All →</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentActivity as $log)
            <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center mt-0.5 shrink-0 text-slate-600">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm text-slate-800 font-medium">{{ $log->description }}</div>
                        <div class="text-xs text-slate-400 mt-1">
                            {{ $log->user->name ?? 'System' }} • {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-slate-400 text-sm">
                No recent activities.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
