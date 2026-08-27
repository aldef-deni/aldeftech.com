@extends('layouts.layoutMaster')

@section('title', 'Tambah Kategori')

@section('content')

<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Kategori"
        title="Tambah Kategori"
        :back="route('admin.categories.index')">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    <div class="row">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input label="Nama" name="name" required placeholder="mis. Kecerdasan Buatan" />
                    <x-admin.form.textarea
                        label="Deskripsi" name="description" :rows="3"
                        placeholder="Penjelasan singkat tentang kategori ini"
                        help="Opsional. Slug dibuat otomatis dari nama." />
                    <x-admin.form.input label="Urutan" name="sort_order" type="number" :value="0" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
