@extends('layouts.layoutMaster')

@section('title', 'Tambah Pertanyaan')

@section('content')

<form method="POST" action="{{ route('admin.faq.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="FAQ"
        title="Tambah Pertanyaan"
        :back="route('admin.faq.index')">
        <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.faq._form')
</form>

@endsection
