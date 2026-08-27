@extends('layouts.layoutMaster')

@section('title', 'Dashboard')

@php
    $statusLabels = config('aldeftech.lead.statuses', []);
    $trendMax = max(1, collect($leadTrend)->max('value'));
    $trendTotal = collect($leadTrend)->sum('value');

    $tiles = [
        ['label' => 'Total Leads',  'value' => $stats['leads'],       'icon' => 'tabler-users',      'tone' => 'primary', 'url' => route('admin.leads.index')],
        ['label' => 'Leads Baru',   'value' => $stats['new_leads'],   'icon' => 'tabler-mail-opened','tone' => 'warning', 'url' => route('admin.leads.index')],
        ['label' => 'Portofolio',   'value' => $stats['portfolios'],  'icon' => 'tabler-briefcase',  'tone' => 'info',    'url' => route('admin.portfolio.index')],
        ['label' => 'Artikel',      'value' => $stats['blog_posts'],  'icon' => 'tabler-news',       'tone' => 'success', 'url' => route('admin.blog.index')],
    ];

    $contentTiles = [
        ['label' => 'Layanan',    'value' => $stats['services'],     'icon' => 'tabler-layout-grid-add', 'url' => route('admin.services.index')],
        ['label' => 'Solusi',     'value' => $stats['solutions'],    'icon' => 'tabler-bulb',            'url' => route('admin.solutions.index')],
        ['label' => 'Testimoni',  'value' => $stats['testimonials'], 'icon' => 'tabler-star',            'url' => route('admin.testimonials.index')],
        ['label' => 'FAQ',        'value' => $stats['faqs'],         'icon' => 'tabler-help-circle',     'url' => route('admin.faq.index')],
        ['label' => 'Media',      'value' => $stats['media'],        'icon' => 'tabler-photo',           'url' => route('admin.media.index')],
    ];
@endphp

@section('content')

<x-admin.page-head
    eyebrow="Ringkasan"
    title="Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}"
    subtitle="Kondisi konten dan permintaan masuk aldeftech.com hari ini.">
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
        <i class="icon-base ti tabler-external-link me-2"></i>Lihat Situs
    </a>
    <a href="{{ route('admin.leads.index') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-users me-2"></i>Kelola Leads
    </a>
</x-admin.page-head>

{{-- Needs attention ---------------------------------------------------------}}
@if($unpublishedTotal > 0)
<div class="alert alert-warning d-flex align-items-start gap-3 mb-4" role="alert">
    <i class="icon-base ti tabler-eye-off mt-1"></i>
    <div class="flex-grow-1">
        <h6 class="alert-heading mb-1">{{ $unpublishedTotal }} konten belum tampil di situs</h6>
        <p class="mb-0 small">
            @foreach(array_filter($unpublished) as $key => $count)
                <span class="me-3">{{ ucfirst(str_replace('_', ' ', $key)) }}: <strong>{{ $count }}</strong></span>
            @endforeach
        </p>
    </div>
</div>
@endif

{{-- Primary tiles -----------------------------------------------------------}}
<div class="row g-4 mb-4">
    @foreach($tiles as $tile)
    <div class="col-6 col-xl-3">
        <a href="{{ $tile['url'] }}" class="card h-100 text-body text-decoration-none card-hover">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-label-{{ $tile['tone'] }} rounded p-2">
                        <i class="icon-base ti {{ $tile['icon'] }} icon-md"></i>
                    </span>
                    <i class="icon-base ti tabler-arrow-up-right text-body-secondary"></i>
                </div>
                <h3 class="aldef-stat mb-0">{{ number_format($tile['value'], 0, ',', '.') }}</h3>
                <small class="text-body-secondary">{{ $tile['label'] }}</small>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="row g-4 mb-4">

    {{-- Lead trend ---------------------------------------------------------}}
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-start justify-content-between">
                <div>
                    <h5 class="card-title mb-1">Leads Masuk</h5>
                    <small class="text-body-secondary">14 hari terakhir · total {{ $trendTotal }}</small>
                </div>
                <span class="badge bg-label-primary">{{ $stats['new_leads'] }} belum diproses</span>
            </div>

            <div class="card-body">
                @if($trendTotal === 0)
                    <x-admin.empty
                        icon="tabler-chart-bar"
                        title="Belum ada lead dalam 14 hari terakhir"
                        message="Formulir kontak dan tombol WhatsApp di situs akan mengisi bagian ini otomatis." />
                @else
                    <div class="d-flex align-items-end gap-2" style="height: 11rem;" role="img"
                         aria-label="Grafik batang leads 14 hari terakhir">
                        @foreach($leadTrend as $point)
                        <div class="flex-fill d-flex flex-column align-items-center justify-content-end h-100"
                             title="{{ $point['label'] }}: {{ $point['value'] }} lead">
                            <span class="small text-body-secondary mb-1">{{ $point['value'] ?: '' }}</span>
                            <div class="w-100 rounded-top {{ $point['value'] ? 'bg-primary' : 'bg-label-secondary' }}"
                                 style="height: {{ max(3, round(($point['value'] / $trendMax) * 100)) }}%; min-height: 4px;"></div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                        <small class="text-body-secondary">{{ $leadTrend[0]['label'] }}</small>
                        <small class="text-body-secondary">{{ $leadTrend[count($leadTrend) - 1]['label'] }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pipeline ------------------------------------------------------------}}
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Pipeline</h5>
            </div>
            <div class="card-body">
                @if(empty($leadsByStatus))
                    <x-admin.empty icon="tabler-filter" title="Pipeline masih kosong" />
                @else
                    @php $pipelineTotal = max(1, array_sum($leadsByStatus)); @endphp
                    <ul class="list-unstyled mb-0">
                        @foreach($statusLabels as $key => $label)
                            @php $count = $leadsByStatus[$key] ?? 0; @endphp
                            <li class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small">{{ $label }}</span>
                                    <span class="small fw-medium">{{ $count }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $key === 'won' ? 'bg-success' : ($key === 'lost' ? 'bg-secondary' : '') }}"
                                         style="width: {{ round(($count / $pipelineTotal) * 100) }}%"
                                         role="progressbar"
                                         aria-valuenow="{{ $count }}" aria-valuemin="0" aria-valuemax="{{ $pipelineTotal }}"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">

    {{-- Recent leads --------------------------------------------------------}}
    <div class="col-12 col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Leads Terbaru</h5>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-text-primary">Semua</a>
            </div>

            @if($recentLeads->isEmpty())
                <div class="card-body">
                    <x-admin.empty icon="tabler-inbox" title="Belum ada lead masuk" />
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th class="d-none d-md-table-cell">Kebutuhan</th>
                            <th>Status</th>
                            <th class="text-end">Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLeads as $lead)
                        <tr>
                            <td>
                                <a href="{{ route('admin.leads.show', $lead) }}" class="fw-medium text-body">{{ $lead->name }}</a>
                                @if($lead->company)<div class="small text-body-secondary">{{ $lead->company }}</div>@endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="small text-body-secondary">{{ $lead->project_type ?: '—' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-{{ match($lead->status) {
                                    'won' => 'success', 'lost' => 'secondary', 'new' => 'warning', default => 'info',
                                } }}">{{ $statusLabels[$lead->status] ?? ucfirst((string) $lead->status) }}</span>
                            </td>
                            <td class="text-end">
                                <span class="small text-body-secondary">{{ $lead->created_at?->diffForHumans(short: true) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Activity ------------------------------------------------------------}}
    <div class="col-12 col-xl-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Aktivitas Terakhir</h5>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-text-primary">Semua</a>
            </div>

            <div class="card-body">
                @if($recentActivity->isEmpty())
                    <x-admin.empty icon="tabler-history" title="Belum ada aktivitas tercatat" />
                @else
                <ul class="timeline timeline-dashed mb-0">
                    @foreach($recentActivity as $log)
                    <li class="timeline-item timeline-item-transparent ps-4 pb-3">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event p-0">
                            <div class="timeline-header">
                                <h6 class="mb-0 small fw-medium">{{ $log->description }}</h6>
                            </div>
                            <small class="text-body-secondary">
                                {{ $log->user->name ?? 'Sistem' }} · {{ $log->created_at?->diffForHumans(short: true) }}
                            </small>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Content inventory -------------------------------------------------------}}
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Isi Situs</h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            @foreach($contentTiles as $tile)
            <div class="col-6 col-md-4 col-xl">
                <a href="{{ $tile['url'] }}" class="d-flex align-items-center gap-3 text-body text-decoration-none">
                    <span class="badge bg-label-primary rounded p-2">
                        <i class="icon-base ti {{ $tile['icon'] }} icon-md"></i>
                    </span>
                    <span>
                        <span class="d-block aldef-stat fs-5">{{ $tile['value'] }}</span>
                        <small class="text-body-secondary">{{ $tile['label'] }}</small>
                    </span>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
