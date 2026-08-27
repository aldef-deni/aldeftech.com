@extends('layouts.layoutMaster')

@section('title', 'Pengaturan Situs')

@php use App\Models\SiteSetting; @endphp

@section('content')

<form method="POST" action="{{ route('admin.settings.site.update') }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Pengaturan"
        title="Pengaturan Situs"
        subtitle="Identitas, kontak, dan teks footer aldeftech.com">
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-7">

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Identitas</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Nama Situs" name="site_name" :value="SiteSetting::get('site_name', config('app.name'))" />

                    <x-admin.form.input
                        label="Tagline" name="site_tagline" :value="SiteSetting::get('site_tagline', '')"
                        placeholder="Mitra Transformasi Digital Korporasi Anda" />

                    <x-admin.form.textarea
                        label="Deskripsi Situs" name="site_description" :value="SiteSetting::get('site_description', '')"
                        :rows="3" help="Dipakai sebagai fallback meta description." />
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Kontak</h5></div>
                <div class="card-body">
                    <div class="row">
                        <x-admin.form.input
                            col="12 col-md-6" label="Email" name="email" type="email"
                            :value="SiteSetting::get('email', '')" />
                        <x-admin.form.input
                            col="12 col-md-6" label="Telepon" name="phone"
                            :value="SiteSetting::get('phone', '')" />
                    </div>

                    <x-admin.form.textarea
                        label="Alamat" name="address" :value="SiteSetting::get('address', '')" :rows="3"
                        help="Tampil di footer dan halaman Kontak, sekaligus jadi kueri Google Maps." />

                    <x-admin.form.input
                        label="URL Google Maps" name="google_maps_url" type="url"
                        :value="SiteSetting::get('google_maps_url', '')"
                        placeholder="https://maps.app.goo.gl/..."
                        help="Opsional. Bila kosong, tautan dibuat otomatis dari alamat." />
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Footer</h5></div>
                <div class="card-body">
                    <x-admin.form.textarea
                        label="Deskripsi Footer" name="footer_description"
                        :value="SiteSetting::get('footer_description', '')" :rows="4"
                        placeholder="Paragraf singkat di kolom kiri footer" />

                    <x-admin.form.input
                        label="Teks Hak Cipta" name="copyright" :value="SiteSetting::get('copyright', '')"
                        placeholder="© Aldef Tech. Seluruh hak cipta dilindungi." />
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Logo &amp; Favicon</h5></div>
                <div class="card-body">
                    @if($logo = media_url(SiteSetting::get('site_logo', ''), 'images/logo.png'))
                    <div class="p-3 mb-3 rounded" style="background: #0A1017;">
                        <img src="{{ $logo }}" alt="Logo" style="height: 44px; width: auto;">
                    </div>
                    @endif

                    <x-admin.form.input
                        label="Path Logo" name="site_logo" :value="SiteSetting::get('site_logo', '')"
                        placeholder="images/logo.png"
                        help="Logo tampil di navbar dan footer. Latar transparan (PNG/SVG) paling baik." />

                    <x-admin.form.input
                        label="Path Favicon" name="site_favicon" :value="SiteSetting::get('site_favicon', '')"
                        placeholder="images/logo-square.png"
                        help="Ikon tab browser. Idealnya persegi." />
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
