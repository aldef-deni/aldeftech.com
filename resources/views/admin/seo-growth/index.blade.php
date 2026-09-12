@extends('layouts.layoutMaster')
@section('title', 'SEO Growth')
@section('content')
<x-admin.page-head eyebrow="SEO &amp; Pemasaran" title="ALDEF SEO Growth Engine" subtitle="Peluang editorial, internal linking dan distribusi konten.">
    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-primary">Artikel</a>
</x-admin.page-head>
@error('seo')<div class="alert alert-danger" role="alert">{{ $message }}</div>@enderror
@if(!$ready)
<div class="alert alert-info">SEO Growth belum siap. Jalankan migrasi penambahan tabel SEO Growth.</div>
@else
@include('admin.seo-growth.activity')
<div class="alert alert-info">
    SEO Growth {{ config('seo_growth.enabled') ? 'aktif' : 'nonaktif' }}.
    Skor relevansi adalah penilaian internal, bukan DA/DR. Metrik authority, traffic dan Search Console belum tersedia.
    Outreach dan distribusi hanya draf; tidak ada pengiriman atau penempatan tautan otomatis.
</div>
<div class="row g-4 mb-4">
@foreach($stats as $label => $value)
    <div class="col-6 col-lg-4"><div class="card h-100"><div class="card-body"><h4>{{ $value }}</h4><span>{{ $label }}</span></div></div></div>
@endforeach
</div>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Backlink Prospects</h5></div>
    <div class="card-body">
    @forelse($prospects as $prospect)
        <div class="border-bottom pb-4 mb-4" id="prospect-{{ $prospect->id }}">
            <h6><a href="{{ $prospect->url }}" target="_blank" rel="noopener noreferrer">{{ $prospect->website_name }}</a></h6>
            <p class="text-break">{{ $prospect->url }}</p>
            <p>{{ $prospect->category }} · Relevansi internal: {{ $prospect->relevance_score }}/100 ·
                Authority: {{ $prospect->metrics_provider ? $prospect->authority_score . ' (' . $prospect->metrics_provider . ')' : 'unknown' }} · Status: {{ $prospect->status }}</p>
            @if($prospect->post)
                <p>Artikel tujuan: <a href="{{ route('admin.blog.edit', $prospect->post) }}">{{ $prospect->post->title }}</a></p>
            @endif
            <p>{{ $prospect->suggested_pitch }}</p>
            <details class="mb-3"><summary>Bukti sumber dan penilaian</summary>
                <p class="mt-2">{{ data_get($prospect->evidence, 'source.evidence') }}</p>
                <p>URL diperiksa: {{ $prospect->verified_at?->format('d M Y H:i') }}. Penerimaan kontribusi tetap perlu konfirmasi.</p>
                @foreach(data_get($prospect->evidence, 'search.metadata.groundingChunks', []) as $chunk)
                    @if(filter_var(data_get($chunk, 'web.uri'), FILTER_VALIDATE_URL) && in_array(parse_url(data_get($chunk, 'web.uri'), PHP_URL_SCHEME), ['https', 'http'], true))
                    <div><a href="{{ data_get($chunk, 'web.uri') }}" target="_blank" rel="noopener noreferrer">{{ data_get($chunk, 'web.title', 'Sumber') }}</a></div>
                    @endif
                @endforeach
                @if($suggestions = data_get($prospect->evidence, 'search.metadata.searchEntryPoint.renderedContent'))
                <iframe title="Saran pencarian Google" sandbox referrerpolicy="no-referrer" class="w-100 border-0" srcdoc="{{ $suggestions }}"></iframe>
                @endif
            </details>
            <form method="POST" action="{{ route('admin.seo-growth.prospect', $prospect) }}" class="mb-3">
                @csrf @method('PATCH')
                <label class="form-label" for="prospect-status-{{ $prospect->id }}">Status prospek</label>
                <select class="form-select mb-2" name="status" id="prospect-status-{{ $prospect->id }}">
                    @foreach(\App\Models\BacklinkProspect::STATUSES as $status)<option value="{{ $status }}" @selected($prospect->status === $status)>{{ $status }}</option>@endforeach
                </select>
                <label class="form-label" for="prospect-notes-{{ $prospect->id }}">Catatan review</label>
                <textarea class="form-control mb-2" name="notes" id="prospect-notes-{{ $prospect->id }}" rows="2" maxlength="5000">{{ $prospect->notes }}</textarea>
                <button class="btn btn-sm btn-outline-primary">Simpan review</button>
            </form>
            @if($prospect->outreach)
                <details><summary>Draf outreach</summary><h6 class="mt-3">{{ $prospect->outreach->subject }}</h6>
                    <textarea class="form-control mb-3" rows="8" readonly aria-label="Draf outreach">{{ $prospect->outreach->body }}</textarea>
                    @if($guest = $prospect->outreach->guest_post)
                        <h6>Usulan guest post: {{ $guest['title'] }}</h6><p>Audiens: {{ $guest['audience'] }}</p>
                        <ul>@foreach($guest['outline'] as $section)<li>{{ $section }}</li>@endforeach</ul>
                        <p>{{ $guest['anchor'] }} → {{ $guest['destination'] }}</p>
                    @endif
                </details>
            @elseif($prospect->status === 'approved')
                <form method="POST" action="{{ route('admin.seo-growth.outreach', $prospect) }}">@csrf<button class="btn btn-sm btn-primary">Buat draf outreach</button></form>
            @endif
        </div>
    @empty<p>Belum ada prospek terverifikasi dari pencarian.</p>@endforelse
    {{ $prospects->withQueryString()->links() }}
    </div>
</div>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Content Opportunities &amp; Clusters</h5></div>
    <div class="card-body">
    @forelse($opportunities as $opportunity)
        <div class="border-bottom pb-3 mb-3" id="opportunity-{{ $opportunity->id }}">
            <h6>{{ $opportunity->topic }}</h6><p>{{ $opportunity->category }} · Cluster: {{ $opportunity->cluster }} · Prioritas: {{ $opportunity->priority }}/5 · {{ $opportunity->status }}</p>
            <p>Keyword: {{ $opportunity->keyword }}</p><p>{{ $opportunity->reason }}</p>
            @if($opportunity->pillar_blog_post_id)<p><a href="{{ route('admin.blog.edit', $opportunity->pillar_blog_post_id) }}">Tinjau artikel pilar</a></p>@endif
            <a class="btn btn-sm btn-outline-primary mb-2" href="{{ route('admin.blog.ai.create', ['topic' => $opportunity->topic, 'primary_keyword' => $opportunity->keyword]) }}">Buka generator draf</a>
            <form method="POST" action="{{ route('admin.seo-growth.opportunity', $opportunity) }}" class="d-flex gap-2">
                @csrf @method('PATCH')
                <select class="form-select" name="status" aria-label="Status peluang {{ $opportunity->id }}">
                    @foreach(['new','reviewed','used','rejected'] as $status)<option value="{{ $status }}" @selected($opportunity->status === $status)>{{ $status }}</option>@endforeach
                </select><button class="btn btn-sm btn-outline-primary">Simpan</button>
            </form>
        </div>
    @empty<p>Belum ada peluang konten. Jadwal harian menyiapkan ide untuk review.</p>@endforelse
    {{ $opportunities->withQueryString()->links() }}
    </div>
</div>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Content Refresh &amp; SEO Monitoring</h5></div>
    <div class="card-body">
    @forelse($reviews as $review)
        <div class="border-bottom pb-3 mb-3">
            <h6>{{ $review->post?->title ?? 'Artikel tidak tersedia' }}</h6><p class="text-break">{{ $review->page_url }}</p>
            <p>Analisis: {{ $review->analyzed_at?->format('d M Y') }} · Review berikut: {{ $review->next_review_at?->format('d M Y') }}</p>
            <ul>@foreach($review->recommendations['checks'] ?? [] as $check)<li>{{ $check }}</li>@endforeach</ul>
            @if($draft = data_get($review->recommendations, 'editorial_draft'))
                <p>Usulan meta title: {{ $draft['meta_title'] }}</p><p>Usulan meta description: {{ $draft['meta_description'] }}</p>
                <ul>@foreach($draft['recommendations'] as $item)<li>{{ $item }}</li>@endforeach</ul>
            @endif
            <p>Halaman terkait untuk internal link:</p><ul>
            @foreach($review->recommendations['related_pages'] ?? [] as $page)<li>{{ $page['title'] }} — {{ $page['url'] }}</li>@endforeach
            </ul>
            <p>{{ $review->recommendations['inbound_note'] ?? '' }}</p>
            @if($review->post)<a class="btn btn-sm btn-outline-primary mb-2" href="{{ route('admin.blog.edit', $review->post) }}">Tinjau di editor artikel</a>@endif
            <form method="POST" action="{{ route('admin.seo-growth.review', $review) }}">
                @csrf @method('PATCH')
                <label class="form-label" for="keyword-{{ $review->id }}">Target keyword</label>
                <input class="form-control mb-2" name="target_keyword" id="keyword-{{ $review->id }}" maxlength="150" value="{{ $review->target_keyword }}">
                <select class="form-select mb-2" name="optimization_status" aria-label="Status review {{ $review->id }}">
                    @foreach(['review_needed','reviewed'] as $status)<option value="{{ $status }}" @selected($review->optimization_status === $status)>{{ $status }}</option>@endforeach
                </select><button class="btn btn-sm btn-outline-primary">Simpan review</button>
            </form>
        </div>
    @empty<p>Belum ada analisis artikel.</p>@endforelse
    {{ $reviews->withQueryString()->links() }}
    </div>
</div>
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Distribution Packs</h5></div>
    <div class="card-body">
    @forelse($distributions as $pack)
        <details class="mb-3" id="pack-{{ $pack->id }}" @if(request('pack_id')) open @endif><summary>{{ $pack->title }} · {{ $pack->status }}</summary>
            <p class="mt-3">{{ $pack->excerpt }}</p><p class="text-break">URL artikel: {{ $pack->content }}</p>
            @foreach($pack->platform_posts ?? [] as $platform => $copy)
            <h6>{{ ucfirst($platform) }}</h6>
            <textarea class="form-control mb-3" rows="6" readonly aria-label="Draf {{ $platform }}">{{ implode("\n\n", $copy) }}</textarea>
            @endforeach
        </details>
    @empty<p>Belum ada paket distribusi.</p>@endforelse
    {{ $distributions->withQueryString()->links() }}
    </div>
</div>
<div class="card"><div class="card-header"><h5 class="mb-0">Aktivitas &amp; Ringkasan Mingguan</h5></div><div class="card-body">
    @forelse($runs as $run)
    <details class="mb-2" id="run-{{ $run->id }}" @if(request('run_id')) open @endif><summary>{{ $run->task }} · {{ $run->created_at->format('d M Y H:i') }} · {{ $run->status }}</summary>
        <pre class="text-wrap text-break mt-2">{{ json_encode($run->result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
    </details>
    @empty<p>Belum ada pekerjaan SEO Growth.</p>@endforelse
</div></div>
@endif
@endsection
