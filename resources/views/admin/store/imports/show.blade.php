@extends('layouts.layoutMaster')

@section('title', 'Import ' . $import->original_name)

@section('content')

@php
    $plan = collect($import->plan ?? []);
    $summary = $import->summary ?? [];
    $resultBadge = fn ($entry) => match ($entry['result'] ?? $entry['state']) {
        'created' => ['bg-label-success', 'Dibuat'],
        'new' => ['bg-label-primary', 'Akan dibuat'],
        'skip', 'skipped' => ['bg-label-secondary', 'Dilewati'],
        default => ['bg-label-danger', 'Bermasalah'],
    };
@endphp

<x-admin.page-head
    eyebrow="Aldef Store · Import ZIP"
    :title="$import->original_name"
    :subtitle="$import->statusLabel() . ' · ' . format_upload_mb($import->size) . ' · ' . $import->created_at->format('d M Y H:i')"
    :back="route('admin.store.imports.index')">
    @if($import->isActive())
        <x-admin.delete :action="route('admin.store.imports.destroy', $import)" label="Batalkan import"
            class="btn btn-outline-danger" :icon="false"
            confirm="Batalkan import ini? Berkas sementara akan dihapus dan tidak ada produk yang dibuat dari langkah yang belum berjalan.">Batalkan</x-admin.delete>
    @endif
</x-admin.page-head>

@if($import->status === 'failed')
    <div class="alert alert-danger" role="alert">{{ $import->error ?: 'Import gagal.' }}</div>
@endif

{{-- Long-running phases continue here if the upload page was left. --}}
@if(in_array($import->status, ['extracting', 'importing'], true) || $import->status === 'ready')
<div class="card mb-4"
     data-import-runner
     data-status="{{ $import->status }}"
     data-extract-url="{{ route('admin.store.imports.extract', $import) }}"
     data-run-url="{{ route('admin.store.imports.run', $import) }}">
    <div class="card-body">
        @if($import->status === 'ready')
            <div class="d-flex flex-wrap align-items-center gap-3" data-runner-start-box>
                <div class="me-auto">
                    <h5 class="mb-1">Pratinjau siap</h5>
                    <p class="mb-0 text-body-secondary">
                        {{ $counts['new'] }} produk akan dibuat sebagai Draf
                        @if($counts['skip']) · {{ $counts['skip'] }} dilewati (sudah ada) @endif
                        @if($counts['error']) · {{ $counts['error'] }} bermasalah (tidak diimpor) @endif
                    </p>
                </div>
                <button type="button" class="btn btn-primary" data-runner-start @disabled($counts['new'] === 0)>
                    <i class="icon-base ti tabler-player-play me-2"></i>Mulai Import
                </button>
            </div>
        @endif
        <div data-runner-progress @if($import->status === 'ready') hidden @endif>
            <div class="d-flex justify-content-between small mb-1">
                <span data-runner-stage>{{ $import->status === 'extracting' ? 'Mengekstrak ZIP…' : 'Membuat produk…' }}</span>
                <span data-runner-percent>{{ $import->total ? floor($import->cursor / $import->total * 100) : 0 }}%</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                     style="width: {{ $import->total ? floor($import->cursor / $import->total * 100) : 0 }}%" data-runner-bar></div>
            </div>
            <small class="text-body-secondary d-block mt-2">Biarkan halaman ini terbuka sampai selesai.</small>
        </div>
        <div class="alert alert-danger mt-3 mb-0" role="alert" data-runner-error hidden></div>
    </div>
</div>
@endif

@if($import->status === 'done')
    <div class="alert alert-success d-flex flex-wrap align-items-center gap-2" role="alert">
        <span class="me-auto">
            Import selesai: <strong>{{ $counts['created'] }}</strong> produk dibuat sebagai Draf
            @if($plan->where('result', 'skipped')->count()) · {{ $plan->where('result', 'skipped')->count() }} dilewati @endif
            @if($plan->where('result', 'failed')->count()) · {{ $plan->where('result', 'failed')->count() }} gagal/bermasalah @endif
        </span>
        <a href="{{ route('admin.store.products.index', ['status' => 'draft']) }}" class="btn btn-sm btn-success">Lihat &amp; terbitkan produk</a>
    </div>
@endif

@if(! empty($summary['wrapper_candidate']) && $import->status === 'ready')
    <form method="POST" action="{{ route('admin.store.imports.plan', $import) }}" class="alert alert-info d-flex flex-wrap align-items-center gap-3" role="alert">
        @csrf
        <input type="hidden" name="strip_wrapper" value="{{ $summary['wrapper'] ? 0 : 1 }}">
        <span class="me-auto">
            Seluruh isi ZIP berada di dalam folder <strong>{{ $summary['wrapper_candidate'] }}</strong>.
            {{ $summary['wrapper'] ? 'Folder ini diabaikan (dianggap folder pembungkus).' : 'Folder ini dipakai sebagai nama brand.' }}
        </span>
        <button type="submit" class="btn btn-sm btn-outline-primary">
            {{ $summary['wrapper'] ? 'Jadikan folder ini sebagai brand' : 'Abaikan folder ini (folder pembungkus)' }}
        </button>
    </form>
@endif

@if($plan->isNotEmpty())
<div class="row g-4 mb-4">
    @foreach([['Produk', $counts['products']], ['Foto', $counts['images']], ['Video', $counts['videos']], ['Berkas diabaikan', $summary['ignored'] ?? 0]] as [$label, $value])
        <div class="col-6 col-md-3">
            <div class="card"><div class="card-body py-3">
                <small class="text-body-secondary">{{ $label }}</small>
                <div class="fs-4 fw-semibold">{{ number_format($value, 0, ',', '.') }}</div>
            </div></div>
        </div>
    @endforeach
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title mb-0">{{ $import->status === 'done' ? 'Laporan' : 'Pratinjau' }} per produk</h5></div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Produk</th><th>Brand</th><th class="d-none d-md-table-cell">Kategori</th>
                    <th class="text-center">Foto</th><th class="text-center">Video</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plan as $entry)
                    @php [$badgeClass, $badgeText] = $resultBadge($entry); @endphp
                    <tr>
                        <td>
                            @if(! empty($entry['product_id']))
                                <a href="{{ route('admin.store.products.edit', $entry['product_id']) }}" class="fw-medium">{{ $entry['name'] ?? $entry['folder'] }}</a>
                                <small class="text-body-secondary d-block">{{ $entry['sku'] }}</small>
                            @else
                                <span class="fw-medium">{{ $entry['name'] ?? '—' }}</span>
                                <small class="text-body-secondary d-block text-break">{{ $entry['folder'] ?: '(folder utama ZIP)' }}</small>
                            @endif
                        </td>
                        <td>{{ $entry['brand'] ?? '—' }}</td>
                        <td class="d-none d-md-table-cell">
                            {{ $entry['category'] ?? '—' }}@if(! empty($entry['subcategory'])) <span class="text-body-secondary">›</span> {{ $entry['subcategory'] }}@endif
                        </td>
                        <td class="text-center">{{ count($entry['images'] ?? []) }}</td>
                        <td class="text-center">{{ count($entry['videos'] ?? []) }}</td>
                        <td>
                            <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            @if(! empty($entry['note']))<small class="text-body-secondary d-block">{{ $entry['note'] }}</small>@endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif(in_array($import->status, ['ready', 'done'], true))
    <div class="alert alert-warning">Tidak ada folder produk berisi foto atau video di dalam ZIP ini.</div>
@endif

@if(! empty($summary['notes']))
<div class="card">
    <div class="card-header"><h5 class="card-title mb-0">Berkas yang diabaikan</h5></div>
    <div class="card-body">
        <ul class="small mb-0 ps-3">
            @foreach($summary['notes'] as $note)<li class="text-break">{{ $note }}</li>@endforeach
        </ul>
        @if(($summary['ignored'] ?? 0) > count($summary['notes']))
            <small class="text-body-secondary">…dan {{ $summary['ignored'] - count($summary['notes']) }} lainnya.</small>
        @endif
    </div>
</div>
@endif

@endsection

@section('page-script')
    @vite(['resources/assets/js/aldef-store-import.js'])
@endsection
