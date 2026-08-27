@extends('layouts.layoutMaster')

@section('title', 'Tambah Layanan')

@section('content')

<form method="POST" action="{{ route('admin.services.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Layanan"
        title="Tambah Layanan"
        :back="route('admin.services.index')">
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.services._form')
</form>

@endsection
