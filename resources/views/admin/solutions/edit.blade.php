@extends('layouts.layoutMaster')

@section('title', 'Ubah Solusi')

@section('content')

<form method="POST" action="{{ route('admin.solutions.update', $solution) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Solusi"
        :title="$solution->title"
        subtitle="Terakhir diubah {{ $solution->updated_at?->diffForHumans() }}"
        :back="route('admin.solutions.index')">
        <a href="{{ route('admin.solutions.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    @include('admin.solutions._form')
</form>

@endsection
