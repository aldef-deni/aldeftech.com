@extends('layouts.layoutMaster')

@section('title', 'Ubah Pengguna')

@section('content')

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Pengguna"
        :title="$user->name"
        :subtitle="$user->email"
        :back="route('admin.users.index')">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <x-admin.form.input label="Nama" name="name" :value="$user->name" required autocomplete="name" />
                    <x-admin.form.input label="Email" name="email" type="email" :value="$user->email" required autocomplete="email" />

                    <x-admin.form.select
                        label="Peran" name="role" required
                        :options="$roles->pluck('display_name', 'name')->all()"
                        :value="$user->roles->first()->name ?? null" />

                    <hr class="my-4">

                    <p class="text-body-secondary mb-3">
                        Kosongkan kolom di bawah bila kata sandi tidak diubah.
                    </p>

                    <x-admin.form.input
                        label="Kata Sandi Baru" name="password" type="password"
                        autocomplete="new-password" help="Minimal 8 karakter." />

                    <x-admin.form.input
                        label="Ulangi Kata Sandi Baru" name="password_confirmation" type="password"
                        autocomplete="new-password" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
