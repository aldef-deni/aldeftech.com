@extends('layouts.layoutMaster')

@section('title', 'Tambah Tag')

@section('content')

<form method="POST" action="{{ route('admin.tags.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Tag"
        title="Tambah Tag"
        :back="route('admin.tags.index')">
        <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    <div class="row">
        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input
                        label="Nama Tag" name="name" required placeholder="mis. Otomasi"
                        help="Slug dibuat otomatis dari nama." />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
