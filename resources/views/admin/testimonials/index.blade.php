@extends('layouts.layoutMaster')

@section('title', 'Testimoni')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="Testimoni"
    subtitle="{{ $testimonials->count() }} testimoni · {{ $testimonials->where('is_published', true)->count() }} tampil di situs">
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
<div class="row g-4">
    @foreach($testimonials as $testimonial)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="text-warning" aria-label="{{ $testimonial->rating }} dari 5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="icon-base ti {{ $i <= $testimonial->rating ? 'tabler-star-filled' : 'tabler-star' }} icon-sm"></i>
                        @endfor
                    </div>
                    <x-admin.status :published="$testimonial->is_published" />
                </div>

                <p class="text-body-secondary flex-grow-1">{{ excerpt_text($testimonial->testimonial, 200) }}</p>

                <div class="d-flex align-items-center gap-3 pt-3 mt-3 border-top">
                    @if($src = media_url($testimonial->photo))
                        <img src="{{ $src }}" alt="" class="aldef-thumb rounded-circle">
                    @else
                        <span class="avatar"><span class="avatar-initial rounded-circle bg-label-primary">{{ initials_of($testimonial->client_name) }}</span></span>
                    @endif

                    <div class="flex-grow-1 text-truncate">
                        <div class="fw-medium text-truncate">{{ $testimonial->client_name }}</div>
                        <small class="text-body-secondary text-truncate d-block">{{ trim(($testimonial->position ? $testimonial->position . ' · ' : '') . $testimonial->company, ' ·') ?: '—' }}</small>
                    </div>

                    <div class="text-nowrap">
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.testimonials.destroy', $testimonial)"
                            :confirm="'Hapus testimoni dari ' . $testimonial->client_name . '?'" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
