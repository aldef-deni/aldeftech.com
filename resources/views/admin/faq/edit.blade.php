@extends('layouts.layoutMaster')

@section('title', 'Ubah Pertanyaan')

@section('content')

<form method="POST" action="{{ route('admin.faq.update', $faq) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="FAQ"
        title="Ubah Pertanyaan"
        :subtitle="excerpt_text($faq->question, 90)"
        :back="route('admin.faq.index')">
        <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    @include('admin.faq._form')
</form>

@endsection
