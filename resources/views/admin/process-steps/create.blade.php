@extends('layouts.layoutMaster')

@section('title', 'Tambah Tahap')

@section('content')

<form method="POST" action="{{ route('admin.process-steps.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="Alur Kerja"
        title="Tambah Tahap"
        :back="route('admin.process-steps.index')">
        <a href="{{ route('admin.process-steps.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Detail Tahap</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Nama Tahap" name="title" required placeholder="mis. Konsultasi" />
                    <x-admin.form.textarea
                        label="Penjelasan" name="description" required :rows="4"
                        placeholder="Apa yang terjadi pada tahap ini dan apa keluarannya" />
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Pengaturan</h5></div>
                <div class="card-body">
                    <x-admin.form.switch label="Tampilkan di situs" name="is_published" :checked="true" />
                    <x-admin.form.input
                        label="Nomor Tahap" name="step_number" type="number" :value="$nextNumber" required
                        help="Menentukan urutan tampil." />
                    <x-admin.form.input label="Ikon" name="icon" placeholder="🧭" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
