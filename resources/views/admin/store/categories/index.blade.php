@extends('layouts.layoutMaster')

@section('title', 'Kategori Sepatu')

@section('content')

<x-admin.page-head
    eyebrow="Aldef Store · Sepatu"
    title="Kategori Sepatu"
    subtitle="Kategori dan subkategori dipakai sebagai filter di halaman katalog">
    <a href="{{ route('admin.store.categories.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Kategori
    </a>
</x-admin.page-head>

<div class="card">
    @if($tree->isEmpty())
        <div class="card-body">
            <x-admin.empty icon="tabler-category-2" title="Belum ada kategori"
                message="Contoh: Sepatu Lari dengan subkategori Road dan Trail, atau Sneakers dengan subkategori Low dan High.">
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
                @foreach($tree as $category)
                    @include('admin.store.categories._row', ['category' => $category, 'child' => false])
                    @foreach($category->children as $child)
                        @include('admin.store.categories._row', ['category' => $child, 'child' => true])
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
