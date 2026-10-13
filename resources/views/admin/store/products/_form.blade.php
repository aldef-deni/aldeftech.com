@php
    $product = $product ?? null;

    $sizes = old('sizes', $product?->sizes()->all() ?? []);
    $colors = old('colors', $product?->colors()->all() ?? []);

    // Availability per "size|color". After a failed submit, the boxes the admin
    // ticked win; otherwise the saved variants; a new combination starts in stock.
    if (old('variant_matrix')) {
        $availableKeys = array_fill_keys((array) old('available', []), true);
    } else {
        $availableKeys = $product
            ? $product->variants->where('is_available', true)->mapWithKeys(fn ($v) => [$v->size . '|' . $v->color => true])->all()
            : null;
    }
    $knownKeys = $product ? $product->variants->map(fn ($v) => $v->size . '|' . $v->color)->all() : [];

    $images = old('images', $product?->images->map(fn ($i) => ['type' => $i->type, 'path' => $i->path, 'alt' => $i->alt])->values()->all() ?? []);
    $primaryIndex = (int) old('primary_image', 0);
@endphp

@if($errors->any())
    <div class="alert alert-danger" role="alert">
        Periksa kembali isian yang ditandai. {{ $errors->first() }}
    </div>
@endif

<div class="row g-4">
    <div class="col-12 col-lg-8">

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Informasi Produk</h5></div>
            <div class="card-body">
                <x-admin.form.input
                    label="Nama Sepatu" name="name" id="name" :value="$product->name ?? ''" required
                    placeholder="mis. Runner Pro 2 Knit" />

                <x-admin.form.input
                    label="Slug URL" name="slug" :value="$product->slug ?? ''"
                    data-slug-from="#name" placeholder="runner-pro-2-knit"
                    :help="'Alamat halaman: ' . rtrim(config('app.url'), '/') . '/store/' . ($product->slug ?? '…') . '. Dibuat otomatis dari nama bila dikosongkan. Mengubahnya mengubah URL.'" />

                <div class="mb-4">
                    <label class="form-label">Kode Produk (SKU)</label>
                    <input type="text" class="form-control" value="{{ $product->sku ?? 'Dibuat otomatis saat disimpan' }}" readonly disabled>
                    <div class="form-text">Format ATS + tanggal (DDMMYYYY) + nomor urut 5 digit. Tidak dapat diubah.</div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <x-admin.form.select label="Brand" name="store_brand_id" :options="$brandOptions"
                            :value="$product->store_brand_id ?? null" placeholder="Tanpa brand" />
                        <a href="{{ route('admin.store.brands.create') }}" class="small d-inline-block" style="margin-top:-1rem" target="_blank" rel="noopener">+ Brand baru</a>
                    </div>
                    <x-admin.form.input col="12 col-md-6" label="Material" name="material" :value="$product->material ?? ''" placeholder="mis. Upper knit, sol karet" />
                </div>

                <x-admin.form.textarea label="Deskripsi" name="description" :value="$product->description ?? ''" :rows="6"
                    placeholder="Ceritakan kegunaan, kenyamanan, dan keunggulan sepatu ini. Jangan cantumkan harga." />
            </div>
        </div>

        {{-- ── Photos & videos ─────────────────────────────────────────── --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-1">Foto &amp; Video Produk</h5>
                <small class="text-body-secondary">
                    Hingga {{ config('aldeftech.upload.store_max_media', 50) }} foto/video per produk; pilih banyak berkas sekaligus.
                    Foto: JPG, PNG, atau WebP · maks {{ format_upload_mb(max_store_image_upload_bytes()) }} per foto, otomatis diperkecil untuk web.
                    Video: MP4 atau WebM · maks {{ format_upload_mb(max_video_upload_bytes()) }} per video.
                    Foto utama (harus foto) tampil di katalog; urutan menentukan galeri.
                </small>
            </div>
            <div class="card-body" data-store-gallery data-upload-url="{{ route('admin.uploads.store') }}"
                 data-max-total="{{ config('aldeftech.upload.store_max_media', 50) }}"
                 data-max-image-bytes="{{ max_store_image_upload_bytes() }}"
                 data-max-video-bytes="{{ max_video_upload_bytes() }}">
                <div class="row g-3" data-gallery-list>
                    @foreach($images as $index => $image)
                        @php $isVideo = ($image['type'] ?? 'image') === 'video'; @endphp
                        <div class="col-6 col-md-4 col-xl-3" data-gallery-item data-type="{{ $isVideo ? 'video' : 'image' }}">
                            <div class="border rounded p-2 h-100 d-flex flex-column gap-2">
                                <div class="ratio ratio-1x1 bg-white rounded border position-relative">
                                    @if($isVideo)
                                        <video src="{{ media_url($image['path'] ?? '') }}#t=0.1" preload="metadata" muted playsinline style="object-fit: contain;" data-gallery-preview></video>
                                        <span class="badge bg-dark position-absolute top-0 start-0 m-1" style="width:auto;height:auto;"><i class="icon-base ti tabler-video me-1"></i>Video</span>
                                    @else
                                        <img src="{{ media_url($image['path'] ?? '') }}" alt="" style="object-fit: contain;" data-gallery-preview>
                                    @endif
                                </div>
                                <input type="hidden" data-gallery-type name="images[{{ $index }}][type]" value="{{ $isVideo ? 'video' : 'image' }}">
                                <input type="hidden" data-gallery-path name="images[{{ $index }}][path]" value="{{ $image['path'] ?? '' }}">
                                <input type="text" data-gallery-alt name="images[{{ $index }}][alt]" value="{{ $image['alt'] ?? '' }}"
                                       class="form-control form-control-sm" placeholder="{{ $isVideo ? 'Keterangan video (opsional)' : 'Teks alt (opsional)' }}" maxlength="255">
                                <div class="form-check mb-0" @if($isVideo) hidden @endif>
                                    <input class="form-check-input" type="radio" name="primary_image" value="{{ $index }}" id="primary_{{ $index }}" data-gallery-primary
                                           @checked($index === $primaryIndex && ! $isVideo) @disabled($isVideo)>
                                    <label class="form-check-label small" for="primary_{{ $index }}">Foto utama</label>
                                </div>
                                <div class="d-flex gap-1 mt-auto">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-gallery-up title="Geser ke kiri"><i class="icon-base ti tabler-arrow-up"></i></button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-gallery-down title="Geser ke kanan"><i class="icon-base ti tabler-arrow-down"></i></button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger ms-auto" data-gallery-remove title="Hapus foto"><i class="icon-base ti tabler-trash"></i></button>
                                </div>
                                @error("images.{$index}.path")<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <input type="file" accept=".jpg,.jpeg,.png,.webp,.mp4,.webm,video/mp4,video/webm" multiple hidden data-gallery-file>
                <button type="button" class="btn btn-outline-primary mt-3" data-gallery-add>
                    <i class="icon-base ti tabler-photo-plus me-2"></i>Tambah foto / video
                </button>
                <div class="text-danger small mt-2" data-gallery-error hidden></div>
                @error('images')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Variants ───────────────────────────────────────────────── --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-1">Ukuran, Warna &amp; Ketersediaan</h5>
                <small class="text-body-secondary">Isi daftar ukuran dan warna (kosongkan warna bila hanya satu). Lalu centang setiap kombinasi yang tersedia; yang tidak dicentang tampil sebagai habis.</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <x-admin.form.input label="Sistem Ukuran" name="size_system" :value="$product->size_system ?? 'EU'" placeholder="EU" help="mis. EU, US, UK, atau cm." />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6" data-variant-sizes>
                        <x-admin.form.list label="Ukuran" name="sizes" :items="$sizes" placeholder="mis. 42" add-label="Tambah ukuran" />
                    </div>
                    <div class="col-12 col-md-6" data-variant-colors>
                        <x-admin.form.list label="Warna (opsional)" name="colors" :items="$colors" placeholder="mis. Hitam" add-label="Tambah warna" />
                    </div>
                </div>

                <label class="form-label">Ketersediaan per kombinasi</label>
                <div data-variant-matrix
                     data-available="{{ json_encode($availableKeys === null ? null : array_keys($availableKeys)) }}"
                     data-known="{{ json_encode($knownKeys) }}">
                    {{-- Built by aldef-store-admin.js from the lists above. --}}
                    <div class="text-body-secondary small">Isi daftar ukuran atau warna untuk menampilkan tabel ketersediaan.</div>
                </div>

                <div class="mt-4">
                    <x-admin.form.textarea label="Panduan Ukuran (opsional)" name="size_guide" :value="$product->size_guide ?? ''" :rows="4"
                        placeholder="mis. 42 EU = panjang kaki 26,5 cm. Isi hanya dengan data yang Anda miliki."
                        help="Tampil di bawah pilihan ukuran. Jangan isi konversi ukuran yang belum dipastikan." />
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">SEO</h5></div>
            <div class="card-body">
                <x-admin.form.input label="Meta Title" name="meta_title" :value="$product->meta_title ?? ''"
                    help="Kosongkan untuk memakai: Nama — Merek | Aldef Store." />
                <x-admin.form.textarea label="Meta Description" name="meta_description" :value="$product->meta_description ?? ''" :rows="3"
                    help="Kosongkan untuk memakai ringkasan deskripsi." />
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Publikasi</h5></div>
            <div class="card-body">
                <x-admin.form.select
                    label="Status" name="status" required
                    :options="['draft' => 'Draf — tidak tampil di situs', 'published' => 'Terbit — tampil di katalog']"
                    :value="$product->status ?? 'draft'" />

                <x-admin.form.switch
                    label="Produk unggulan" name="is_featured"
                    :checked="$product->is_featured ?? false"
                    help="Produk unggulan tampil paling depan di katalog." />

                <x-admin.form.input
                    label="Urutan Tampil" name="sort_order" type="number" min="0" :value="$product->sort_order ?? 0"
                    help="Angka kecil tampil lebih dulu." />
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Kategori</h5></div>
            <div class="card-body">
                <x-admin.form.select
                    label="Kategori" name="store_category_id"
                    :options="$categoryOptions"
                    :value="$product->store_category_id ?? null"
                    placeholder="Tanpa kategori" />
                <a href="{{ route('admin.store.categories.create') }}" class="small" target="_blank" rel="noopener">+ Kategori baru</a>
            </div>
        </div>
    </div>
</div>
