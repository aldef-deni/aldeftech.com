@extends('layouts.layoutMaster')

@section('title', 'Ubah Klien')

@section('content')

<form method="POST" action="{{ route('admin.clients.update', $client) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Klien Aldef Tech"
        :title="$client->name"
        subtitle="Terakhir diubah {{ $client->updated_at?->diffForHumans() }}"
        :back="route('admin.clients.index')">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    @include('admin.clients._form')
</form>

@endsection
