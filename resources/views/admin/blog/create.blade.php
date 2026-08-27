@extends('layouts.layoutMaster')

@section('title', 'Tulis Artikel')

@section('content')

<form method="POST" action="{{ route('admin.blog.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Blog"
        title="Tulis Artikel"
        :back="route('admin.blog.index')">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    @include('admin.blog._form')
</form>

@endsection
