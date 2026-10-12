@extends('layouts.layoutMaster')

@section('title', 'Ubah ' . $product->name)

@section('content')

<form method="POST" action="{{ route('admin.store.products.update', $product) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Aldef Store · {{ $product->sku }}"
        :title="$product->name"
        :back="route('admin.store.products.index')">
        @if($product->isPublished())
            <a href="{{ route('store.show', $product->slug) }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
                <i class="icon-base ti tabler-external-link me-2"></i>Lihat di situs
            </a>
        @endif
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
