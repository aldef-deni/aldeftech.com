@extends('layouts.layoutMaster')

@section('title', 'Tambah Proyek')

@section('content')

<form method="POST" action="{{ route('admin.portfolio.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Portofolio"
        title="Tambah Proyek"
        :back="route('admin.portfolio.index')">
        <a href="{{ route('admin.portfolio.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.portfolio._form')
</form>

@endsection
