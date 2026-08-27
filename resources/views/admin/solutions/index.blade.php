@extends('layouts.layoutMaster')

@section('title', 'Solusi')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Solusi"
    subtitle="{{ $solutions->count() }} solusi · {{ $solutions->where('is_published', true)->count() }} tampil di situs">
    <a href="{{ route('admin.solutions.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Solusi
    </a>
</x-admin.page-head>

<div class="card">
    @if($solutions->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-bulb"
                title="Belum ada solusi"
                message="Tambahkan solusi siap-pakai yang bisa disesuaikan untuk klien.">
                <a href="{{ route('admin.solutions.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Solusi
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Solusi</th>
                    <th class="d-none d-lg-table-cell">Modul</th>
                    <th class="text-center">Urutan</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solutions as $solution)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-label-primary rounded p-2 lh-1">{{ $solution->icon ?: '•' }}</span>
                            <div class="text-truncate">
                                <a href="{{ route('admin.solutions.edit', $solution) }}" class="fw-medium text-body d-block text-truncate">{{ $solution->title }}</a>
                                <small class="text-body-secondary d-block text-truncate" style="max-width: 26rem;">{{ $solution->short_description }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-label-secondary">{{ count((array) $solution->features) }}</span>
                    </td>
                    <td class="text-center">{{ $solution->sort_order }}</td>
                    <td class="text-center"><x-admin.status :published="$solution->is_published" /></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.solutions.edit', $solution) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.solutions.destroy', $solution)"
                            :confirm="'Hapus solusi \'' . $solution->title . '\'?'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
