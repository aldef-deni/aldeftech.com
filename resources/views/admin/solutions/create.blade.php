@extends('layouts.layoutMaster')

@section('title', 'Tambah Solusi')

@section('content')

<form method="POST" action="{{ route('admin.solutions.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Solusi"
        title="Tambah Solusi"
        :back="route('admin.solutions.index')">
        <a href="{{ route('admin.solutions.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.solutions._form')
</form>

@endsection
