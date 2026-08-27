@extends('layouts.layoutMaster')

@section('title', 'Ubah Kategori')

@section('content')

<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Kategori"
        :title="$category->name"
        :subtitle="'/' . $category->slug"
        :back="route('admin.categories.index')">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input label="Nama" name="name" :value="$category->name" required />
                    <x-admin.form.textarea
                        label="Deskripsi" name="description" :value="$category->description ?? ''" :rows="3"
                        help="Slug diperbarui otomatis mengikuti nama." />
                    <x-admin.form.input label="Urutan" name="sort_order" type="number" :value="$category->sort_order" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
