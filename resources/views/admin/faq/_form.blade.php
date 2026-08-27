@php
    $faq = $faq ?? null;

    // Reuse whatever categories already exist so the list stays tidy.
    try {
        $faqCategories = \App\Models\Faq::query()
            ->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category')->all();
    } catch (\Throwable $e) {
        $faqCategories = [];
    }
@endphp

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Pertanyaan &amp; Jawaban</h5></div>
            <div class="card-body">
                <x-admin.form.textarea
                    label="Pertanyaan" name="question" :value="$faq->question ?? ''" required
                    :rows="2" placeholder="mis. Berapa lama pengerjaan sistem custom?"
                    help="Tulis persis seperti calon klien menanyakannya." />

                <x-admin.form.textarea
                    label="Jawaban" name="answer" :value="$faq->answer ?? ''" required
                    :rows="8" placeholder="Jawaban lengkap dan spesifik" />
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Pengaturan</h5></div>
            <div class="card-body">
                <x-admin.form.switch
                    label="Tampilkan di situs" name="is_published"
                    :checked="$faq->is_published ?? true" />

                <div class="mb-4">
                    <label for="category" class="form-label">Kategori</label>
                    <input type="text" id="category" name="category" list="faq-categories"
                           value="{{ old('category', $faq->category ?? '') }}"
                           class="form-control @error('category') is-invalid @enderror"
                           placeholder="mis. Umum, Teknis, Keamanan">
                    <datalist id="faq-categories">
                        @foreach($faqCategories as $cat)
                            <option value="{{ $cat }}"></option>
                        @endforeach
                    </datalist>
                    @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <div class="form-text">Dipakai sebagai penyaring di halaman FAQ.</div>
                </div>

                <x-admin.form.input
                    label="Urutan" name="sort_order" type="number" :value="$faq->sort_order ?? 0"
                    help="Angka kecil tampil lebih dulu." />
            </div>
        </div>
    </div>
</div>
