@extends('layouts.layoutMaster')

@section('title', 'Profil CEO')

@section('content')

<form method="POST" action="{{ route('admin.ceo.update') }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Pengaturan"
        title="Profil CEO"
        subtitle="Tampil di beranda dan halaman Tentang">
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
                <div class="card-header"><h5 class="card-title mb-0">Identitas</h5></div>
                <div class="card-body">
                    <div class="row">
                        <x-admin.form.input
                            col="12 col-md-6" label="Nama" name="name" :value="$profile->name" required />
                        <x-admin.form.input
                            col="12 col-md-6" label="Jabatan" name="position" :value="$profile->position" required
                            placeholder="mis. Founder & Lead Technical Architect" />
                    </div>

                    <x-admin.form.textarea
                        label="Bio Singkat" name="short_bio" :value="$profile->short_bio ?? ''" :rows="4"
                        placeholder="Kalimat yang dikutip besar di beranda"
                        help="Dipakai sebagai kutipan di bagian Kepemimpinan. Maksimal 1000 karakter." />

                    <x-admin.form.textarea
                        label="Bio Lengkap" name="full_bio" :value="$profile->full_bio ?? ''" :rows="8"
                        placeholder="Latar belakang, pengalaman, dan fokus keahlian" />
                </div>
            </div>
        <x-admin.form.translation
            :model="$profile"
            :fields="[
                'position'   => ['label' => 'Jabatan (English)', 'type' => 'text'],
                'short_bio'  => ['label' => 'Bio Singkat (English)', 'type' => 'textarea', 'rows' => 4],
                'full_bio'   => ['label' => 'Bio Lengkap (English)', 'type' => 'textarea', 'rows' => 8],
                'skills'     => ['label' => 'Bidang Keahlian (English)', 'type' => 'list'],
                'experience' => ['label' => 'Pengalaman (English)', 'type' => 'list'],
            ]" />


            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Kontak &amp; Tautan</h5></div>
                <div class="card-body">
                    <div class="row">
                        <x-admin.form.input
                            col="12 col-md-6" label="Email" name="email" type="email" :value="$profile->email ?? ''" />
                        <x-admin.form.input
                            col="12 col-md-6" label="LinkedIn" name="linkedin" type="url" :value="$profile->linkedin ?? ''"
                            placeholder="https://linkedin.com/in/..." />
                        <x-admin.form.input
                            col="12 col-md-6" label="GitHub" name="github" type="url" :value="$profile->github ?? ''"
                            placeholder="https://github.com/..." />
                        <x-admin.form.input
                            col="12 col-md-6" label="Instagram" name="instagram" type="url" :value="$profile->instagram ?? ''"
                            placeholder="https://instagram.com/..." />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Foto</h5></div>
                <div class="card-body">
                    <x-admin.form.image
                        label="Foto" name="profile_photo" :value="$profile->profile_photo ?? ''"
                        ratio="4 / 5" fallback="images/deni-afrizal.jpg"
                        help="Potret rasio 4:5 paling pas." />

                    <x-admin.form.switch
                        label="Tampilkan di situs" name="is_active" :checked="$profile->is_active ?? true" />
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Keahlian</h5></div>
                <div class="card-body">
                    <x-admin.form.list
                        label="Bidang Keahlian" name="skills"
                        :items="$profile->skills ?? []"
                        placeholder="mis. System Architecture"
                        add-label="Tambah keahlian" />
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Pengalaman</h5></div>
                <div class="card-body">
                    <x-admin.form.list
                        label="Riwayat" name="experience"
                        :items="$profile->experience ?? []"
                        placeholder="mis. 10+ tahun rekayasa perangkat lunak"
                        add-label="Tambah pengalaman" />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
