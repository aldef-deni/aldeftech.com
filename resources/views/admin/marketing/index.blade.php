@extends('layouts.layoutMaster')

@section('title', 'AI Marketing')

@php
    $statusTone = [
        'active' => 'success', 'draft' => 'secondary',
        'paused' => 'warning', 'completed' => 'info',
    ];
    $statusLabel = [
        'active' => 'Aktif', 'draft' => 'Draf',
        'paused' => 'Dijeda', 'completed' => 'Selesai',
    ];

    $tiles = [
        ['label' => 'Kampanye',        'value' => $stats['campaigns'],        'icon' => 'tabler-target',      'tone' => 'primary'],
        ['label' => 'Kampanye Aktif',  'value' => $stats['active_campaigns'], 'icon' => 'tabler-player-play', 'tone' => 'success'],
        ['label' => 'Ide Konten',      'value' => $stats['ideas'],            'icon' => 'tabler-bulb',        'tone' => 'info'],
        ['label' => 'Draf Konten',     'value' => $stats['drafts'],           'icon' => 'tabler-file-pencil', 'tone' => 'warning'],
        ['label' => 'Sudah Terbit',    'value' => $stats['published'],        'icon' => 'tabler-send',        'tone' => 'primary'],
    ];
@endphp

@section('content')

<x-admin.page-head
    eyebrow="CRM &amp; Pemasaran"
    title="AI Marketing"
    subtitle="Rencanakan kampanye, hasilkan ide konten, dan pantau distribusinya.">
    <a href="{{ route('admin.marketing.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Kampanye Baru
    </a>
</x-admin.page-head>

<div class="row g-4 mb-4">
    @foreach($tiles as $tile)
    <div class="col-6 col-md-4 col-xl">
        <div class="card h-100">
            <div class="card-body">
                <span class="badge bg-label-{{ $tile['tone'] }} rounded p-2 mb-3">
                    <i class="icon-base ti {{ $tile['icon'] }} icon-md"></i>
                </span>
                <h4 class="aldef-stat mb-0">{{ $tile['value'] }}</h4>
                <small class="text-body-secondary">{{ $tile['label'] }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">

    {{-- Campaigns ----------------------------------------------------------}}
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title mb-0">Kampanye</h5></div>

            @if($campaigns->isEmpty())
                <div class="card-body">
                    <x-admin.empty
                        icon="tabler-target"
                        title="Belum ada kampanye"
                        message="Mulai dengan satu kampanye, lalu hasilkan ide konten dari audiens dan pain point yang sudah tersedia.">
                        <a href="{{ route('admin.marketing.create') }}" class="btn btn-primary">
                            <i class="icon-base ti tabler-plus me-2"></i>Kampanye Baru
                        </a>
                    </x-admin.empty>
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kampanye</th>
                            <th class="text-center">Ide</th>
                            <th class="d-none d-md-table-cell">Periode</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $campaign)
                        <tr>
                            <td>
                                <a href="{{ route('admin.marketing.show', $campaign) }}" class="fw-medium text-body d-block">{{ $campaign->name }}</a>
                                @if($campaign->objective)
                                <small class="text-body-secondary d-block text-truncate" style="max-width: 24rem;">{{ $campaign->objective }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-secondary">{{ $campaign->content_ideas_count }}</span>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <small class="text-body-secondary">
                                    {{ $campaign->start_date?->translatedFormat('d M Y') ?? '—' }}
                                    @if($campaign->end_date) – {{ $campaign->end_date->translatedFormat('d M Y') }} @endif
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-{{ $statusTone[$campaign->status] ?? 'secondary' }}">
                                    {{ $statusLabel[$campaign->status] ?? ucfirst((string) $campaign->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Recent content ------------------------------------------------------}}
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title mb-0">Konten Terbaru</h5></div>
            <div class="card-body">
                @if($recentContents->isEmpty())
                    <x-admin.empty icon="tabler-article" title="Belum ada konten dihasilkan" />
                @else
                <ul class="list-unstyled mb-0">
                    @foreach($recentContents as $content)
                    <li class="d-flex align-items-start gap-3 py-3 {{ $loop->last ? '' : 'border-bottom' }}">
                        <span class="badge bg-label-primary rounded p-2 lh-1">
                            <i class="icon-base ti tabler-article icon-sm"></i>
                        </span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-medium text-truncate">{{ $content->title }}</div>
                            <small class="text-body-secondary">
                                {{ $content->campaign->name ?? 'Tanpa kampanye' }}
                                @if($content->generated_at) · {{ $content->generated_at->diffForHumans(short: true) }} @endif
                            </small>
                        </div>
                        <span class="badge bg-label-{{ $content->status === 'published' ? 'success' : ($content->status === 'approved' ? 'info' : 'secondary') }} text-nowrap">
                            {{ ucfirst((string) $content->status) }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
