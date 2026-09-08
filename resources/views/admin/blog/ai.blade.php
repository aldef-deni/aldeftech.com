@extends('layouts.layoutMaster')

@section('title', 'Generate Artikel dengan AI')

@section('content')
<form method="POST" action="{{ route('admin.blog.ai.store') }}" id="ai-article-form">
    @csrf
    <x-admin.page-head eyebrow="Blog" title="Generate Artikel dengan AI" :back="route('admin.blog.index')">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary" id="ai-generate-button">Buat Draf Artikel</button>
    </x-admin.page-head>

    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">Artikel disimpan sebagai draf dan dibuka di editor untuk diperiksa. Generator menggunakan uraian kualitatif tanpa angka atau klaim riset. Tambahkan statistik hanya setelah memverifikasi sumbernya di editor.</div>
            @error('generation')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror
            <x-admin.form.textarea label="Topik Artikel" name="topic" :value="$defaults['topic'] ?? ''" required :rows="3" placeholder="Contoh: Manfaat otomasi proses bisnis" />
            <x-admin.form.input label="Keyword Utama" name="primary_keyword" :value="$defaults['primary_keyword'] ?? ''" />
            <x-admin.form.textarea label="Keyword Tambahan" name="secondary_keywords" :rows="2" help="Pisahkan keyword dengan koma." />
            <x-admin.form.select label="Kategori" name="category_id"
                :options="$categories->pluck('name', 'id')->all()" placeholder="Tanpa kategori" />
            <x-admin.form.input label="Target Jumlah Kata" name="target_words" type="number" :value="1600" min="500" max="2500" required help="Antara 500–2500 kata. Panjang hasil dapat berbeda." />
            <p class="text-body-secondary mb-0" id="ai-generation-status" role="status">Pembuatan artikel dapat membutuhkan beberapa menit.</p>
        </div>
    </div>
</form>
<script>
document.getElementById('ai-article-form').addEventListener('submit', function (event) {
    if (this.dataset.generating === 'true') {
        event.preventDefault();
        return;
    }
    this.dataset.generating = 'true';
    this.setAttribute('aria-busy', 'true');
    document.getElementById('ai-generate-button').disabled = true;
    document.getElementById('ai-generate-button').textContent = 'Sedang membuat draf…';
    document.getElementById('ai-generation-status').textContent = 'Sedang membuat draf. Tunggu hingga editor terbuka.';
});
window.addEventListener('pageshow', function () {
    const form = document.getElementById('ai-article-form');
    form.dataset.generating = 'false';
    form.removeAttribute('aria-busy');
    const button = document.getElementById('ai-generate-button');
    button.disabled = false;
    button.textContent = 'Buat Draf Artikel';
    document.getElementById('ai-generation-status').textContent = 'Pembuatan artikel dapat membutuhkan beberapa menit.';
});
</script>
@endsection
