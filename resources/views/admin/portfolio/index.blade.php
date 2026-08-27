@extends('layouts.layoutMaster')

@section('title', 'Portofolio')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Portofolio"
    subtitle="{{ $portfolios->count() }} proyek · {{ $portfolios->where('is_featured', true)->count() }} tampil di beranda">
    <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Proyek
    </a>
</x-admin.page-head>

<div class="card">
    @if($portfolios->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-briefcase"
                title="Belum ada proyek"
                message="Portofolio adalah bukti terkuat yang Anda punya. Tambahkan proyek pertama.">
                <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Proyek
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Proyek</th>
                    <th class="d-none d-lg-table-cell">Kategori</th>
                    <th class="d-none d-md-table-cell">Klien</th>
                    <th class="text-center">Beranda</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($portfolios as $portfolio)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($src = media_url($portfolio->featured_image))
                                <img src="{{ $src }}" alt="" class="aldef-thumb">
                            @else
                                <span class="aldef-thumb d-inline-flex align-items-center justify-content-center text-body-secondary">
                                    <i class="icon-base ti tabler-photo"></i>
                                </span>
                            @endif
                            <div class="text-truncate">
                                <a href="{{ route('admin.portfolio.edit', $portfolio) }}" class="fw-medium text-body d-block text-truncate">{{ $portfolio->title }}</a>
                                <small class="text-body-secondary d-block text-truncate" style="max-width: 22rem;">{{ $portfolio->short_description }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-label-secondary">{{ $portfolio->category->name ?? '—' }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <small class="text-body-secondary">{{ $portfolio->client ?: '—' }}</small>
                    </td>
                    <td class="text-center">
                        @if($portfolio->is_featured)
                            <i class="icon-base ti tabler-star-filled text-warning" title="Tampil di beranda"></i>
                        @else
                            <span class="text-body-secondary">—</span>
                        @endif
                    </td>
                    <td class="text-center"><x-admin.status :published="$portfolio->is_published" /></td>
                    <td class="text-end text-nowrap">
                        @if($portfolio->is_published && $portfolio->slug)
                        <a href="{{ route('portfolio.show', $portfolio->slug) }}" target="_blank" rel="noopener"
                           class="btn btn-sm btn-icon btn-text-secondary" title="Lihat di situs">
                            <i class="icon-base ti tabler-external-link"></i>
                        </a>
                        @endif
                        <a href="{{ route('admin.portfolio.edit', $portfolio) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.portfolio.destroy', $portfolio)"
                            :confirm="'Hapus proyek \'' . $portfolio->title . '\'?'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
