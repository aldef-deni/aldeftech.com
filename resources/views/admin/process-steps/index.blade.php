@extends('layouts.layoutMaster')

@section('title', 'Alur Kerja')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Alur Kerja"
    subtitle="{{ $steps->count() }} tahap · ditampilkan di beranda sebagai metode kerja">
    <a href="{{ route('admin.process-steps.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Tahap
    </a>
</x-admin.page-head>

@if($steps->isEmpty())
<div class="card">
    <div class="card-body">
        <x-admin.empty
            icon="tabler-timeline-event"
            title="Belum ada tahap"
            message="Jelaskan alur kerja Anda agar calon klien tahu apa yang akan terjadi setelah menghubungi.">
            <a href="{{ route('admin.process-steps.create') }}" class="btn btn-primary">
                <i class="icon-base ti tabler-plus me-2"></i>Tambah Tahap
            </a>
        </x-admin.empty>
    </div>
</div>
@else
<div class="row g-4">
    @foreach($steps as $step)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <span class="aldef-numeral fs-2">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</span>
                    <x-admin.status :published="$step->is_published" />
                </div>

                <h6 class="mb-2">{{ $step->title }}</h6>
                <p class="text-body-secondary mb-0">{{ $step->description }}</p>
            </div>

            <div class="card-footer d-flex align-items-center justify-content-between">
                <small class="text-body-secondary">{{ $step->icon ?: '—' }}</small>
                <div class="text-nowrap">
                    <a href="{{ route('admin.process-steps.edit', $step) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                        <i class="icon-base ti tabler-pencil"></i>
                    </a>
                    <x-admin.delete
                        :action="route('admin.process-steps.destroy', $step)"
                        :confirm="'Hapus tahap \'' . $step->title . '\'?'" />
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
