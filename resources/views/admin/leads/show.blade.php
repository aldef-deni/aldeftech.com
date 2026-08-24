@extends('layouts.admin')
@php $pageTitle = 'Lead — ' . $lead->name; @endphp
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Lead Info --}}
    <div class="lg:col-span-2 bg-brand-surface border border-brand-border rounded-xl p-6">
        <h2 class="font-semibold text-text-primary mb-4">Lead Information</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-text-muted">Name:</span> <span class="text-text-primary">{{ $lead->name }}</span></div>
            <div><span class="text-text-muted">Company:</span> <span class="text-text-primary">{{ $lead->company ?? '—' }}</span></div>
            <div><span class="text-text-muted">Email:</span> <span class="text-text-primary">{{ $lead->email }}</span></div>
            <div><span class="text-text-muted">WhatsApp:</span> <span class="text-text-primary">{{ $lead->whatsapp ?? '—' }}</span></div>
            <div><span class="text-text-muted">Project Type:</span> <span class="text-text-primary">{{ $lead->project_type ?? '—' }}</span></div>
            <div><span class="text-text-muted">Budget:</span> <span class="text-text-primary">{{ $lead->budget_range ?? '—' }}</span></div>
            <div><span class="text-text-muted">Source:</span> <span class="text-text-primary">{{ $lead->source_label }}</span></div>
            <div><span class="text-text-muted">Created:</span> <span class="text-text-primary">{{ $lead->created_at->format('d M Y H:i') }}</span></div>
        </div>
        <div class="mt-4">
            <span class="text-text-muted text-sm">Message:</span>
            <div class="mt-1 text-sm text-text-secondary bg-brand-surface-2 rounded-lg p-4 whitespace-pre-wrap">{{ $lead->message }}</div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        {{-- Status --}}
        <div class="bg-brand-surface border border-brand-border rounded-xl p-5">
            <h3 class="font-semibold text-text-primary text-sm mb-3">Update Status</h3>
            <form method="POST" action="{{ route('admin.leads.update-status', $lead) }}">
                @csrf @method('PUT')
                <select name="status" class="w-full bg-brand-surface-2 border border-brand-border rounded-lg px-3 py-2 text-sm text-text-primary mb-3">
                    @foreach(config('aldeftech.lead.statuses') as $key => $label)
                    <option value="{{ $key }}" {{ $lead->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full btn-primary text-sm py-2">Update Status</button>
            </form>
        </div>

        {{-- Notes --}}
        <div class="bg-brand-surface border border-brand-border rounded-xl p-5">
            <h3 class="font-semibold text-text-primary text-sm mb-3">Add Note</h3>
            <form method="POST" action="{{ route('admin.leads.add-note', $lead) }}">
                @csrf
                <textarea name="note" rows="3" class="w-full bg-brand-surface-2 border border-brand-border rounded-lg px-3 py-2 text-sm text-text-primary focus:outline-none focus:border-accent resize-y mb-3" placeholder="Add a note..." required></textarea>
                <button type="submit" class="w-full btn-secondary text-sm py-2">Add Note</button>
            </form>
        </div>
    </div>
</div>

{{-- Notes List --}}
@if($lead->notes->count())
<div class="mt-6 bg-brand-surface border border-brand-border rounded-xl p-6">
    <h3 class="font-semibold text-text-primary mb-4">Notes</h3>
    <div class="space-y-3">
        @foreach($lead->notes as $note)
        <div class="bg-brand-surface-2 rounded-lg p-4">
            <div class="flex items-center justify-between mb-1">
                <span class="text-sm font-medium text-text-primary">{{ $note->user->name ?? 'System' }}</span>
                <span class="text-xs text-text-muted">{{ $note->created_at->diffForHumans() }}</span>
            </div>
            <div class="text-sm text-text-secondary whitespace-pre-wrap">{{ $note->note }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
