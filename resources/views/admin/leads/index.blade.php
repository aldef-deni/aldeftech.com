@extends('layouts.layoutMaster')

@section('title', 'Leads')

@php
    $statusLabels = config('aldeftech.lead.statuses', []);
    $sourceLabels = config('aldeftech.lead.sources', []);
    $isArchived = request()->has('archived');
@endphp

@section('content')

<x-admin.page-head
    eyebrow="CRM"
    title="{{ $isArchived ? 'Leads Diarsipkan' : 'Leads' }}"
    subtitle="{{ $leads->total() }} permintaan{{ $isArchived ? ' diarsipkan' : ' aktif' }}">
    @if($isArchived)
        <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-inbox me-2"></i>Leads Aktif
        </a>
    @else
        <a href="{{ route('admin.leads.index', ['archived' => 1]) }}" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-archive me-2"></i>Arsip
        </a>
    @endif
    @if($viewingSpam)
        <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-inbox me-2"></i>Daftar Utama
        </a>
    @else
        <a href="{{ route('admin.leads.index', ['spam' => 1]) }}"
           class="btn btn-outline-secondary position-relative">
            <i class="icon-base ti tabler-shield-x me-2"></i>Spam
            @if($spamCount > 0)
                <span class="badge bg-label-danger ms-2">{{ $spamCount }}</span>
            @endif
        </a>
    @endif
    <a href="{{ route('admin.leads.export', request()->only('archived')) }}" class="btn btn-primary">
        <i class="icon-base ti tabler-download me-2"></i>Ekspor CSV
    </a>
</x-admin.page-head>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="row g-3 align-items-end">
            @if($isArchived)<input type="hidden" name="archived" value="1">@endif
            @if($viewingSpam)<input type="hidden" name="spam" value="1">@endif

            <div class="col-12 col-md-5">
                <label for="search" class="form-label">Cari</label>
                <input type="search" id="search" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Nama, email, atau perusahaan">
            </div>

            <div class="col-6 col-md-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach($statusLabels as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label for="source" class="form-label">Sumber</label>
                <select id="source" name="source" class="form-select">
                    <option value="">Semua</option>
                    @foreach($sourceLabels as $key => $label)
                        <option value="{{ $key }}" @selected(request('source') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Terapkan</button>
                @if(request()->hasAny(['search', 'status', 'source']))
                <a href="{{ route('admin.leads.index', request()->only('archived')) }}"
                   class="btn btn-outline-secondary btn-icon" title="Reset">
                    <i class="icon-base ti tabler-x"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    @if($leads->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-inbox"
                title="Tidak ada lead"
                message="{{ request()->hasAny(['search', 'status', 'source']) ? 'Tidak ada yang cocok dengan filter ini.' : 'Permintaan dari formulir kontak akan muncul di sini.' }}" />
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kontak</th>
                    <th class="d-none d-lg-table-cell">Kebutuhan</th>
                    <th class="d-none d-xl-table-cell">Anggaran</th>
                    <th>Status</th>
                    <th class="d-none d-md-table-cell">Masuk</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-primary">{{ initials_of($lead->name) }}</span>
                            </span>
                            <div class="text-truncate">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="fw-medium text-body d-block text-truncate">{{ $lead->name }}</a>
                                <small class="text-body-secondary d-block text-truncate">{{ $lead->company ?: $lead->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <small class="text-body-secondary">{{ $lead->project_type ?: '—' }}</small>
                    </td>
                    <td class="d-none d-xl-table-cell">
                        <small class="text-body-secondary">{{ $lead->budget_range ?: '—' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-label-{{ match($lead->status) {
                            'won' => 'success', 'lost' => 'secondary', 'new' => 'warning', default => 'info',
                        } }}">{{ $statusLabels[$lead->status] ?? ucfirst((string) $lead->status) }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <small class="text-body-secondary">{{ $lead->created_at?->translatedFormat('d M Y') }}</small>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Buka">
                            <i class="icon-base ti tabler-eye"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.leads.archive', $lead) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-icon btn-text-secondary"
                                    title="{{ $lead->archived_at ? 'Kembalikan' : 'Arsipkan' }}">
                                <i class="icon-base ti {{ $lead->archived_at ? 'tabler-archive-off' : 'tabler-archive' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.leads.spam', $lead) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    class="btn btn-sm btn-icon {{ $lead->is_spam ? 'btn-text-success' : 'btn-text-secondary' }}"
                                    title="{{ $lead->is_spam ? 'Bukan spam — kembalikan ke daftar utama' : 'Tandai sebagai spam' }}">
                                <i class="icon-base ti {{ $lead->is_spam ? 'tabler-shield-check' : 'tabler-shield-x' }}"></i>
                            </button>
                        </form>
                        <x-admin.delete
                            :action="route('admin.leads.destroy', $lead)"
                            :confirm="'Hapus lead dari ' . $lead->name . '? Data ini tidak dapat dipulihkan.'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($leads->hasPages())
    <div class="card-footer">{{ $leads->links() }}</div>
    @endif
    @endif
</div>

@endsection
