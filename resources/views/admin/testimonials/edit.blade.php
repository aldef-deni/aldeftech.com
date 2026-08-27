@extends('layouts.layoutMaster')

@section('title', 'Ubah Testimoni')

@section('content')

<form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Testimoni"
        :title="$testimonial->client_name"
        subtitle="Terakhir diubah {{ $testimonial->updated_at?->diffForHumans() }}"
        :back="route('admin.testimonials.index')">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    @include('admin.testimonials._form')
</form>

@endsection
