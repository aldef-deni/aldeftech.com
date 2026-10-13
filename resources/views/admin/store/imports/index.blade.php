@extends('layouts.layoutMaster')

@section('title', 'Import ZIP Produk')

@section('content')

<x-admin.page-head
    eyebrow="Aldef Store · Sepatu"
    title="Import ZIP Produk"
    subtitle="Unggah banyak produk sekaligus dari satu ZIP berisi folder brand, kategori, dan produk" />

<div class="row g-4">
    <div class="col-12 col-xl-7">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Unggah ZIP</h5></div>
            <div class="card-body"
                 data-store-import
                 data-start-url="{{ route('admin.store.imports.store') }}"
                 data-chunk-bytes="{{ $chunkBytes }}"
                 data-max-bytes="{{ $maxBytes }}">

                <input type="file" accept=".zip,application/zip" hidden data-import-file>
                <button type="button" class="aldef-import-drop w-100 border border-2 border-dashed rounded-3 p-5 text-center bg-transparent" data-import-pick>
                    <i class="icon-base ti tabler-file-zip d-block mx-auto mb-2" style="font-size:2.5rem"></i>
                    <span class="fw-medium d-block">Pilih atau seret berkas ZIP ke sini</span>
                    <small class="text-body-secondary">Maksimal 500 MB · diunggah bertahap, aman untuk koneksi lambat</small>
                </button>

                <div class="mt-4" data-import-progress hidden>
                    <div class="d-flex justify-content-between small mb-1">
                        <span data-import-stage>Mengunggah…</span>
                        <span data-import-percent>0%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" data-import-bar></div>
                    </div>
                    <small class="text-body-secondary d-block mt-2" data-import-detail></small>
                </div>

                <div class="alert alert-danger mt-4 mb-0" role="alert" data-import-error hidden></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Riwayat Import</h5></div>
            @if($imports->isEmpty())
                <div class="card-body text-body-secondary">Belum ada import.</div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Berkas</th><th class="d-none d-md-table-cell">Waktu</th><th class="text-center">Produk</th><th class="text-center">Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($imports as $import)
                            @php $c = $import->counts(); @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('admin.store.imports.show', $import) }}" class="fw-medium text-body text-break">{{ $import->original_name }}</a>
                                    <small class="text-body-secondary d-block">{{ format_upload_mb($import->size) }}@if($import->user) · {{ $import->user->name }}@endif</small>
                                </td>
                                <td class="d-none d-md-table-cell"><small>{{ $import->created_at->format('d M Y H:i') }}</small></td>
                                <td class="text-center">
                                    @if($import->status === 'done')
                                        <span class="text-success fw-medium">{{ $c['created'] }}</span> <small class="text-body-secondary">dibuat</small>
                                    @elseif($c['products'] > 0)
                                        {{ $c['products'] }}
                                    @else
                                        <span class="text-body-secondary">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ match($import->status) { 'done' => 'bg-label-success', 'failed', 'cancelled', 'expired' => 'bg-label-secondary', default => 'bg-label-warning' } }}">{{ $import->statusLabel() }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Susunan folder di dalam ZIP</h5></div>
            <div class="card-body">
                <pre class="bg-body-tertiary rounded p-3 small mb-3" style="white-space: pre;">Nike/
  Sepatu Lari/
    Trail/
      Pegasus Trail 5/
        1.jpg
        2.jpg
        video.mp4
  Sneakers/
    Air Force 1/          ← tanpa subkategori
      1.jpg
Adidas/
  Samba OG/               ← tanpa kategori
    1.jpg  2.webp</pre>
                <ul class="small mb-0 ps-3">
                    <li><strong>Brand / Kategori / Subkategori / Nama Produk</strong>. Kategori dan subkategori boleh dilewati.</li>
                    <li>Nama folder terakhir menjadi <strong>nama produk</strong>. Brand, kategori, dan subkategori yang belum ada dibuat otomatis; yang sudah ada dicocokkan tanpa peduli huruf besar/kecil.</li>
                    <li>Foto JPG, PNG, WebP (maks 15 MB, hingga 12) dan video MP4, WebM (maks 50 MB, hingga 3). Urutan mengikuti nama berkas; <strong>foto pertama menjadi foto utama</strong>, jadi beri nama <code>1.jpg</code>, <code>2.jpg</code>, dst.</li>
                    <li>Berkas lain (PDF, dokumen, file sistem Mac/Windows) diabaikan dan dicatat di laporan.</li>
                    <li>Produk dengan nama dan brand yang sudah ada <strong>dilewati</strong>, jadi ZIP aman diunggah ulang.</li>
                    <li>Semua hasil import berstatus <strong>Draf</strong>. Lengkapi ukuran dan warna, lalu terbitkan dari daftar produk (bisa banyak sekaligus).</li>
                    <li>Boleh mengompres satu folder pembungkus (mis. <code>Katalog/Nike/…</code>); di pratinjau Anda bisa memilih untuk mengabaikannya.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
    @vite(['resources/assets/js/aldef-store-import.js'])
@endsection
