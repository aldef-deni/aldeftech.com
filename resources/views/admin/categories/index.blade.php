@extends('layouts.layoutMaster')

@section('title', 'Kategori')

@section('content')

<x-admin.page-head
    eyebrow="Blog"
    title="Kategori"
    subtitle="{{ $categories->count() }} kategori artikel">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Kategori
    </a>
</x-admin.page-head>

<div class="card">
    @if($categories->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-category"
                title="Belum ada kategori"
                message="Kategori membantu pembaca menemukan artikel yang relevan.">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Kategori
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th class="d-none d-md-table-cell">Slug</th>
                    <th class="text-center">Artikel</th>
                    <th class="text-center">Urutan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="fw-medium text-body">{{ $category->name }}</a>
                        @if($category->description)
                        <small class="text-body-secondary d-block text-truncate" style="max-width: 28rem;">{{ $category->description }}</small>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell"><code class="text-body-secondary">{{ $category->slug }}</code></td>
                    <td class="text-center"><span class="badge bg-label-secondary">{{ $category->posts_count ?? 0 }}</span></td>
                    <td class="text-center">{{ $category->sort_order }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.categories.destroy', $category)"
                            :confirm="'Hapus kategori \'' . $category->name . '\'?'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
