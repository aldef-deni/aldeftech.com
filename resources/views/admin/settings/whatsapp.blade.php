@extends('layouts.layoutMaster')

@section('title', 'Pengaturan WhatsApp')

@php
    use App\Models\SiteSetting;
    use App\Services\WhatsAppService;
@endphp

@section('content')

<form method="POST" action="{{ route('admin.settings.whatsapp.update') }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Pengaturan"
        title="WhatsApp"
        subtitle="Nomor tujuan dan pesan awal untuk semua tombol konsultasi di situs">
        <a href="{{ WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-brand-whatsapp me-2"></i>Uji Tautan
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Kanal WhatsApp</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Nomor WhatsApp" name="whatsapp_number"
                        :value="SiteSetting::get('whatsapp_number', '')"
                        placeholder="628128968609"
                        help="Format internasional tanpa tanda plus atau spasi. Contoh: 628128968609." />

                    <x-admin.form.textarea
                        label="Pesan Awal" name="whatsapp_default_message"
                        :value="SiteSetting::get('whatsapp_default_message', '')" :rows="4"
                        placeholder="Halo Aldef Tech, saya ingin berkonsultasi mengenai kebutuhan sistem untuk bisnis saya."
                        help="Teks ini sudah terisi di kolom chat saat pengunjung menekan tombol konsultasi." />
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Tautan Aktif</h5></div>
                <div class="card-body">
                    <p class="text-body-secondary">Tautan berikut dipakai oleh tombol konsultasi, widget mengambang, dan footer.</p>
                    <div class="alert alert-secondary mb-3">
                        <code class="text-break">{{ WhatsAppService::getUrl() }}</code>
                    </div>
                    <p class="text-body-secondary mb-0">
                        Halaman Layanan dan Portofolio mengirim pesan yang sudah menyebut nama layanan atau proyek
                        yang sedang dilihat pengunjung.
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
