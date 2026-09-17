@extends('layouts.layoutMaster')

@section('title', 'Tambah Klien')

@section('content')

<form method="POST" action="{{ route('admin.clients.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Klien Aldef Tech"
        title="Tambah Klien"
        :back="route('admin.clients.index')">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.clients._form')
</form>

@endsection
