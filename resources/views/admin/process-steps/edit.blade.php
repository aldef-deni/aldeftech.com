@extends('layouts.layoutMaster')

@section('title', 'Ubah Tahap')

@section('content')

<form method="POST" action="{{ route('admin.process-steps.update', $step) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Alur Kerja"
        :title="$step->title"
        subtitle="Tahap ke-{{ $step->step_number }}"
        :back="route('admin.process-steps.index')">
        <a href="{{ route('admin.process-steps.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Detail Tahap</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Nama Tahap" name="title" :value="$step->title" required />
                    <x-admin.form.textarea
                        label="Penjelasan" name="description" :value="$step->description" required :rows="4" />
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Pengaturan</h5></div>
                <div class="card-body">
                    <x-admin.form.switch label="Tampilkan di situs" name="is_published" :checked="$step->is_published" />
                    <x-admin.form.input
                        label="Nomor Tahap" name="step_number" type="number" :value="$step->step_number" required
                        help="Menentukan urutan tampil." />
                    <x-admin.form.input label="Ikon" name="icon" :value="$step->icon ?? ''" placeholder="🧭" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
