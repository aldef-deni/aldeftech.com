@extends('layouts.layoutMaster')

@section('title', $category ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')

<form method="POST" action="{{ $category ? route('admin.store.categories.update', $category) : route('admin.store.categories.store') }}">
    @csrf
    @if($category) @method('PUT') @endif

    <x-admin.page-head
        eyebrow="Aldef Store"
        :title="$category ? 'Ubah Kategori' : 'Tambah Kategori'"
        :back="route('admin.store.categories.index')">
        <a href="{{ route('admin.store.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input label="Nama Kategori" name="name" id="name" :value="$category->name ?? ''" required placeholder="mis. Sepatu Lari" />
                    <x-admin.form.input label="Slug" name="slug" :value="$category->slug ?? ''" data-slug-from="#name"
                        placeholder="sepatu-lari" help="Dipakai pada filter katalog, mis. /store?kategori=sepatu-lari. Dibuat otomatis bila dikosongkan." />
                    <x-admin.form.textarea label="Deskripsi (opsional)" name="description" :value="$category->description ?? ''" :rows="3" />
                    <x-admin.form.input label="Urutan" name="sort_order" type="number" min="0" :value="$category->sort_order ?? 0" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
