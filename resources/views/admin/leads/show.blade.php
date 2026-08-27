@extends('layouts.layoutMaster')

@section('title', 'Lead — ' . $lead->name)

@php
    $statusLabels = config('aldeftech.lead.statuses', []);
    $waUrl = $lead->whatsapp
        ? 'https://wa.me/' . preg_replace('/\D/', '', $lead->whatsapp)
        : null;

    try {
        $assignableUsers = \App\Models\User::orderBy('name')->pluck('name', 'id')->all();
    } catch (\Throwable $e) {
        $assignableUsers = [];
    }
@endphp

@section('content')

<x-admin.page-head
    eyebrow="Lead"
    :title="$lead->name"
    :subtitle="trim(($lead->company ? $lead->company . ' · ' : '') . 'Masuk ' . $lead->created_at?->translatedFormat('d F Y, H:i'))"
    :back="route('admin.leads.index')">
    @if($waUrl)
    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
        <i class="icon-base ti tabler-brand-whatsapp me-2"></i>WhatsApp
    </a>
    @endif
    <a href="mailto:{{ $lead->email }}" class="btn btn-outline-secondary">
        <i class="icon-base ti tabler-mail me-2"></i>Balas Email
    </a>
    <form method="POST" action="{{ route('admin.leads.archive', $lead) }}" class="d-inline">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-outline-secondary">
            <i class="icon-base ti {{ $lead->archived_at ? 'tabler-archive-off' : 'tabler-archive' }} me-2"></i>{{ $lead->archived_at ? 'Kembalikan' : 'Arsipkan' }}
        </button>
    </form>
</x-admin.page-head>

<div class="row g-4">

    {{-- Message + notes ----------------------------------------------------}}
    <div class="col-12 col-lg-8">

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Pesan</h5>
                <span class="badge bg-label-{{ match($lead->status) {
                    'won' => 'success', 'lost' => 'secondary', 'new' => 'warning', default => 'info',
                } }}">{{ $lead->status_label }}</span>
            </div>
            <div class="card-body">
                <p class="mb-0" style="white-space: pre-line;">{{ $lead->message }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Catatan Internal</h5></div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.leads.add-note', $lead) }}" class="mb-4">
                    @csrf
                    <x-admin.form.textarea
                        label="Tambah catatan" name="note" :rows="3" required
                        placeholder="Hasil telepon, kesepakatan, atau tindak lanjut berikutnya" />
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="icon-base ti tabler-plus me-2"></i>Simpan Catatan
                    </button>
                </form>

                @if($lead->notes->isEmpty())
                    <x-admin.empty icon="tabler-notes" title="Belum ada catatan" />
                @else
                <ul class="timeline timeline-dashed mb-0">
                    @foreach($lead->notes as $note)
                    <li class="timeline-item timeline-item-transparent ps-4 pb-4">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event p-0">
                            <div class="timeline-header mb-1 d-flex justify-content-between gap-3">
                                <h6 class="mb-0 small fw-medium">{{ $note->user->name ?? 'Sistem' }}</h6>
                                <small class="text-body-secondary text-nowrap">{{ $note->created_at?->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 text-body-secondary" style="white-space: pre-line;">{{ $note->note }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Details -------------------------------------------------------------}}
    <div class="col-12 col-lg-4">

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Status</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leads.update-status', $lead) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Tahap pipeline</label>
                        <select id="status" name="status" class="form-select">
                            @foreach($statusLabels as $key => $label)
                                @continue($key === 'new')
                                <option value="{{ $key }}" @selected($lead->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Perbarui Status</button>
                </form>

                @if(!empty($assignableUsers))
                <hr class="my-4">
                <form method="POST" action="{{ route('admin.leads.assign', $lead) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Ditangani oleh</label>
                        <select id="assigned_to" name="assigned_to" class="form-select">
                            <option value="">Belum ditugaskan</option>
                            @foreach($assignableUsers as $id => $name)
                                <option value="{{ $id }}" @selected($lead->assigned_to == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">Tugaskan</button>
                </form>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Detail Kontak</h5></div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt class="small text-body-secondary fw-normal">Email</dt>
                    <dd class="mb-3"><a href="mailto:{{ $lead->email }}" class="text-break">{{ $lead->email }}</a></dd>

                    <dt class="small text-body-secondary fw-normal">WhatsApp</dt>
                    <dd class="mb-3">
                        @if($waUrl)
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener">{{ $lead->whatsapp }}</a>
                        @else
                            <span class="text-body-secondary">—</span>
                        @endif
                    </dd>

                    <dt class="small text-body-secondary fw-normal">Perusahaan</dt>
                    <dd class="mb-3">{{ $lead->company ?: '—' }}</dd>

                    <dt class="small text-body-secondary fw-normal">Jenis Proyek</dt>
                    <dd class="mb-3">{{ $lead->project_type ?: '—' }}</dd>

                    <dt class="small text-body-secondary fw-normal">Perkiraan Anggaran</dt>
                    <dd class="mb-3">{{ $lead->budget_range ?: '—' }}</dd>

                    <dt class="small text-body-secondary fw-normal">Sumber</dt>
                    <dd class="mb-0">{{ $lead->source_label }}</dd>
                </dl>
            </div>
        </div>

        <x-admin.delete
            :action="route('admin.leads.destroy', $lead)"
            :confirm="'Hapus lead dari ' . $lead->name . '? Data ini tidak dapat dipulihkan.'"
            class="btn btn-outline-danger w-100"
            :icon="false">
            <i class="icon-base ti tabler-trash me-2"></i>Hapus Lead
        </x-admin.delete>
    </div>
</div>

@endsection
