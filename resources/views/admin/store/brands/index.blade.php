@extends('layouts.layoutMaster')

@section('title', 'Brand Sepatu')

@section('content')

<x-admin.page-head
    eyebrow="Aldef Store · Sepatu"
    title="Brand Sepatu"
    subtitle="Brand dipakai bersama kategori: satu brand bisa punya produk di banyak kategori">
    <a href="{{ route('admin.store.brands.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Brand
    </a>
</x-admin.page-head>

@if($unbranded > 0)
    <div class="alert alert-info" role="alert">
        {{ $unbranded }} produk belum punya brand.
        <a href="{{ route('admin.store.products.index') }}" class="alert-link">Lihat daftar produk</a>.
    </div>
@endif

<div class="card">
    @if($brands->isEmpty())
        <div class="card-body">
            <x-admin.empty icon="tabler-tag" title="Belum ada brand"
                message="Tambahkan brand di sini, atau biarkan import ZIP membuatnya otomatis dari nama folder.">
                <a href="{{ route('admin.store.brands.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Brand
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th class="d-none d-lg-table-cell">Dipakai di kategori</th>
                    <th class="text-center">Produk</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($brand->logo && ($logo = media_url($brand->logo)))
                                <img src="{{ $logo }}" alt="" class="aldef-thumb" style="object-fit: contain; background: #fff;">
                            @else
                                <span class="aldef-thumb d-inline-flex align-items-center justify-content-center text-body-secondary fw-semibold">{{ mb_strtoupper(mb_substr($brand->name, 0, 1)) }}</span>
                            @endif
                            <div>
                                <a href="{{ route('admin.store.brands.edit', $brand) }}" class="fw-medium text-body">{{ $brand->name }}</a>
                                <small class="text-body-secondary d-block"><code>{{ $brand->slug }}</code></small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @forelse(($usedIn[$brand->id] ?? collect())->take(4) as $label)
                            <span class="badge bg-label-secondary me-1">{{ $label }}</span>
                        @empty
                            <small class="text-body-secondary">—</small>
                        @endforelse
                        @if(($usedIn[$brand->id] ?? collect())->count() > 4)
                            <small class="text-body-secondary">+{{ $usedIn[$brand->id]->count() - 4 }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.store.products.index', ['brand' => $brand->id]) }}">{{ $brand->products_count }}</a>
                        <small class="text-body-secondary d-block">{{ $brand->published_count }} terbit</small>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.store.brands.edit', $brand) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.store.brands.destroy', $brand)"
                            :confirm="'Hapus brand \'' . $brand->name . '\'? Produknya tetap ada, hanya tanpa brand.'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
