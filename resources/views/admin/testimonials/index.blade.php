@extends('layouts.layoutMaster')

@section('title', 'Testimoni')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Testimoni"
    subtitle="Kelola ulasan dan pengalaman klien AldefTech. {{ $testimonials->count() }} testimoni · {{ $testimonials->filter(fn ($t) => $t->isVisible())->count() }} tampil di situs">
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Testimoni
    </a>
</x-admin.page-head>

@if($testimonials->isEmpty())
<div class="card">
    <div class="card-body">
        <x-admin.empty
            icon="tabler-star"
            title="Belum ada testimoni"
            message="Testimoni klien adalah salah satu pendorong konversi terkuat di halaman utama.">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                <i class="icon-base ti tabler-plus me-2"></i>Tambah Testimoni
            </a>
        </x-admin.empty>
    </div>
</div>
@else
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Klien</th>
                    <th class="d-none d-lg-table-cell">Perusahaan</th>
                    <th class="text-center">Rating</th>
                    <th class="d-none d-md-table-cell text-center">Bahasa</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Unggulan</th>
                    <th class="d-none d-lg-table-cell text-center">Urutan</th>
                    <th class="d-none d-xl-table-cell">Tanggal</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testimonials as $testimonial)
                <tr>
                    <td style="min-width: 15rem;">
                        <div class="d-flex align-items-center gap-3">
                            @if($src = media_url($testimonial->photo))
                                <img src="{{ $src }}" alt="" class="aldef-thumb rounded-circle">
                            @else
                                <span class="avatar avatar-sm">
                                    <span class="avatar-initial rounded-circle bg-label-primary">{{ initials_of($testimonial->client_name) }}</span>
                                </span>
                            @endif
                            <div class="text-truncate">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="fw-medium text-body d-block text-truncate">{{ $testimonial->client_name }}</a>
                                <small class="text-body-secondary d-block text-truncate" style="max-width: 22rem;">{{ $testimonial->roleLine() ?: excerpt_text($testimonial->testimonial, 60) }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <small class="text-body-secondary">{{ $testimonial->company ?: '—' }}</small>
                    </td>
                    <td class="text-center text-nowrap" aria-label="{{ $testimonial->rating }} dari 5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="icon-base ti {{ $i <= $testimonial->rating ? 'tabler-star-filled text-warning' : 'tabler-star text-body-secondary' }} icon-sm"></i>
                        @endfor
                    </td>
                    <td class="d-none d-md-table-cell text-center">
                        @foreach($testimonial->localeBadges() as $badge)
                            <span class="badge bg-label-secondary me-1">{{ $badge }}</span>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @if($testimonial->isScheduled())
                            <span class="badge bg-label-warning">Terjadwal</span>
                        @else
                            <x-admin.status :published="$testimonial->isVisible()" :labels="['Published', 'Draft']" />
                        @endif
                    </td>
                    <td class="text-center">
                        @if($testimonial->is_featured)
                            <span class="badge bg-label-warning"><i class="icon-base ti tabler-star-filled icon-sm"></i></span>
                        @else
                            <span class="text-body-secondary">—</span>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell text-center">{{ $testimonial->sort_order }}</td>
                    <td class="d-none d-xl-table-cell">
                        <small class="text-body-secondary">{{ $testimonial->published_at?->translatedFormat('d M Y') ?? '—' }}</small>
                    </td>
                    <td class="text-end text-nowrap">
                        @if($testimonial->isVisible())
                        <a href="{{ lroute('home') }}#testimoni" target="_blank" rel="noopener"
                           class="btn btn-sm btn-icon btn-text-secondary" title="Lihat di situs">
                            <i class="icon-base ti tabler-external-link"></i>
                        </a>
                        @endif
                        <form method="POST" action="{{ route('admin.testimonials.toggle-published', $testimonial) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-icon btn-text-secondary"
                                    title="{{ $testimonial->is_published ? 'Jadikan draf' : 'Terbitkan' }}">
                                <i class="icon-base ti {{ $testimonial->is_published ? 'tabler-eye-off' : 'tabler-eye' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.testimonials.toggle-featured', $testimonial) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-icon btn-text-secondary"
                                    title="{{ $testimonial->is_featured ? 'Lepas tanda unggulan' : 'Tandai unggulan' }}">
                                <i class="icon-base ti {{ $testimonial->is_featured ? 'tabler-star-off' : 'tabler-star' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.testimonials.destroy', $testimonial)"
                            :confirm="'Hapus testimoni dari ' . $testimonial->client_name . '?'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
