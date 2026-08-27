@extends('layouts.layoutMaster')

@section('title', 'Tambah Pengguna')

@section('content')

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Pengguna"
        title="Tambah Pengguna"
        :back="route('admin.users.index')">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    <div class="row">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input label="Nama" name="name" required placeholder="Nama lengkap" autocomplete="name" />
                    <x-admin.form.input label="Email" name="email" type="email" required placeholder="nama@aldeftech.com" autocomplete="email" />

                    <x-admin.form.select
                        label="Peran" name="role" required
                        :options="$roles->pluck('display_name', 'name')->all()"
                        placeholder="Pilih peran"
                        help="Peran menentukan menu yang dapat diakses." />

                    <hr class="my-4">

                    <x-admin.form.input
                        label="Kata Sandi" name="password" type="password" required
                        autocomplete="new-password" help="Minimal 8 karakter." />

                    <x-admin.form.input
                        label="Ulangi Kata Sandi" name="password_confirmation" type="password" required
                        autocomplete="new-password" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
