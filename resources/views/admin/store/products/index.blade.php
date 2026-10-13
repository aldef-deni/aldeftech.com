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
            <div class="col-12 col-md-4">
                <label class="form-label" for="q">Cari</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Nama, kode produk, atau brand">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label" for="f-brand">Brand</label>
                <select id="f-brand" name="brand" class="form-select">
                    <option value="">Semua brand</option>
                    @foreach($brandOptions as $id => $name)
                        <option value="{{ $id }}" @selected($filters['brand'] === $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label" for="f-kategori">Kategori</label>
                <select id="f-kategori" name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach($categoryOptions as $id => $name)
                        <option value="{{ $id }}" @selected($filters['kategori'] === $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-8 col-md-2">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="published" @selected($filters['status'] === 'published')>Terbit</option>
                    <option value="draft" @selected($filters['status'] === 'draft')>Draf</option>
                </select>
            </div>
            <div class="col-4 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary"><i class="icon-base ti tabler-search"></i></button>
                @if($filters['q'] !== '' || $filters['status'] !== '' || $filters['brand'] || $filters['kategori'])
                    <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-shoe"
                title="{{ $filters['q'] !== '' || $filters['status'] !== '' || $filters['brand'] || $filters['kategori'] ? 'Tidak ada produk yang cocok' : 'Belum ada produk' }}"
                message="Produk berstatus Draf tidak tampil di situs. Ubah ke Terbit saat foto dan varian sudah lengkap.">
                <a href="{{ route('admin.store.products.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Produk
                </a>
            </x-admin.empty>
        </div>
    @else
    {{-- Bulk actions: the row checkboxes belong to this form through form="",
         so they can sit inside the table next to each row's own delete form. --}}
    <form method="POST" action="{{ route('admin.store.products.bulk') }}" id="bulk-form"
          class="card-body border-bottom d-flex flex-wrap align-items-center gap-2 py-3">
        @csrf
        <span class="text-body-secondary small me-2"><span data-bulk-count>0</span> dipilih</span>
        <button type="submit" name="action" value="publish" class="btn btn-sm btn-success" data-bulk-button disabled>
            <i class="icon-base ti tabler-world-upload me-1"></i>Terbitkan
        </button>
        <button type="submit" name="action" value="draft" class="btn btn-sm btn-outline-secondary" data-bulk-button disabled>
            Jadikan Draf
        </button>
        @error('ids')<span class="text-danger small">{{ $message }}</span>@enderror
    </form>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:2.5rem"><input type="checkbox" class="form-check-input" data-bulk-all aria-label="Pilih semua di halaman ini"></th>
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
                    <td><input type="checkbox" class="form-check-input" name="ids[]" value="{{ $product->id }}" form="bulk-form" data-bulk-item aria-label="Pilih {{ $product->name }}"></td>
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
                                <small class="text-body-secondary">{{ $product->sku }}@if($product->brand) · {{ $product->brand->name }}@endif</small>
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

@section('page-script')
<script>
  // Select-all and the selected count for the bulk publish bar.
  document.addEventListener('DOMContentLoaded', function () {
    const all = document.querySelector('[data-bulk-all]');
    const items = Array.from(document.querySelectorAll('[data-bulk-item]'));
    const buttons = document.querySelectorAll('[data-bulk-button]');
    const count = document.querySelector('[data-bulk-count]');
    if (!all || !items.length) return;
    const refresh = function () {
      const picked = items.filter((b) => b.checked).length;
      count.textContent = picked;
      buttons.forEach((b) => { b.disabled = picked === 0; });
      all.checked = picked === items.length;
      all.indeterminate = picked > 0 && picked < items.length;
    };
    all.addEventListener('change', function () { items.forEach((b) => { b.checked = all.checked; }); refresh(); });
    items.forEach((b) => b.addEventListener('change', refresh));
    refresh();
  });
</script>
@endsection
