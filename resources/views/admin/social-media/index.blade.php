@extends('layouts.layoutMaster')

@section('title', 'Media Sosial')

@section('content')

<x-admin.page-head
    eyebrow="Pengaturan"
    title="Media Sosial"
    subtitle="Tautan yang tampil di footer situs">
</x-admin.page-head>

<div class="row g-4">

    {{-- Existing links ------------------------------------------------------}}
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Tautan Aktif</h5></div>

            @if($links->isEmpty())
                <div class="card-body">
                    <x-admin.empty
                        icon="tabler-share"
                        title="Belum ada tautan"
                        message="Tambahkan profil media sosial agar tampil di footer." />
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Platform</th>
                            <th>Tautan</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($links as $link)
                        <tr>
                            <td>
                                <form method="POST" action="{{ route('admin.social-media.update', $link) }}"
                                      id="social{{ $link->id }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="platform" value="{{ $link->platform }}"
                                           class="form-control form-control-sm" style="max-width: 9rem;" required>
                                </form>
                            </td>
                            <td>
                                <input type="url" name="url" value="{{ $link->url }}" form="social{{ $link->id }}"
                                       class="form-control form-control-sm" required>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-flex m-0">
                                    <input type="hidden" name="is_active" value="0" form="social{{ $link->id }}">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           name="is_active" value="1" form="social{{ $link->id }}"
                                           @checked($link->is_active) aria-label="Aktif">
                                </div>
                            </td>
                            <td class="text-end text-nowrap">
                                <button type="submit" form="social{{ $link->id }}"
                                        class="btn btn-sm btn-icon btn-text-primary" title="Simpan">
                                    <i class="icon-base ti tabler-device-floppy"></i>
                                </button>
                                <x-admin.delete
                                    :action="route('admin.social-media.destroy', $link)"
                                    :confirm="'Hapus tautan ' . $link->platform . '?'" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Add new -------------------------------------------------------------}}
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Tambah Tautan</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.social-media.store') }}">
                    @csrf

                    <x-admin.form.select
                        label="Platform" name="platform" required
                        :options="['LinkedIn', 'Instagram', 'GitHub', 'Facebook', 'X', 'YouTube', 'TikTok', 'Threads']"
                        placeholder="Pilih platform"
                        help="Ikon LinkedIn, Instagram, dan GitHub sudah tersedia; lainnya memakai inisial." />

                    <x-admin.form.input
                        label="URL Profil" name="url" type="url" required
                        placeholder="https://linkedin.com/company/aldeftech" />

                    <x-admin.form.switch label="Aktifkan" name="is_active" :checked="true" />

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="icon-base ti tabler-plus me-2"></i>Tambah Tautan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
