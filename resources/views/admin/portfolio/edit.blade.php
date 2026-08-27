@extends('layouts.layoutMaster')

@section('title', 'Ubah Proyek')

@section('content')

<form method="POST" action="{{ route('admin.portfolio.update', $portfolio) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Portofolio"
        :title="$portfolio->title"
        subtitle="Terakhir diubah {{ $portfolio->updated_at?->diffForHumans() }}"
        :back="route('admin.portfolio.index')">
        @if($portfolio->is_published && $portfolio->slug)
        <a href="{{ route('portfolio.show', $portfolio->slug) }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-external-link me-2"></i>Lihat
        </a>
        @endif
        <a href="{{ route('admin.portfolio.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    @include('admin.portfolio._form')
</form>

@endsection
