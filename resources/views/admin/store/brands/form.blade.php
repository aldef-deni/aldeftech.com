@extends('layouts.layoutMaster')

@section('title', $brand ? 'Ubah Brand' : 'Tambah Brand')

@section('content')

<form method="POST" action="{{ $brand ? route('admin.store.brands.update', $brand) : route('admin.store.brands.store') }}">
    @csrf
    @if($brand) @method('PUT') @endif

    <x-admin.page-head
        eyebrow="Aldef Store · Sepatu"
        :title="$brand ? 'Ubah Brand' : 'Tambah Brand'"
        :back="route('admin.store.brands.index')">
        <a href="{{ route('admin.store.brands.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input label="Nama Brand" name="name" id="name" :value="$brand->name ?? ''" required placeholder="mis. Nike"
                        help="Import ZIP mencocokkan nama folder dengan nama ini (huruf besar/kecil tidak berpengaruh)." />
                    <x-admin.form.input label="Slug" name="slug" :value="$brand->slug ?? ''" data-slug-from="#name"
                        placeholder="nike" help="Dipakai pada filter katalog, mis. /store?brand=nike. Dibuat otomatis bila dikosongkan." />
                    <x-admin.form.textarea label="Deskripsi (opsional)" name="description" :value="$brand->description ?? ''" :rows="3" />
                    <x-admin.form.input label="Urutan" name="sort_order" type="number" min="0" :value="$brand->sort_order ?? 0" />
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Logo (opsional)</h5></div>
                <div class="card-body">
                    <x-admin.form.image label="Logo" name="logo" :value="$brand->logo ?? ''" ratio="1 / 1" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
