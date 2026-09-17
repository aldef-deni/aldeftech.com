@php $client = $client ?? null; @endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Identitas Klien</h5></div>
            <div class="card-body">
                <x-admin.form.input
                    label="Nama Klien" name="name" :value="$client->name ?? ''" required
                    placeholder="mis. Nusantara Logistik" />

                <x-admin.form.input
                    label="Website" name="website_url" :value="$client->website_url ?? ''"
                    placeholder="nusantaralogistik.co.id"
                    help="Boleh dikosongkan. Tanpa alamat, logo tidak dijadikan tautan. Alamat tanpa https:// otomatis dilengkapi." />
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Tayang</h5></div>
            <div class="card-body">
                <x-admin.form.switch
                    label="Tampilkan di beranda" name="show_home"
                    :checked="$client->show_home ?? true"
                    help="Tampil pada marquee logo di halaman utama." />

                <x-admin.form.switch
                    label="Tampilkan di halaman Tentang" name="show_about"
                    :checked="$client->show_about ?? true"
                    help="Tampil pada grid logo di halaman Tentang." />

                <x-admin.form.switch
                    label="Tandai unggulan" name="is_featured"
                    :checked="$client->is_featured ?? false"
                    help="Unggulan tampil lebih dulu, tanpa mengubah ukuran logo." />

                <x-admin.form.input
                    label="Urutan" name="sort_order" type="number" :value="$client->sort_order ?? 0"
                    help="Angka kecil tampil lebih dulu. Urutan berlaku di dalam grup unggulan." />
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Logo</h5></div>
            <div class="card-body">
                <x-admin.form.image
                    label="Logo Klien" name="logo" :value="$client->logo ?? ''" required
                    ratio="3 / 2" width="14rem" :dark="true"
                    hint="Seret logo ke sini atau klik"
                    help="PNG, JPG, atau WEBP. Tampil di atas latar gelap, jadi logo versi terang paling terbaca. Format lebar lebih pas daripada logo persegi. SVG tidak dipakai." />
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Status</h5></div>
            <div class="card-body">
                <x-admin.form.switch
                    label="Aktif" name="is_published"
                    :checked="$client->is_published ?? true"
                    help="Nonaktif menyembunyikan logo dari beranda dan halaman Tentang." />
            </div>
        </div>
    </div>
</div>
