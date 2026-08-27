@extends('layouts.layoutMaster')

@section('title', 'Log Aktivitas')

@section('content')

<x-admin.page-head
    eyebrow="Sistem"
    title="Log Aktivitas"
    subtitle="{{ $logs->total() }} catatan · perubahan yang dilakukan di konsol admin" />

<div class="card">
    @if($logs->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-history"
                title="Belum ada aktivitas"
                message="Setiap perubahan konten akan tercatat di sini." />
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th class="d-none d-md-table-cell">Oleh</th>
                    <th class="d-none d-lg-table-cell">Alamat IP</th>
                    <th class="text-end">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>
                        <div class="d-flex align-items-start gap-3">
                            <span class="badge bg-label-{{ str_contains((string) $log->action, 'delete') ? 'danger' : (str_contains((string) $log->action, 'create') ? 'success' : 'primary') }} rounded p-2 lh-1">
                                <i class="icon-base ti {{ str_contains((string) $log->action, 'delete') ? 'tabler-trash' : (str_contains((string) $log->action, 'create') ? 'tabler-plus' : 'tabler-pencil') }} icon-sm"></i>
                            </span>
                            <div>
                                <div class="fw-medium">{{ $log->description }}</div>
                                <small class="text-body-secondary font-monospace">{{ $log->action }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <small>{{ $log->user->name ?? 'Sistem' }}</small>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <small class="text-body-secondary font-monospace">{{ $log->ip_address ?: '—' }}</small>
                    </td>
                    <td class="text-end text-nowrap">
                        <small class="text-body-secondary" title="{{ $log->created_at }}">
                            {{ $log->created_at?->diffForHumans() }}
                        </small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="card-footer">{{ $logs->links() }}</div>
    @endif
    @endif
</div>

@endsection
