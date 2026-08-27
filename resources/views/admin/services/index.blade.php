@extends('layouts.layoutMaster')

@section('title', 'Layanan')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Layanan"
    subtitle="{{ $services->count() }} layanan · {{ $services->where('is_published', true)->count() }} tampil di situs">
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Layanan
    </a>
</x-admin.page-head>

<div class="card">
    @if($services->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-layout-grid-add"
                title="Belum ada layanan"
                message="Tambahkan layanan pertama agar muncul di beranda dan halaman Layanan.">
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Layanan
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="d-none d-lg-table-cell">Poin</th>
                    <th class="text-center">Urutan</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-label-primary rounded p-2 lh-1">{{ $service->icon ?: '•' }}</span>
                            <div class="text-truncate">
                                <a href="{{ route('admin.services.edit', $service) }}" class="fw-medium text-body d-block text-truncate">{{ $service->title }}</a>
                                <small class="text-body-secondary d-block text-truncate" style="max-width: 26rem;">{{ $service->short_description }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-label-secondary">{{ count((array) $service->features) }}</span>
                    </td>
                    <td class="text-center">{{ $service->sort_order }}</td>
                    <td class="text-center"><x-admin.status :published="$service->is_published" /></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.services.destroy', $service)"
                            :confirm="'Hapus layanan \'' . $service->title . '\'? Tindakan ini tidak dapat dibatalkan.'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
