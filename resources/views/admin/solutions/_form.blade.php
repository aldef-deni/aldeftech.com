@php $solution = $solution ?? null; @endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Detail Solusi</h5></div>
            <div class="card-body">
                <x-admin.form.input
                    label="Judul" name="title" :value="$solution->title ?? ''" required
                    placeholder="mis. ERP & Business Core System" />

                <x-admin.form.textarea
                    label="Deskripsi Singkat" name="short_description" :value="$solution->short_description ?? ''" required
                    :rows="3" placeholder="Kalimat ringkas yang muncul di kartu solusi"
                    help="Tampil di beranda dan halaman Solusi." />

                <x-admin.form.textarea
                    label="Deskripsi Lengkap" name="description" :value="$solution->description ?? ''"
                    :rows="6" placeholder="Penjelasan menyeluruh tentang solusi ini" />

                <x-admin.form.list
                    label="Modul / Kemampuan" name="features"
                    :items="$solution->features ?? []"
                    placeholder="mis. Multi-Company & Multi-Branch"
                    help="Tampil sebagai daftar bercentang di kartu solusi." />
            </div>
        </div>

        <x-admin.form.translation
            :model="$solution"
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
                    label="Meta Title" name="meta_title" :value="$solution->meta_title ?? ''"
                    placeholder="Judul untuk mesin pencari" />
                <x-admin.form.textarea
                    label="Meta Description" name="meta_description" :value="$solution->meta_description ?? ''"
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
                    :checked="$solution->is_published ?? true"
                    help="Nonaktifkan untuk menyimpan sebagai draf." />

                <x-admin.form.input
                    label="Urutan" name="sort_order" type="number" :value="$solution->sort_order ?? 0"
                    help="Angka kecil tampil lebih dulu." />

                <x-admin.form.input
                    label="Ikon" name="icon" :value="$solution->icon ?? ''"
                    placeholder="🏢"
                    help="Emoji diterjemahkan otomatis menjadi ikon garis di situs." />
            </div>
        </div>
    </div>
</div>
