@extends('layouts.layoutMaster')

@section('title', 'Klien Aldef Tech')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Klien Aldef Tech"
    subtitle="Kelola logo dan identitas klien yang ditampilkan pada website AldefTech. {{ $clients->count() }} klien · {{ $onHome }} tampil di beranda · {{ $onAbout }} tampil di halaman Tentang">
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Klien
    </a>
</x-admin.page-head>

@if($clients->isEmpty())
<div class="card">
    <div class="card-body">
        <x-admin.empty
            icon="tabler-building-store"
            title="Belum ada Klien Aldef Tech."
            message="Logo klien yang aktif akan tampil pada marquee beranda dan grid halaman Tentang.">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
                <i class="icon-base ti tabler-plus me-2"></i>Tambah Klien
            </a>
        </x-admin.empty>
    </div>
</div>
@else
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Nama Klien</th>
                    <th class="d-none d-lg-table-cell">Website</th>
                    <th class="d-none d-md-table-cell text-center">Home</th>
                    <th class="d-none d-md-table-cell text-center">About</th>
                    <th class="text-center">Featured</th>
                    <th class="text-center">Status</th>
                    <th class="d-none d-lg-table-cell text-center">Urutan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>
                        {{-- The logo sits on an obsidian plate: most client marks are
                             light artwork and would vanish on the table's white. --}}
                        @if($src = media_url($client->logo))
                            <img src="{{ $src }}" alt="{{ $client->name }}"
                                 style="width: 4.75rem; height: 2.75rem; object-fit: contain; padding: 0.25rem; border-radius: 0.5rem; background: #0b0d11; border: 1px solid rgba(13, 20, 32, 0.12);">
                        @else
                            <span class="badge bg-label-secondary">Tanpa logo</span>
                        @endif
                    </td>
                    <td style="min-width: 13rem;">
                        <a href="{{ route('admin.clients.edit', $client) }}" class="fw-medium text-body d-block">{{ $client->name }}</a>
                        @if($client->is_featured)
                        <small class="text-body-secondary">Ditampilkan lebih dulu</small>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if($client->website())
                            <a href="{{ $client->website() }}" target="_blank" rel="noopener noreferrer"
                               class="text-body d-inline-flex align-items-center gap-1">
                                {{ $client->websiteHost() }}
                                <i class="icon-base ti tabler-external-link icon-sm"></i>
                            </a>
                        @else
                            <small class="text-body-secondary">—</small>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell text-center">
                        @if($client->show_home)
                            <i class="icon-base ti tabler-check text-success" aria-label="Ya"></i>
                        @else
                            <span class="text-body-secondary" aria-label="Tidak">—</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell text-center">
                        @if($client->show_about)
                            <i class="icon-base ti tabler-check text-success" aria-label="Ya"></i>
                        @else
                            <span class="text-body-secondary" aria-label="Tidak">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($client->is_featured)
                            <span class="badge bg-label-warning"><i class="icon-base ti tabler-star-filled icon-sm"></i></span>
                        @else
                            <span class="text-body-secondary">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <x-admin.status :published="$client->isActive()" :labels="['Active', 'Inactive']" />
                    </td>
                    <td class="d-none d-lg-table-cell text-center">{{ $client->sort_order }}</td>
                    <td class="text-end text-nowrap">
                        <form method="POST" action="{{ route('admin.clients.toggle-active', $client) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-icon btn-text-secondary"
                                    title="{{ $client->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="icon-base ti {{ $client->isActive() ? 'tabler-eye-off' : 'tabler-eye' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.clients.destroy', $client)"
                            :confirm="'Hapus klien ' . $client->name . '?'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
