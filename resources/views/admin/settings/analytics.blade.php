@extends('layouts.layoutMaster')

@section('title', 'Pengaturan Analytics')

@php use App\Models\SiteSetting; @endphp

@section('content')

<form method="POST" action="{{ route('admin.settings.analytics.update') }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Pengaturan"
        title="Analytics &amp; Pelacakan"
        subtitle="Skrip hanya dimuat di situs publik bila kolomnya diisi">
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-7">

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Google</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Google Analytics ID" name="google_analytics_id"
                        :value="SiteSetting::get('google_analytics_id', '')"
                        placeholder="G-XXXXXXXXXX" class="font-monospace" />

                    <x-admin.form.input
                        label="Google Tag Manager ID" name="google_tag_manager_id"
                        :value="SiteSetting::get('google_tag_manager_id', '')"
                        placeholder="GTM-XXXXXXX" class="font-monospace"
                        help="Bila memakai GTM, biasanya GA4 dipasang dari dalam GTM — cukup isi salah satu." />

                    <x-admin.form.input
                        label="Verifikasi Search Console" name="google_search_console_verification"
                        :value="SiteSetting::get('google_search_console_verification', '')"
                        placeholder="Kode verifikasi meta tag" class="font-monospace"
                        help="Isi nilai content dari meta tag verifikasi, bukan seluruh tag." />
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Meta</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Meta Pixel ID" name="meta_pixel_id"
                        :value="SiteSetting::get('meta_pixel_id', '')"
                        placeholder="123456789012345" class="font-monospace" />
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Status Pelacakan</h5></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach([
                            'Google Analytics' => 'google_analytics_id',
                            'Google Tag Manager' => 'google_tag_manager_id',
                            'Meta Pixel' => 'meta_pixel_id',
                            'Search Console' => 'google_search_console_verification',
                        ] as $label => $key)
                        @php $on = filled(SiteSetting::get($key, '')); @endphp
                        <li class="d-flex align-items-center justify-content-between py-2 {{ $loop->last ? '' : 'border-bottom' }}">
                            <span>{{ $label }}</span>
                            <span class="badge bg-label-{{ $on ? 'success' : 'secondary' }}">
                                {{ $on ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
