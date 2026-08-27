@php $service = $service ?? null; @endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Detail Layanan</h5></div>
            <div class="card-body">
                <x-admin.form.input
                    label="Judul" name="title" :value="$service->title ?? ''" required
                    placeholder="mis. Custom Software Development" />

                <x-admin.form.textarea
                    label="Deskripsi Singkat" name="short_description" :value="$service->short_description ?? ''" required
                    :rows="3" placeholder="Kalimat ringkas yang muncul di kartu layanan"
                    help="Tampil di beranda dan halaman Layanan. Idealnya di bawah 160 karakter." />

                <x-admin.form.textarea
                    label="Deskripsi Lengkap" name="description" :value="$service->description ?? ''"
                    :rows="6" placeholder="Penjelasan menyeluruh tentang layanan ini" />

                <x-admin.form.list
                    label="Poin yang Didapat Klien" name="features"
                    :items="$service->features ?? []"
                    placeholder="mis. Analisis Proses Bisnis"
                    help="Tampil sebagai daftar bercentang. 4-6 poin paling enak dibaca." />
            </div>
        </div>

        <x-admin.form.translation
            :model="$service"
            :fields="[
                'title'             => ['label' => 'Judul (English)', 'type' => 'text'],
                'short_description' => ['label' => 'Deskripsi Singkat (English)', 'type' => 'textarea', 'rows' => 3],
                'description'       => ['label' => 'Deskripsi Lengkap (English)', 'type' => 'textarea', 'rows' => 6],
                'features'          => ['label' => 'Poin (English)', 'type' => 'list'],
            ]" />

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">SEO</h5></div>
            <div class="card-body">
                <x-admin.form.input
                    label="Meta Title" name="meta_title" :value="$service->meta_title ?? ''"
                    placeholder="Judul untuk mesin pencari" />
                <x-admin.form.textarea
                    label="Meta Description" name="meta_description" :value="$service->meta_description ?? ''"
                    :rows="3" placeholder="Ringkasan untuk hasil pencarian" />
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Publikasi</h5></div>
            <div class="card-body">
                <x-admin.form.switch
                    label="Tampilkan di situs" name="is_published"
                    :checked="$service->is_published ?? true"
                    help="Nonaktifkan untuk menyimpan sebagai draf." />

                <x-admin.form.input
                    label="Urutan" name="sort_order" type="number" :value="$service->sort_order ?? 0"
                    help="Angka kecil tampil lebih dulu." />

                <x-admin.form.input
                    label="Ikon" name="icon" :value="$service->icon ?? ''"
                    placeholder="⚙️"
                    help="Emoji diterjemahkan otomatis menjadi ikon garis di situs." />
            </div>
        </div>
    </div>
</div>
