@extends('layouts.layoutMaster')

@section('title', 'Homepage')

@php
    // Human labels for the section keys the controller seeds.
    $sectionLabels = [
        'hero'               => ['Hero', 'Bagian paling atas: judul besar, subjudul, dan tombol utama.'],
        'trust'              => ['Deret Teknologi', 'Baris berjalan berisi tech stack di bawah hero.'],
        'services'           => ['Layanan', 'Grid kartu layanan.'],
        'featured_portfolio' => ['Portofolio Unggulan', 'Tiga proyek yang ditandai "Tampil di beranda".'],
        'why_aldeftech'      => ['Mengapa Aldef Tech', 'Empat prinsip pembeda.'],
        'process'            => ['Alur Kerja', 'Tahapan pengerjaan proyek.'],
        'ceo'                => ['Kepemimpinan', 'Kutipan dan profil pendiri.'],
        'testimonials'       => ['Testimoni', 'Kutipan klien.'],
        'faq'                => ['FAQ', 'Enam pertanyaan teratas.'],
        'final_cta'          => ['Ajakan Penutup', 'Blok gelap terakhir sebelum footer.'],
    ];

    $manageLinks = [
        'services'           => ['Kelola Layanan', 'admin.services.index'],
        'featured_portfolio' => ['Kelola Portofolio', 'admin.portfolio.index'],
        'process'            => ['Kelola Alur Kerja', 'admin.process-steps.index'],
        'ceo'                => ['Kelola Profil CEO', 'admin.ceo.edit'],
        'testimonials'       => ['Kelola Testimoni', 'admin.testimonials.index'],
        'faq'                => ['Kelola FAQ', 'admin.faq.index'],
    ];
@endphp

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Homepage"
    subtitle="Atur judul, subjudul, dan visibilitas tiap bagian di beranda.">
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
        <i class="icon-base ti tabler-external-link me-2"></i>Lihat Beranda
    </a>
</x-admin.page-head>

<div class="accordion" id="homepageSections">
    @foreach($sections as $section)
    @php
        [$label, $hint] = $sectionLabels[$section->section_key] ?? [ucwords(str_replace('_', ' ', $section->section_key)), null];
        $manage = $manageLinks[$section->section_key] ?? null;
    @endphp

    <div class="accordion-item mb-3">
        <div class="d-flex align-items-center gap-2 pe-3">
            <h2 class="accordion-header flex-grow-1 min-w-0" id="secHead{{ $section->id }}">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#secBody{{ $section->id }}"
                        aria-expanded="false" aria-controls="secBody{{ $section->id }}">
                    <span class="me-3 text-body-secondary font-monospace small">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="fw-medium">{{ $label }}</span>
                </button>
            </h2>
            <span class="badge bg-label-{{ $section->is_visible ? 'success' : 'secondary' }} text-nowrap">
                {{ $section->is_visible ? 'Tampil' : 'Disembunyikan' }}
            </span>
        </div>

        <div id="secBody{{ $section->id }}" class="accordion-collapse collapse"
             aria-labelledby="secHead{{ $section->id }}" data-bs-parent="#homepageSections">
            <div class="accordion-body">
                @if($hint)<p class="text-body-secondary">{{ $hint }}</p>@endif

                <form method="POST" action="{{ route('admin.homepage.update', $section) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-4">
                                <label for="title{{ $section->id }}" class="form-label">Judul Bagian</label>
                                <input type="text" id="title{{ $section->id }}" name="title"
                                       value="{{ $section->title }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-4">
                                <label for="subtitle{{ $section->id }}" class="form-label">Subjudul</label>
                                <input type="text" id="subtitle{{ $section->id }}" name="subtitle"
                                       value="{{ $section->subtitle }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <input type="hidden" name="is_visible" value="0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="visible{{ $section->id }}" name="is_visible" value="1"
                                   @checked($section->is_visible)>
                            <label class="form-check-label" for="visible{{ $section->id }}">Tampilkan bagian ini</label>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan
                        </button>
                        @if($manage)
                        <a href="{{ route($manage[1]) }}" class="btn btn-outline-secondary btn-sm">
                            {{ $manage[0] }}
                            <i class="icon-base ti tabler-arrow-right ms-2"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
