@extends('layouts.layoutMaster')

@section('title', 'Ubah Layanan')

@section('content')

<form method="POST" action="{{ route('admin.services.update', $service) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Layanan"
        :title="$service->title"
        subtitle="Terakhir diubah {{ $service->updated_at?->diffForHumans() }}"
        :back="route('admin.services.index')">
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    @include('admin.services._form')
</form>

@endsection
