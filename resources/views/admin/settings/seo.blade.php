@extends('layouts.layoutMaster')

@section('title', 'Pengaturan SEO')

@php use App\Models\SiteSetting; @endphp

@section('content')

<form method="POST" action="{{ route('admin.settings.seo.update') }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Pengaturan"
        title="SEO"
        subtitle="Judul, deskripsi, dan gambar bawaan untuk mesin pencari dan berbagi tautan">
        <a href="{{ route('sitemap') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-sitemap me-2"></i>Sitemap
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Meta Bawaan</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Judul Bawaan" name="seo_default_title"
                        :value="SiteSetting::get('seo_default_title', '')"
                        placeholder="Aldef Tech — Jasa Pembuatan Sistem, Aplikasi, SaaS & AI"
                        help="Dipakai halaman yang tidak punya judul sendiri. Idealnya 50-60 karakter." />

                    <x-admin.form.input
                        label="Kode Verifikasi Google Search Console"
                        name="google_search_console_verification"
                        :value="SiteSetting::get('google_search_console_verification', '')"
                        placeholder="contoh: aBcD1eFgH2iJkL3mNoP4qRsT5uVwX6yZ"
                        help="Isi hanya nilai content-nya, bukan seluruh tag &lt;meta&gt;. Search Console memberi tag lengkap; ambil teks di dalam tanda kutip content." />

                    <x-admin.form.textarea
                        label="Deskripsi Bawaan" name="seo_default_description"
                        :value="SiteSetting::get('seo_default_description', '')" :rows="4"
                        help="Idealnya 140-160 karakter agar tidak terpotong di hasil pencarian." />

                    <x-admin.form.input
                        label="Gambar Bagikan (OG Image)" name="seo_default_image"
                        :value="SiteSetting::get('seo_default_image', '')"
                        placeholder="images/og-image.jpg"
                        help="Tampil saat tautan situs dibagikan di WhatsApp, LinkedIn, atau X. Rasio 1200x630 paling aman." />
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Pratinjau Gambar Bagikan</h5></div>
                <div class="card-body">
                    <img src="{{ media_url(SiteSetting::get('seo_default_image', ''), 'images/og-image.jpg') }}"
                         alt="Pratinjau OG image" class="img-fluid rounded border">
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Berkas SEO</h5></div>
                <div class="card-body">
                    <p class="text-body-secondary">Dihasilkan otomatis dari konten yang sudah terbit.</p>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('sitemap') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                            <i class="icon-base ti tabler-file-code me-2"></i>sitemap.xml
                        </a>
                        <a href="{{ route('robots') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                            <i class="icon-base ti tabler-robot me-2"></i>robots.txt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
