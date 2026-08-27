@extends('layouts.layoutMaster')

@section('title', 'Tentang')

@php
    use App\Models\SiteSetting;

    $aboutValues = json_decode(SiteSetting::get('about_values', '[]'), true);
    $aboutValues = is_array($aboutValues) ? $aboutValues : [];
@endphp

@section('content')

<form method="POST" action="{{ route('admin.about.update') }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Konten Situs"
        title="Halaman Tentang"
        subtitle="Teks yang tampil di aldeftech.com/about">
        <a href="{{ route('about') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-external-link me-2"></i>Lihat
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-8">

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Kepala Halaman</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Judul" name="about_title" :value="SiteSetting::get('about_title', '')"
                        placeholder="Tentang Aldef Tech" />

                    <x-admin.form.textarea
                        label="Subjudul" name="about_subtitle" :value="SiteSetting::get('about_subtitle', '')"
                        :rows="3" placeholder="Satu paragraf pengantar"
                        help="Dipakai juga sebagai meta description halaman Tentang." />

                    <x-admin.form.textarea
                        label="Konten Tambahan" name="about_content" :value="SiteSetting::get('about_content', '')"
                        :rows="8" placeholder="Cerita panjang tentang perusahaan (opsional)" />
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Misi &amp; Visi</h5></div>
                <div class="card-body">
                    <x-admin.form.textarea
                        label="Misi" name="about_mission" :value="SiteSetting::get('about_mission', '')"
                        :rows="4" placeholder="Apa yang perusahaan kerjakan setiap hari" />

                    <x-admin.form.textarea
                        label="Visi" name="about_vision" :value="SiteSetting::get('about_vision', '')"
                        :rows="4" placeholder="Ke mana perusahaan menuju" />
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="badge bg-label-primary">EN</span>
                <div>
                    <h5 class="card-title mb-0">English</h5>
                    <small class="text-body-secondary">Dikosongkan berarti memakai teks Bahasa Indonesia di atas.</small>
                </div>
            </div>
            <div class="card-body">
                @php
                    $en = fn (string $k) => optional(\App\Models\SiteSetting::where('key', $k)->first())->translate('value', 'en');
                @endphp

                <div class="mb-4">
                    <label class="form-label" for="en_about_title">Judul (English)</label>
                    <input type="text" id="en_about_title" name="en[about_title]"
                           value="{{ old('en.about_title', $en('about_title')) }}" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label" for="en_about_subtitle">Subjudul (English)</label>
                    <textarea id="en_about_subtitle" name="en[about_subtitle]" rows="3" class="form-control">{{ old('en.about_subtitle', $en('about_subtitle')) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="en_about_content">Konten Tambahan (English)</label>
                    <textarea id="en_about_content" name="en[about_content]" rows="8" class="form-control">{{ old('en.about_content', $en('about_content')) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="en_about_mission">Misi (English)</label>
                    <textarea id="en_about_mission" name="en[about_mission]" rows="4" class="form-control">{{ old('en.about_mission', $en('about_mission')) }}</textarea>
                </div>

                <div class="mb-0">
                    <label class="form-label" for="en_about_vision">Visi (English)</label>
                    <textarea id="en_about_vision" name="en[about_vision]" rows="4" class="form-control">{{ old('en.about_vision', $en('about_vision')) }}</textarea>
                </div>
            </div>
        </div>

    <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Nilai Perusahaan</h5></div>
                <div class="card-body">
                    <x-admin.form.list
                        label="Daftar Nilai" name="about_values"
                        :items="$aboutValues"
                        placeholder="mis. Jujur soal ruang lingkup"
                        add-label="Tambah nilai"
                        help="Halaman Tentang menampilkan empat prinsip kerja bawaan bila daftar ini kosong." />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
