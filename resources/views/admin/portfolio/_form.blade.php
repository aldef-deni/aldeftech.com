@php $portfolio = $portfolio ?? null; @endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Ringkasan Proyek</h5></div>
            <div class="card-body">
                <x-admin.form.input
                    label="Judul Proyek" name="title" id="title" :value="$portfolio->title ?? ''" required
                    placeholder="mis. Arahinn Mobile — OTA & Travel Platform" />

                <x-admin.form.input
                    label="Slug URL" name="slug" :value="$portfolio->slug ?? ''"
                    data-slug-from="#title"
                    placeholder="arahinn-mobile-ota-travel-platform"
                    :help="'Alamat halaman: ' . rtrim(config('app.url'), '/') . '/portfolio/' . ($portfolio->slug ?? '…') . '. Terisi otomatis dari judul saat membuat proyek baru. Mengubahnya mengubah URL — tautan lama akan mati.'" />

                <x-admin.form.textarea
                    label="Deskripsi Singkat" name="short_description" :value="$portfolio->short_description ?? ''" required
                    :rows="3" placeholder="Satu paragraf tentang apa yang dibangun dan untuk siapa"
                    help="Tampil di kartu portofolio." />

                <x-admin.form.textarea
                    label="Deskripsi Lengkap" name="description" :value="$portfolio->description ?? ''"
                    :rows="6" placeholder="Penjelasan menyeluruh untuk halaman studi kasus" />

                <x-admin.form.list
                    label="Teknologi" name="technologies"
                    :items="$portfolio->technologies ?? []"
                    placeholder="mis. Laravel 11"
                    add-label="Tambah teknologi" />
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-1">Narasi Studi Kasus</h5>
                <small class="text-body-secondary">Bagian ini yang membuat portofolio menjual. Isi seadanya bila belum lengkap.</small>
            </div>
            <div class="card-body">
                <x-admin.form.textarea
                    label="Tantangan" name="challenge" :value="$portfolio->challenge ?? ''" :rows="4"
                    placeholder="Masalah apa yang dihadapi klien sebelum sistem ini ada?" />
                <x-admin.form.textarea
                    label="Pendekatan" name="approach" :value="$portfolio->approach ?? ''" :rows="4"
                    placeholder="Bagaimana Anda memutuskan cara menyelesaikannya?" />
                <x-admin.form.textarea
                    label="Solusi" name="solution" :value="$portfolio->solution ?? ''" :rows="4"
                    placeholder="Apa yang akhirnya dibangun?" />
                <x-admin.form.textarea
                    label="Hasil" name="results" :value="$portfolio->results ?? ''" :rows="4"
                    placeholder="Angka konkret paling meyakinkan: waktu proses turun, kapasitas naik, biaya berkurang." />
            </div>
        </div>

        <x-admin.form.translation
            :model="$portfolio"
            :fields="[
                'title'             => ['label' => 'Judul Proyek (English)', 'type' => 'text'],
                'short_description' => ['label' => 'Deskripsi Singkat (English)', 'type' => 'textarea', 'rows' => 3],
                'description'       => ['label' => 'Deskripsi Lengkap (English)', 'type' => 'textarea', 'rows' => 6],
                'challenge'         => ['label' => 'Tantangan (English)', 'type' => 'textarea', 'rows' => 4],
                'approach'          => ['label' => 'Pendekatan (English)', 'type' => 'textarea', 'rows' => 4],
                'solution'          => ['label' => 'Solusi (English)', 'type' => 'textarea', 'rows' => 4],
                'results'           => ['label' => 'Hasil (English)', 'type' => 'textarea', 'rows' => 4],
            ]" />

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">SEO</h5></div>
            <div class="card-body">
                <x-admin.form.input label="Meta Title" name="meta_title" :value="$portfolio->meta_title ?? ''" />
                <x-admin.form.textarea label="Meta Description" name="meta_description" :value="$portfolio->meta_description ?? ''" :rows="3" />
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Publikasi</h5></div>
            <div class="card-body">
                <x-admin.form.switch
                    label="Tampilkan di situs" name="is_published"
                    :checked="$portfolio->is_published ?? true" />

                <x-admin.form.switch
                    label="Tampilkan di beranda" name="is_featured"
                    :checked="$portfolio->is_featured ?? false"
                    help="Beranda menampilkan tiga proyek unggulan." />

                <x-admin.form.input
                    label="Tanggal Terbit" name="published_at" type="datetime-local"
                    :value="$portfolio?->published_at?->format('Y-m-d\TH:i') ?? ''" />

                <x-admin.form.input
                    label="Urutan" name="sort_order" type="number" :value="$portfolio->sort_order ?? 0" />
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Identitas</h5></div>
            <div class="card-body">
                <x-admin.form.select
                    label="Kategori" name="category_id"
                    :options="$categories->pluck('name', 'id')->all()"
                    :value="$portfolio->category_id ?? null"
                    placeholder="Tanpa kategori" />

                <x-admin.form.input
                    label="Klien" name="client" :value="$portfolio->client ?? ''"
                    placeholder="Nama klien atau 'Proyek Internal'" />

                <x-admin.form.input
                    label="Tahun" name="year" :value="$portfolio->year ?? ''" placeholder="2024" />

                <x-admin.form.input
                    label="Tautan Proyek" name="project_url" type="url" :value="$portfolio->project_url ?? ''"
                    placeholder="https://..."
                    help="Situs milik klien. Bukan demo." />
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-1">Demo</h5>
                <small class="text-body-secondary">
                    Isi Tautan Demo untuk memunculkan tombol &ldquo;Coba Demo&rdquo; di halaman ini.
                    Dikosongkan berarti proyek ini tidak menampilkan apa pun soal demo.
                </small>
            </div>
            <div class="card-body">
                <x-admin.form.input
                    label="Tautan Demo" name="demo_url" type="url" :value="$portfolio->demo_url ?? ''"
                    placeholder="https://demo.aldeftech.com/inventory"
                    help="Satu-satunya penentu apakah proyek ini punya demo." />

                <div class="row">
                    <x-admin.form.input
                        col="12 col-md-6" label="Username Demo" name="demo_username"
                        :value="$portfolio->demo_username ?? ''"
                        placeholder="demo" help="Kosongkan bila demo tidak perlu login." />

                    <x-admin.form.input
                        col="12 col-md-6" label="Password Demo" name="demo_password"
                        :value="$portfolio->demo_password ?? ''"
                        placeholder="demo123" />
                </div>

                <x-admin.form.textarea
                    label="Catatan Demo" name="demo_note" :value="$portfolio->demo_note ?? ''" :rows="3"
                    placeholder="mis. Data direset otomatis setiap 24 jam."
                    help="Opsional. Tampil di dalam modal sebagai pengingat bagi pengunjung." />
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Gambar Utama</h5></div>
            <div class="card-body">
                <x-admin.form.image
                    label="Gambar Utama" name="featured_image" :value="$portfolio->featured_image ?? ''"
                    help="Tampil di kartu portofolio dan halaman studi kasus. Rasio 16:10." />
            </div>
        </div>
    </div>
</div>
