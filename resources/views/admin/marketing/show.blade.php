@extends('layouts.layoutMaster')

@section('title', 'Kampanye — ' . $campaign->name)

@php
    $statusTone = ['active' => 'success', 'draft' => 'secondary', 'paused' => 'warning', 'completed' => 'info'];
    $statusLabel = ['active' => 'Aktif', 'draft' => 'Draf', 'paused' => 'Dijeda', 'completed' => 'Selesai'];

    $contentTone = ['published' => 'success', 'approved' => 'info', 'draft' => 'secondary'];
    $contentLabel = ['published' => 'Terbit', 'approved' => 'Disetujui', 'draft' => 'Draf'];

    $stageLabel = ['awareness' => 'Awareness', 'consideration' => 'Consideration', 'decision' => 'Decision'];
@endphp

@section('content')

<x-admin.page-head
    eyebrow="AI Marketing"
    :title="$campaign->name"
    :subtitle="$campaign->objective ?: $campaign->description"
    :back="route('admin.marketing.index')">
    <form method="POST" action="{{ route('admin.marketing.generate-ideas', $campaign) }}" class="d-flex align-items-center gap-2">
        @csrf
        <input type="number" name="limit" value="12" min="1" max="50"
               class="form-control" style="width: 5.5rem;" aria-label="Jumlah ide">
        <button type="submit" class="btn btn-primary text-nowrap">
            <i class="icon-base ti tabler-sparkles me-2"></i>Buat Ide
        </button>
    </form>
</x-admin.page-head>

{{-- Campaign meta -----------------------------------------------------------}}
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="badge bg-label-{{ $statusTone[$campaign->status] ?? 'secondary' }}">
                {{ $statusLabel[$campaign->status] ?? ucfirst((string) $campaign->status) }}
            </span>
            <span class="badge bg-label-secondary">Prioritas {{ $campaign->priority }}</span>
            @if($campaign->start_date)
            <span class="text-body-secondary small">
                {{ $campaign->start_date->translatedFormat('d M Y') }}
                @if($campaign->end_date) – {{ $campaign->end_date->translatedFormat('d M Y') }} @endif
            </span>
            @endif
        </div>

        @if(!empty($campaign->platforms))
        <div class="d-flex flex-wrap gap-2">
            @foreach($campaign->platforms as $platform)
                <span class="badge bg-label-primary">{{ ucfirst($platform) }}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Ideas -------------------------------------------------------------------}}
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Ide Konten</h5>
        <span class="badge bg-label-secondary">{{ $campaign->contentIdeas->count() }} ide</span>
    </div>

    @if($campaign->contentIdeas->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-bulb"
                title="Belum ada ide"
                message="Gunakan tombol Buat Ide di atas untuk menghasilkan ide dari audiens, pain point, dan kata kunci yang tersimpan." />
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Ide</th>
                    <th class="d-none d-lg-table-cell">Audiens</th>
                    <th class="text-center d-none d-md-table-cell">Tahap</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaign->contentIdeas as $idea)
                <tr>
                    <td>
                        <div class="fw-medium">{{ $idea->title }}</div>
                        @if($idea->hook)<small class="text-body-secondary">{{ $idea->hook }}</small>@endif
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <small class="text-body-secondary">{{ $idea->audience?->name ?: '—' }}</small>
                    </td>
                    <td class="text-center d-none d-md-table-cell">
                        <span class="badge bg-label-secondary">{{ $stageLabel[$idea->funnel_stage] ?? ucfirst((string) $idea->funnel_stage) }}</span>
                    </td>
                    <td class="text-end text-nowrap">
                        @if($idea->contents->isEmpty())
                        <form method="POST" action="{{ route('admin.marketing.generate-content', $idea) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="icon-base ti tabler-wand me-1"></i>Buat Konten
                            </button>
                        </form>
                        @else
                        <span class="badge bg-label-success">Sudah dibuat</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Generated content -------------------------------------------------------}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0">Konten Dihasilkan</h5>
    <span class="badge bg-label-secondary">{{ $contents->count() }} item</span>
</div>

@forelse($contents as $content)
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-start">
            <div class="min-w-0">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge bg-label-{{ $contentTone[$content->status] ?? 'secondary' }}">
                        {{ $contentLabel[$content->status] ?? ucfirst((string) $content->status) }}
                    </span>
                    @if($content->generated_at)
                    <small class="text-body-secondary">{{ $content->generated_at->translatedFormat('d M Y H:i') }}</small>
                    @endif
                </div>

                <h5 class="mb-1">{{ $content->title }}</h5>
                @if($content->excerpt)<p class="text-body-secondary mb-1">{{ $content->excerpt }}</p>@endif
                @if($content->seo_keywords)
                <small class="text-body-secondary">Kata kunci: {{ $content->seo_keywords }}</small>
                @endif
            </div>

            <div class="d-flex flex-wrap gap-2 flex-shrink-0">
                @if($content->status === 'draft')
                <form method="POST" action="{{ route('admin.marketing.approve-content', $content) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="icon-base ti tabler-check me-1"></i>Setujui
                    </button>
                </form>
                @endif

                @if(in_array($content->status, ['draft', 'approved']))
                <form method="POST" action="{{ route('admin.marketing.publish-content', $content) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="icon-base ti tabler-send me-1"></i>Terbitkan ke Blog
                    </button>
                </form>
                @endif

                @if($content->blogPost)
                <a href="{{ route('blog.show', $content->blogPost->slug) }}" target="_blank" rel="noopener"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="icon-base ti tabler-external-link me-1"></i>Lihat
                </a>
                @endif
            </div>
        </div>

        @if(!empty($content->distribution_checklist))
        <hr class="my-4">
        <h6 class="mb-3">Ceklis Distribusi</h6>
        <div class="row g-2">
            @foreach($content->distribution_checklist as $item)
            <div class="col-12 col-md-6">
                <div class="d-flex align-items-start gap-2">
                    <i class="icon-base ti tabler-point-filled text-primary mt-1 icon-sm"></i>
                    <small class="text-body-secondary">{{ is_array($item) ? implode(' — ', $item) : $item }}</small>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if(!empty($content->platform_posts))
        <hr class="my-4">
        <h6 class="mb-3">Naskah per Kanal</h6>
        <div class="row g-3">
            @foreach($content->platform_posts as $platform => $post)
            @php
                $body = trim(
                    ($post['hook'] ?? '') . "\n\n" .
                    ($post['caption'] ?? '') . "\n\n" .
                    ($post['hashtags'] ?? '') . "\n\n" .
                    ($post['cta'] ?? '')
                );
            @endphp
            <div class="col-12 col-lg-4">
                <div class="border rounded p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">{{ ucfirst($platform) }}</h6>
                        <button type="button" class="btn btn-sm btn-text-primary" data-copy-target>
                            <i class="icon-base ti tabler-copy me-1"></i>Salin
                        </button>
                    </div>
                    <textarea rows="8" readonly class="form-control form-control-sm font-monospace">{{ $body }}</textarea>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@empty
<div class="card">
    <div class="card-body">
        <x-admin.empty icon="tabler-article" title="Belum ada konten dihasilkan" />
    </div>
</div>
@endforelse

@endsection

@section('page-script')
<script>
  document.querySelectorAll('[data-copy-target]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var textarea = btn.closest('.border').querySelector('textarea');
      if (!textarea) return;

      var done = function () {
        var original = btn.innerHTML;
        btn.innerHTML = '<i class="icon-base ti tabler-check me-1"></i>Tersalin';
        setTimeout(function () { btn.innerHTML = original; }, 1600);
      };

      if (navigator.clipboard) {
        navigator.clipboard.writeText(textarea.value).then(done);
      } else {
        textarea.removeAttribute('readonly');
        textarea.select();
        document.execCommand('copy');
        textarea.setAttribute('readonly', 'readonly');
        done();
      }
    });
  });
</script>
@endsection
