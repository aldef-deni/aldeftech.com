@extends('layouts.layoutMaster')

@section('title', 'Produk Sepatu')

@section('content')

<x-admin.page-head
    eyebrow="Aldef Store"
    title="Produk Sepatu"
    subtitle="{{ $products->total() }} produk · katalog tanpa harga, pengunjung bertanya via WhatsApp">
    <a href="{{ route('admin.store.products.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Produk
    </a>
</x-admin.page-head>

@unless($waNumber)
    <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
        <i class="icon-base ti tabler-alert-triangle mt-1"></i>
        <div>
            Nomor WhatsApp katalog belum diatur, jadi tombol &ldquo;Tanya Harga via WhatsApp&rdquo; di situs belum aktif.
            <a href="{{ route('admin.store.settings') }}" class="alert-link">Atur nomor sekarang</a>.
        </div>
    </div>
@endunless

<div class="card">
    <div class="card-body border-bottom">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <label class="form-label" for="q">Cari</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Nama, kode produk, atau merek">
            </div>
            <div class="col-8 col-md-3">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="published" @selected($filters['status'] === 'published')>Terbit</option>
                    <option value="draft" @selected($filters['status'] === 'draft')>Draf</option>
                </select>
            </div>
            <div class="col-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary"><i class="icon-base ti tabler-search"></i></button>
                @if($filters['q'] !== '' || $filters['status'] !== '')
                    <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-shoe"
                title="{{ $filters['q'] !== '' || $filters['status'] !== '' ? 'Tidak ada produk yang cocok' : 'Belum ada produk' }}"
                message="Produk berstatus Draf tidak tampil di situs. Ubah ke Terbit saat foto dan varian sudah lengkap.">
                <a href="{{ route('admin.store.products.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Produk
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="d-none d-lg-table-cell">Kategori</th>
                    <th class="d-none d-md-table-cell">Varian tersedia</th>
                    <th class="text-center">Unggulan</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                @php
                    $photo = $product->primaryImage();
                    $total = $product->variants->count();
                    $inStock = $product->variants->where('is_available', true)->count();
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($photo && $photo->url)
                                <img src="{{ $photo->url }}" alt="" class="aldef-thumb" style="object-fit: contain; background: #fff;">
                            @else
                                <span class="aldef-thumb d-inline-flex align-items-center justify-content-center text-body-secondary">
                                    <i class="icon-base ti tabler-shoe"></i>
                                </span>
                            @endif
                            <div class="text-truncate">
                                <a href="{{ route('admin.store.products.edit', $product) }}" class="fw-medium text-body d-block text-truncate">{{ $product->name }}</a>
                                <small class="text-body-secondary">{{ $product->sku }}@if($product->brand) · {{ $product->brand }}@endif</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-label-secondary">{{ $product->category?->label() ?? '—' }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($total === 0)
                            <small class="text-body-secondary">Belum diatur</small>
                        @else
                            <small class="{{ $inStock === 0 ? 'text-danger' : 'text-body-secondary' }}">{{ $inStock }} / {{ $total }} kombinasi</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($product->is_featured)
                            <i class="icon-base ti tabler-star-filled text-warning" title="Produk unggulan"></i>
                        @else
                            <span class="text-body-secondary">—</span>
                        @endif
                    </td>
                    <td class="text-center"><x-admin.status :published="$product->isPublished()" /></td>
                    <td class="text-end text-nowrap">
                        @if($product->isPublished())
                        <a href="{{ route('store.show', $product->slug) }}" target="_blank" rel="noopener"
                           class="btn btn-sm btn-icon btn-text-secondary" title="Lihat di situs">
                            <i class="icon-base ti tabler-external-link"></i>
                        </a>
                        @endif
                        <a href="{{ route('admin.store.products.edit', $product) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.store.products.destroy', $product)"
                            :confirm="'Hapus produk \'' . $product->name . '\'? Kode ' . $product->sku . ' tidak akan dipakai ulang.'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="card-body border-top">{{ $products->links() }}</div>
    @endif
    @endif
</div>

@endsection
