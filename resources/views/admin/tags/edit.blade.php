@extends('layouts.layoutMaster')

@section('title', 'Ubah Tag')

@section('content')

<form method="POST" action="{{ route('admin.tags.update', $tag) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Tag"
        :title="$tag->name"
        :subtitle="'/' . $tag->slug"
        :back="route('admin.tags.index')">
        <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row">
        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input
                        label="Nama Tag" name="name" :value="$tag->name" required
                        help="Slug diperbarui otomatis mengikuti nama." />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
