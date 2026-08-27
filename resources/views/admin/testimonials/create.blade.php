@extends('layouts.layoutMaster')

@section('title', 'Tambah Testimoni')

@section('content')

<form method="POST" action="{{ route('admin.testimonials.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Testimoni"
        title="Tambah Testimoni"
        :back="route('admin.testimonials.index')">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.testimonials._form')
</form>

@endsection
