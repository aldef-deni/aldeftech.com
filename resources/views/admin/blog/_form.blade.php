@php
    $post = $post ?? null;
    $selectedTags = old('tags', $post?->tags?->pluck('id')->all() ?? []);
@endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Isi Artikel</h5></div>
            <div class="card-body">
                <x-admin.form.input
                    label="Judul" name="title" :value="$post->title ?? ''" required
                    placeholder="Judul artikel" />

                <x-admin.form.textarea
                    label="Ringkasan" name="excerpt" :value="$post->excerpt ?? ''" :rows="3"
                    placeholder="Satu-dua kalimat yang muncul di kartu artikel dan hasil pencarian" />

                <x-admin.form.textarea
                    label="Konten" name="content" :value="$post->content ?? ''" required :rows="18"
                    placeholder="Tulis artikel di sini"
                    help="Mendukung HTML. Gunakan <h2>, <p>, <ul>, dan <blockquote> agar tampil rapi di situs."
                    class="font-monospace" />
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">SEO</h5></div>
            <div class="card-body">
                <x-admin.form.input label="Meta Title" name="meta_title" :value="$post->meta_title ?? ''" />
                <x-admin.form.textarea label="Meta Description" name="meta_description" :value="$post->meta_description ?? ''" :rows="3" />
                <x-admin.form.input
                    label="Canonical URL" name="canonical_url" type="url" :value="$post->canonical_url ?? ''"
                    placeholder="https://..."
                    help="Isi hanya jika artikel ini juga terbit di tempat lain." />
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Publikasi</h5></div>
            <div class="card-body">
                <x-admin.form.select
                    label="Status" name="status" required
                    :options="['draft' => 'Draf', 'published' => 'Terbit', 'scheduled' => 'Terjadwal']"
                    :value="$post->status ?? 'draft'" />

                <x-admin.form.input
                    label="Tanggal Terbit" name="published_at" type="datetime-local"
                    :value="$post?->published_at?->format('Y-m-d\TH:i') ?? ''"
                    help="Dikosongkan berarti memakai waktu saat disimpan." />

                <x-admin.form.select
                    label="Kategori" name="category_id"
                    :options="$categories->pluck('name', 'id')->all()"
                    :value="$post->category_id ?? null"
                    placeholder="Tanpa kategori" />
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Tag</h5></div>
            <div class="card-body">
                @if($tags->isEmpty())
                    <p class="text-body-secondary mb-2">Belum ada tag.</p>
                    <a href="{{ route('admin.tags.create') }}" class="btn btn-sm btn-outline-primary">Buat tag</a>
                @else
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                    <div class="form-check form-check-inline m-0">
                        <input class="form-check-input" type="checkbox" name="tags[]"
                               value="{{ $tag->id }}" id="tag{{ $tag->id }}"
                               @checked(in_array($tag->id, $selectedTags))>
                        <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Gambar Utama</h5></div>
            <div class="card-body">
                @if($src = media_url($post->featured_image ?? null))
                <img src="{{ $src }}" alt="" class="aldef-thumb-lg mb-3">
                @endif

                <x-admin.form.input
                    label="Path Gambar" name="featured_image" :value="$post->featured_image ?? ''"
                    placeholder="images/blog/nama.webp"
                    help="Unggah lewat menu Media, lalu salin path-nya ke sini." />
            </div>
        </div>
    </div>
</div>
