@extends('layouts.admin')
@php $pageTitle = 'Leads & Inquiries'; @endphp
@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
    <p class="text-slate-500 text-sm font-medium">{{ $leads->total() }} total leads received</p>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.leads.export') }}" class="btn-secondary text-sm py-2 px-4 shadow-2xs">Export CSV</a>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white border border-slate-200 rounded-xl p-4 mb-6 shadow-2xs">
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, company..."
               class="bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white w-64">
        <select name="status" class="bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white">
            <option value="">All Status</option>
            @foreach(config('aldeftech.lead.statuses') as $key => $label)
            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="source" class="bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-sm text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white">
            <option value="">All Sources</option>
            @foreach(config('aldeftech.lead.sources') as $key => $label)
            <option value="{{ $key }}" {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <label class="flex items-center gap-2 cursor-pointer ml-1">
            <input type="checkbox" name="archived" value="1" {{ request('archived') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm text-slate-600 font-medium">Show archived</span>
        </label>
        <button type="submit" class="btn-primary text-sm py-2 px-5 shadow-xs">Filter</button>
    </form>
</div>

{{-- Leads Table --}}
<div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Prospect</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Project Type</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Budget</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($leads as $lead)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-bold text-sm text-slate-900">{{ $lead->name }}</div>
                        <div class="text-xs text-slate-500">{{ $lead->email }} @if($lead->company) • {{ $lead->company }} @endif</div>
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-700 font-medium">{{ $lead->project_type ?? '—' }}</td>
                    <td class="px-5 py-4 text-sm text-slate-600">{{ $lead->budget_range ?? '—' }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                            @if($lead->status === 'new') bg-emerald-50 text-emerald-700 border border-emerald-200
                            @elseif($lead->status === 'won') bg-blue-50 text-blue-700 border border-blue-200
                            @elseif($lead->status === 'lost') bg-red-50 text-red-700 border border-red-200
                            @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                            {{ $lead->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $lead->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View</a>
                            <form method="POST" action="{{ route('admin.leads.archive', $lead) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-slate-800">{{ $lead->archived_at ? 'Restore' : 'Archive' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm">No leads found matching current criteria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $leads->links() }}
</div>
@endsection
