@extends('layouts.layoutMaster')

@section('title', 'Kampanye Baru')

@php $selectedAudiences = old('target_audiences', []); @endphp

@section('content')

<form method="POST" action="{{ route('admin.marketing.store') }}">
    @csrf

    <x-admin.page-head
        eyebrow="AI Marketing"
        title="Kampanye Baru"
        :back="route('admin.marketing.index')">
        <a href="{{ route('admin.marketing.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Buat Kampanye
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-8">

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Detail Kampanye</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Nama Kampanye" name="name" required
                        placeholder="mis. Digitalisasi Operasional Manufaktur Q1" />

                    <x-admin.form.textarea
                        label="Tujuan" name="objective" :rows="3"
                        placeholder="Apa hasil bisnis yang ingin dicapai kampanye ini?"
                        help="Kalimat ini memandu AI saat menyusun ide konten." />

                    <x-admin.form.textarea
                        label="Deskripsi" name="description" :rows="5"
                        placeholder="Konteks tambahan: industri sasaran, penawaran, atau momentum musiman" />
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">Audiens Sasaran</h5>
                    <small class="text-body-secondary">Pilih satu atau lebih segmen yang ingin dijangkau.</small>
                </div>
                <div class="card-body">
                    @if($audiences->isEmpty())
                        <x-admin.empty icon="tabler-users" title="Belum ada audiens aktif" />
                    @else
                    <div class="row g-3">
                        @foreach($audiences as $audience)
                        <div class="col-12 col-md-6">
                            <label class="form-check card h-100 p-3 m-0 cursor-pointer">
                                <span class="d-flex gap-2">
                                    <input class="form-check-input mt-1 flex-shrink-0" type="checkbox"
                                           name="target_audiences[]" value="{{ $audience->id }}"
                                           @checked(in_array($audience->id, $selectedAudiences))>
                                    <span class="min-w-0">
                                        <span class="d-block fw-medium">{{ $audience->name }}</span>
                                        @if($audience->description)
                                        <small class="text-body-secondary">{{ excerpt_text($audience->description, 110) }}</small>
                                        @endif
                                    </span>
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Jadwal &amp; Status</h5></div>
                <div class="card-body">
                    <x-admin.form.select
                        label="Status" name="status" required
                        :options="['draft' => 'Draf', 'active' => 'Aktif', 'paused' => 'Dijeda', 'completed' => 'Selesai']"
                        value="draft" />

                    <x-admin.form.input label="Tanggal Mulai" name="start_date" type="date" />
                    <x-admin.form.input label="Tanggal Selesai" name="end_date" type="date" />

                    <x-admin.form.input
                        label="Prioritas" name="priority" type="number" :value="50"
                        min="0" max="100"
                        help="0-100. Kampanye berprioritas tinggi tampil lebih dulu." />
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Kanal Distribusi</h5></div>
                <div class="card-body">
                    @php $selectedPlatforms = old('platforms', ['blog', 'linkedin', 'facebook', 'instagram']); @endphp
                    @foreach($platforms as $key => $label)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="platforms[]"
                               value="{{ $key }}" id="platform_{{ $key }}"
                               @checked(in_array($key, $selectedPlatforms))>
                        <label class="form-check-label" for="platform_{{ $key }}">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
