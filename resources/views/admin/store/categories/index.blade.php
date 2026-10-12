@extends('layouts.layoutMaster')

@section('title', 'Kategori Sepatu')

@section('content')

<x-admin.page-head
    eyebrow="Aldef Store"
    title="Kategori Sepatu"
    subtitle="Dipakai sebagai filter di halaman katalog">
    <a href="{{ route('admin.store.categories.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Kategori
    </a>
</x-admin.page-head>

<div class="card">
    @if($categories->isEmpty())
        <div class="card-body">
            <x-admin.empty icon="tabler-category-2" title="Belum ada kategori"
                message="Contoh: Sepatu Lari, Sneakers, Sepatu Formal.">
                <a href="{{ route('admin.store.categories.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Kategori
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th class="d-none d-md-table-cell">Slug</th>
                    <th class="text-center">Produk</th>
                    <th class="text-center d-none d-md-table-cell">Urutan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>
                        <a href="{{ route('admin.store.categories.edit', $category) }}" class="fw-medium text-body">{{ $category->name }}</a>
                        @if($category->description)<small class="text-body-secondary d-block text-truncate" style="max-width: 24rem;">{{ $category->description }}</small>@endif
                    </td>
                    <td class="d-none d-md-table-cell"><code>{{ $category->slug }}</code></td>
                    <td class="text-center">{{ $category->products_count }}</td>
                    <td class="text-center d-none d-md-table-cell">{{ $category->sort_order }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.store.categories.edit', $category) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.store.categories.destroy', $category)"
                            :confirm="'Hapus kategori \'' . $category->name . '\'? Produk di dalamnya tetap ada, hanya tanpa kategori.'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
