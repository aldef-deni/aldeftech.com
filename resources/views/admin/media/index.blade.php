@extends('layouts.layoutMaster')

@section('title', 'Media')

@php
    // The label must reflect the limit the server actually enforces, not the
    // app's own config: anything bigger is dropped by PHP before Laravel runs.
    $maxBytes  = max_upload_bytes();
    $maxLabel  = max_upload_label();
    $allowed   = config('aldeftech.upload.allowed_mimes', []);
    $accept    = collect($allowed)->map(fn ($e) => '.' . $e)->implode(',');
    $configMb  = round(config('aldeftech.upload.max_size', 5120) / 1024, 1);
    $phpCapped = $maxBytes < config('aldeftech.upload.max_size', 5120) * 1024;
@endphp

@section('content')

<x-admin.page-head
    eyebrow="Pengaturan"
    title="Media"
    subtitle="{{ $media->total() }} berkas · maksimal {{ $maxLabel }} per berkas">
    <button type="button" class="btn btn-primary" data-upload-trigger>
        <i class="icon-base ti tabler-upload me-2"></i>Unggah Berkas
    </button>
</x-admin.page-head>

@if($phpCapped)
<div class="alert alert-warning d-flex align-items-start gap-3 mb-4" role="alert">
    <i class="icon-base ti tabler-server-cog mt-1"></i>
    <div>
        <h6 class="alert-heading mb-1">Batas server lebih kecil dari pengaturan aplikasi</h6>
        <p class="mb-0 small">
            Aplikasi disetel {{ $configMb }} MB, tetapi PHP di server ini membatasi
            <code>upload_max_filesize</code> = {{ ini_get('upload_max_filesize') }} dan
            <code>post_max_size</code> = {{ ini_get('post_max_size') }}, sehingga batas yang benar-benar
            berlaku adalah <strong>{{ $maxLabel }}</strong>. Naikkan kedua nilai itu di <code>php.ini</code>
            (dan <code>client_max_body_size</code> di nginx) bila ingin {{ $configMb }} MB.
        </p>
    </div>
</div>
@endif

{{-- Hidden form driven by the picker below --}}
<form id="mediaUploadForm" method="POST" action="{{ route('admin.media.upload') }}"
      enctype="multipart/form-data" class="d-none"
      data-max-bytes="{{ $maxBytes }}"
      data-max-label="{{ $maxLabel }}"
      data-allowed="{{ implode(',', $allowed) }}">
    @csrf
    <input type="file" name="file" id="mediaFileInput" accept="{{ $accept }}">
</form>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.media.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <label for="search" class="form-label">Cari berkas</label>
                <input type="search" id="search" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Nama berkas">
            </div>
            <div class="col-8 col-md-4">
                <label for="type" class="form-label">Jenis</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Semua jenis</option>
                    <option value="image" @selected(request('type') === 'image')>Gambar</option>
                    <option value="application" @selected(request('type') === 'application')>Dokumen</option>
                    <option value="video" @selected(request('type') === 'video')>Video</option>
                </select>
            </div>
            <div class="col-4 col-md-2">
                <button type="submit" class="btn btn-primary w-100">Terapkan</button>
            </div>
        </form>
    </div>
</div>

@if($media->isEmpty())
<div class="card">
    <div class="card-body">
        <x-admin.empty
            icon="tabler-photo"
            title="Belum ada berkas"
            message="Unggah gambar di sini, lalu salin path-nya ke kolom gambar pada portofolio atau artikel. Format yang diterima: {{ implode(', ', $allowed) }}.">
            <button type="button" class="btn btn-primary" data-upload-trigger>
                <i class="icon-base ti tabler-upload me-2"></i>Unggah Berkas
            </button>
        </x-admin.empty>
    </div>
</div>
@else
<div class="row g-4">
    @foreach($media as $item)
    <div class="col-6 col-md-4 col-lg-3 col-xxl-2">
        <div class="card h-100">
            <div class="ratio ratio-4x3 bg-body-secondary rounded-top overflow-hidden">
                @if(str_starts_with((string) $item->mime_type, 'image/'))
                    <img src="{{ media_url($item->path) }}" alt="{{ $item->original_name }}"
                         class="w-100 h-100" style="object-fit: cover;" loading="lazy">
                @else
                    <span class="d-flex align-items-center justify-content-center text-body-secondary">
                        <i class="icon-base ti tabler-file icon-lg"></i>
                    </span>
                @endif
            </div>

            <div class="card-body p-3">
                <p class="mb-1 small fw-medium text-truncate" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
                <small class="text-body-secondary d-block">{{ number_format($item->size / 1024, 0, ',', '.') }} KB</small>

                <div class="input-group input-group-sm mt-2">
                    <input type="text" class="form-control font-monospace" readonly
                           value="{{ $item->path }}" aria-label="Path berkas"
                           onclick="this.select(); navigator.clipboard && navigator.clipboard.writeText(this.value);">
                    <x-admin.delete
                        :action="route('admin.media.destroy', $item)"
                        :confirm="'Hapus ' . $item->original_name . '? Berkas ini mungkin masih dipakai di halaman lain.'"
                        class="btn btn-outline-danger btn-sm" />
                </div>
                <small class="text-body-secondary">Klik path untuk menyalin.</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($media->hasPages())
<div class="mt-4">{{ $media->links() }}</div>
@endif
@endif

{{-- ── Upload guard modal ──────────────────────────────────────────────── --}}
<div class="modal fade" id="uploadGuardModal" tabindex="-1" aria-labelledby="uploadGuardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="uploadGuardTitle">
                    <i class="icon-base ti tabler-alert-triangle text-warning"></i>
                    <span data-guard-title>Berkas terlalu besar</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <p data-guard-message class="mb-3"></p>

                <div class="d-flex align-items-center justify-content-between p-3 rounded bg-body-secondary">
                    <span class="small text-body-secondary">Batas maksimal per berkas</span>
                    <span class="fw-semibold" data-guard-limit>{{ $maxLabel }}</span>
                </div>

                <p class="small text-body-secondary mt-3 mb-0">
                    Kompres gambar terlebih dulu (misalnya ke format <strong>WebP</strong>) agar ukurannya turun
                    drastis tanpa mengorbankan kualitas, lalu unggah ulang.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" data-guard-retry>Pilih Berkas Lain</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Uploading state modal ───────────────────────────────────────────── --}}
<div class="modal fade" id="uploadProgressModal" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Mengunggah…</span>
                </div>
                <p class="mb-1 fw-medium">Mengunggah berkas</p>
                <small class="text-body-secondary text-truncate d-block" data-upload-name></small>
            </div>
        </div>
    </div>
</div>


@endsection

@section('page-script')
<script>
(function () {
  var form  = document.getElementById('mediaUploadForm');
  var input = document.getElementById('mediaFileInput');
  if (!form || !input) return;

  var maxBytes = parseInt(form.dataset.maxBytes, 10) || 0;
  var maxLabel = form.dataset.maxLabel || '';
  var allowed  = (form.dataset.allowed || '').split(',').filter(Boolean);

  var guardEl    = document.getElementById('uploadGuardModal');
  var progressEl = document.getElementById('uploadProgressModal');
  var guard      = guardEl ? new bootstrap.Modal(guardEl) : null;
  var progress   = progressEl ? new bootstrap.Modal(progressEl) : null;

  function humanSize(bytes) {
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1).replace('.', ',') + ' MB';
    return Math.round(bytes / 1024) + ' KB';
  }

  function showGuard(title, message) {
    if (!guard) { window.alert(title + '\n\n' + message); return; }
    guardEl.querySelector('[data-guard-title]').textContent = title;
    guardEl.querySelector('[data-guard-message]').textContent = message;
    guard.show();
  }

  document.querySelectorAll('[data-upload-trigger]').forEach(function (btn) {
    btn.addEventListener('click', function () { input.click(); });
  });

  if (guardEl) {
    guardEl.querySelector('[data-guard-retry]').addEventListener('click', function () {
      guard.hide();
      input.click();
    });
  }

  input.addEventListener('change', function () {
    var file = input.files && input.files[0];
    if (!file) return;

    // Reject before submitting: a request over the server limit is cut off by
    // PHP/nginx and the browser lands on a raw 413 page instead of our UI.
    if (maxBytes && file.size > maxBytes) {
      showGuard(
        'Berkas terlalu besar',
        'Ukuran "' + file.name + '" adalah ' + humanSize(file.size) +
        ', melebihi batas ' + maxLabel + '. Berkas tidak dikirim ke server.'
      );
      input.value = '';
      return;
    }

    var ext = (file.name.split('.').pop() || '').toLowerCase();
    if (allowed.length && allowed.indexOf(ext) === -1) {
      showGuard(
        'Format berkas tidak didukung',
        'Berkas ".' + ext + '" tidak dapat diunggah. Format yang diterima: ' + allowed.join(', ') + '.'
      );
      input.value = '';
      return;
    }

    var nameEl = progressEl && progressEl.querySelector('[data-upload-name]');
    if (nameEl) nameEl.textContent = file.name + ' · ' + humanSize(file.size);
    if (progress) progress.show();

    form.submit();
  });
})();
</script>
@endsection
