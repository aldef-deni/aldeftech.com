@extends('layouts.layoutMaster')

@section('title', 'SEO Halaman')

@section('content')

<x-admin.page-head
    eyebrow="Pengaturan"
    title="SEO Halaman"
    subtitle="Judul dan deskripsi tiap halaman, per bahasa — tanpa perlu deploy">
    <a href="{{ route('admin.settings.seo') }}" class="btn btn-outline-secondary">
        <i class="icon-base ti tabler-settings me-2"></i>SEO Global
    </a>
</x-admin.page-head>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Halaman</th>
                        @foreach($locales as $code => $meta)
                            <th style="width: 22%;">{{ $meta['short'] }}</th>
                        @endforeach
                        <th style="width: 1%;"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pages as $slug => $label)
                    @php $entries = ($rows[$slug] ?? collect())->keyBy('locale'); @endphp
                    <tr>
                        <td>
                            <span class="fw-semibold">{{ $label }}</span>
                            <small class="d-block text-body-secondary">/{{ $slug === 'home' ? '' : $slug }}</small>
                        </td>

                        @foreach($locales as $code => $meta)
                            @php $entry = $entries[$code] ?? null; @endphp
                            <td>
                                @if($entry?->noindex)
                                    <span class="badge bg-label-danger">noindex</span>
                                @elseif(filled($entry?->meta_title) || filled($entry?->meta_description))
                                    <span class="badge bg-label-success">Diatur</span>
                                @else
                                    <span class="badge bg-label-secondary">Bawaan</span>
                                @endif
                            </td>
                        @endforeach

                        <td class="text-end">
                            <a href="{{ route('admin.settings.page-seo.edit', $slug) }}"
                               class="btn btn-sm btn-outline-primary">Ubah</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="alert alert-primary mt-4" role="alert">
    <h6 class="alert-heading mb-1">Cara kerjanya</h6>
    <span class="small">
        Kolom yang dikosongkan memakai teks bawaan halaman, lalu default global di menu SEO.
        Jadi mengosongkan isian tidak pernah membuat judul halaman jadi kosong —
        status <strong>Bawaan</strong> berarti halaman itu memakai teks yang sudah ada di kode.
    </span>
</div>

@endsection
