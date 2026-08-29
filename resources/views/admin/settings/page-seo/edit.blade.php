@extends('layouts.layoutMaster')

@section('title', 'SEO Halaman — ' . $label)

@section('content')

<form method="POST" action="{{ route('admin.settings.page-seo.update', $page) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="SEO Halaman"
        :title="$label"
        :subtitle="'Meta untuk /' . ($page === 'home' ? '' : $page)">
        <a href="{{ route('admin.settings.page-seo.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    <div class="row g-4">
        @foreach($locales as $code => $meta)
            @php
                $entry = $entries[$code] ?? null;
                $path = ($code === config('locales.default') ? '' : '/' . $code)
                      . ($page === 'home' ? '' : '/' . $page);
            @endphp

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center gap-2">
                        <span class="badge bg-label-primary">{{ $meta['short'] }}</span>
                        <div>
                            <h5 class="card-title mb-0">{{ $meta['native'] }}</h5>
                            <small class="text-body-secondary">{{ config('app.url') }}{{ $path ?: '/' }}</small>
                        </div>
                    </div>

                    <div class="card-body" data-seo-preview>
                        <div class="mb-4">
                            <label class="form-label" for="title_{{ $code }}">Judul Halaman</label>
                            <input type="text" class="form-control" id="title_{{ $code }}"
                                   name="seo[{{ $code }}][meta_title]"
                                   value="{{ old('seo.'.$code.'.meta_title', $entry?->meta_title) }}"
                                   maxlength="255"
                                   data-seo-title data-seo-ideal="60">
                            <div class="form-text d-flex justify-content-between">
                                <span>Ideal 50–60 karakter.</span>
                                <span data-seo-count-title>0</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="desc_{{ $code }}">Deskripsi</label>
                            <textarea class="form-control" id="desc_{{ $code }}" rows="3"
                                      name="seo[{{ $code }}][meta_description]"
                                      maxlength="500"
                                      data-seo-desc data-seo-ideal="155">{{ old('seo.'.$code.'.meta_description', $entry?->meta_description) }}</textarea>
                            <div class="form-text d-flex justify-content-between">
                                <span>Ideal 140–160 karakter.</span>
                                <span data-seo-count-desc>0</span>
                            </div>
                        </div>

                        {{-- Roughly how the entry will read on a results page. --}}
                        <div class="aldef-serp" aria-hidden="true">
                            <div class="aldef-serp-url">{{ str_replace(['https://', 'http://'], '', config('app.url')) }}{{ $path }}</div>
                            <div class="aldef-serp-title" data-seo-serp-title>—</div>
                            <div class="aldef-serp-desc" data-seo-serp-desc>—</div>
                        </div>

                        <hr class="my-4">

                        <x-admin.form.image
                            label="Gambar Berbagi (opsional)"
                            :name="'seo[' . $code . '][og_image]'"
                            :value="$entry?->og_image"
                            ratio="1200 / 630"
                            help="Tampil saat tautan dibagikan di WhatsApp dan media sosial. Kosongkan untuk memakai gambar bawaan." />

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                   id="noindex_{{ $code }}" name="seo[{{ $code }}][noindex]"
                                   @checked(old('seo.'.$code.'.noindex', $entry?->noindex))>
                            <label class="form-check-label" for="noindex_{{ $code }}">
                                Sembunyikan dari mesin pencari
                                <small class="d-block text-body-secondary">
                                    Halaman tetap bisa dibuka, tapi dikeluarkan dari indeks dan sitemap.
                                </small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</form>

@endsection
