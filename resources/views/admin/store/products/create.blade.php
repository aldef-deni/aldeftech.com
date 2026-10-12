@extends('layouts.layoutMaster')

@section('title', 'Tambah Produk Sepatu')

@section('content')

<form method="POST" action="{{ route('admin.store.products.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Aldef Store"
        title="Tambah Produk Sepatu"
        :back="route('admin.store.products.index')">
        <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.store.products._form')
</form>

@endsection

@section('page-script')
    @vite(['resources/assets/js/aldef-store-admin.js'])
@endsection
