@extends('layouts.layoutMaster')

@section('title', 'Pengaturan Aldef Store')

@section('content')

<form method="POST" action="{{ route('admin.store.settings.update') }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Aldef Store"
        title="Pengaturan Toko"
        subtitle="Nomor WhatsApp tujuan tombol “Tanya Harga via WhatsApp”">
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">WhatsApp Katalog</h5></div>
                <div class="card-body">
                    <x-admin.form.input
                        label="Nomor WhatsApp katalog" name="store_whatsapp_number" :value="$storeNumber"
                        placeholder="628xxxxxxxxxx" inputmode="tel" maxlength="30"
                        help="Format internasional tanpa tanda +, mis. 6281234567xxx. Nomor berawalan 0 diubah otomatis ke 62. Kosongkan untuk memakai nomor WhatsApp bisnis di Pengaturan > WhatsApp." />
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Nomor yang dipakai sekarang</h5></div>
                <div class="card-body">
                    @if($effectiveNumber)
                        <p class="mb-1 fs-5 fw-semibold">+{{ $effectiveNumber }}</p>
                        <p class="text-body-secondary small mb-3">
                            {{ $source === 'store' ? 'Dari nomor WhatsApp katalog di halaman ini.' : 'Dari nomor WhatsApp bisnis (Pengaturan > WhatsApp).' }}
                        </p>
                        <a href="https://wa.me/{{ $effectiveNumber }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success">
                            <i class="icon-base ti tabler-brand-whatsapp me-1"></i>Uji buka WhatsApp
                        </a>
                    @else
                        <div class="alert alert-warning mb-0" role="alert">
                            Belum ada nomor WhatsApp yang valid. Tombol &ldquo;Tanya Harga via WhatsApp&rdquo; di halaman produk dinonaktifkan
                            dan pengunjung diarahkan ke halaman Kontak sampai nomor diisi.
                        </div>
                    @endif
                    @if($businessNumber && $source === 'store')
                        <p class="text-body-secondary small mt-3 mb-0">Nomor bisnis cadangan: +{{ $businessNumber }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
