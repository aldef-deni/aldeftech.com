@php $testimonial = $testimonial ?? null; @endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Testimoni</h5></div>
            <div class="card-body">
                <div class="row">
                    <x-admin.form.input
                        col="12 col-md-6" label="Nama Klien" name="client_name"
                        :value="$testimonial->client_name ?? ''" required placeholder="Nama lengkap" />

                    <x-admin.form.input
                        col="12 col-md-6" label="Perusahaan" name="company"
                        :value="$testimonial->company ?? ''" placeholder="Nama perusahaan" />
                </div>

                <x-admin.form.input
                    label="Jabatan" name="position" :value="$testimonial->position ?? ''"
                    placeholder="mis. Direktur Operasional" />

                <x-admin.form.textarea
                    label="Isi Testimoni" name="testimonial" :value="$testimonial->testimonial ?? ''" required
                    :rows="6" placeholder="Kutipan dari klien"
                    help="Maksimal 2000 karakter. Kutipan pendek dan spesifik lebih meyakinkan." />
            </div>
        </div>

        <x-admin.form.translation
            :model="$testimonial"
            :fields="[
                'position'    => ['label' => 'Jabatan (English)', 'type' => 'text'],
                'testimonial' => ['label' => 'Isi Testimoni (English)', 'type' => 'textarea', 'rows' => 6],
            ]" />
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Publikasi</h5></div>
            <div class="card-body">
                <x-admin.form.switch
                    label="Tampilkan di situs" name="is_published"
                    :checked="$testimonial->is_published ?? true" />

                <x-admin.form.select
                    label="Rating" name="rating"
                    :options="[5 => '5 bintang', 4 => '4 bintang', 3 => '3 bintang', 2 => '2 bintang', 1 => '1 bintang']"
                    :value="$testimonial->rating ?? 5" required />

                <x-admin.form.input
                    label="Urutan" name="sort_order" type="number" :value="$testimonial->sort_order ?? 0"
                    help="Angka kecil tampil lebih dulu." />

                <x-admin.form.input
                    label="Foto" name="photo" :value="$testimonial->photo ?? ''"
                    placeholder="images/team/nama.jpg"
                    help="Path atau URL foto. Unggah lewat menu Media, lalu salin path-nya ke sini." />

                @if(!empty($testimonial?->photo) && ($src = media_url($testimonial->photo)))
                <img src="{{ $src }}" alt="" class="aldef-thumb-lg mt-2">
                @endif
            </div>
        </div>
    </div>
</div>
