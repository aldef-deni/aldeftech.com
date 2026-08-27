@extends('layouts.layoutMaster')

@section('title', 'Media')

@php
    $maxMb = round(config('aldeftech.upload.max_size', 5120) / 1024, 1);
    $allowed = implode(', ', config('aldeftech.upload.allowed_mimes', []));
@endphp

@section('content')

<x-admin.page-head
    eyebrow="Pengaturan"
    title="Media"
    subtitle="{{ $media->total() }} berkas · maksimal {{ $maxMb }} MB per berkas">
    <label class="btn btn-primary mb-0">
        <i class="icon-base ti tabler-upload me-2"></i>Unggah Berkas
        <input type="file" form="mediaUploadForm" name="file" class="d-none"
               accept="image/*" onchange="this.form.submit()">
    </label>
</x-admin.page-head>

<form id="mediaUploadForm" method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" class="d-none">
    @csrf
</form>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.media.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <label for="search" class="form-label">Cari berkas</label>
                <input type="search" id="search" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Nama berkas">
            </div>
            <div class="col-8 col-md-4">
                <label for="type" class="form-label">Jenis</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Semua jenis</option>
                    <option value="image" @selected(request('type') === 'image')>Gambar</option>
                    <option value="application" @selected(request('type') === 'application')>Dokumen</option>
                    <option value="video" @selected(request('type') === 'video')>Video</option>
                </select>
            </div>
            <div class="col-4 col-md-2">
                <button type="submit" class="btn btn-primary w-100">Terapkan</button>
            </div>
        </form>
    </div>
</div>

@if($media->isEmpty())
<div class="card">
    <div class="card-body">
        <x-admin.empty
            icon="tabler-photo"
            title="Belum ada berkas"
            message="Unggah gambar di sini, lalu salin path-nya ke kolom gambar pada portofolio atau artikel. Format yang diterima: {{ $allowed }}." />
    </div>
</div>
@else
<div class="row g-4">
    @foreach($media as $item)
    <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
        <div class="card h-100">
            <div class="ratio ratio-4x3 bg-body-secondary rounded-top overflow-hidden">
                @if(str_starts_with((string) $item->mime_type, 'image/'))
                    <img src="{{ media_url($item->path) }}" alt="{{ $item->original_name }}"
                         class="w-100 h-100" style="object-fit: cover;" loading="lazy">
                @else
                    <span class="d-flex align-items-center justify-content-center text-body-secondary">
                        <i class="icon-base ti tabler-file icon-lg"></i>
                    </span>
                @endif
            </div>

            <div class="card-body p-3">
                <p class="mb-1 small fw-medium text-truncate" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
                <small class="text-body-secondary d-block">{{ number_format($item->size / 1024, 0, ',', '.') }} KB</small>

                <div class="input-group input-group-sm mt-2">
                    <input type="text" class="form-control font-monospace" readonly
                           value="{{ $item->path }}" aria-label="Path berkas"
                           onclick="this.select(); navigator.clipboard && navigator.clipboard.writeText(this.value);">
                    <x-admin.delete
                        :action="route('admin.media.destroy', $item)"
                        :confirm="'Hapus ' . $item->original_name . '? Berkas ini mungkin masih dipakai di halaman lain.'"
                        class="btn btn-outline-danger btn-sm" />
                </div>
                <small class="text-body-secondary">Klik path untuk menyalin.</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($media->hasPages())
<div class="mt-4">{{ $media->links() }}</div>
@endif
@endif

@endsection
