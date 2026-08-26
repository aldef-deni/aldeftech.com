@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Admin Dashboard')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Analytics</h4>

<div class="row">
    <!-- View sales -->
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card h-100">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title mb-0">Aldef Tech CMS</h5>
                </div>
            </div>
            <div class="card-body">
                <p class="mb-4 text-muted">Migration to Vuexy Template is Active.</p>
                <div class="alert alert-warning mb-0">
                    You have <strong>{{ $stats['new_leads'] }}</strong> new leads waiting to be processed!
                </div>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-primary mt-4">View Leads</a>
            </div>
        </div>
    </div>
    <!-- Statistics -->
    <div class="col-xl-8 mb-4 col-lg-7 col-12">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">System Statistics</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2"><i class="ti ti-briefcase ti-sm"></i></div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $stats['portfolios'] }}</h5>
                                <small>Portfolios</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2"><i class="ti ti-layout-grid ti-sm"></i></div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $stats['services'] }}</h5>
                                <small>Services</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2"><i class="ti ti-users ti-sm"></i></div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $stats['leads'] }}</h5>
                                <small>Total Leads</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-warning me-3 p-2"><i class="ti ti-news ti-sm"></i></div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $stats['blog_posts'] }}</h5>
                                <small>Blog Posts</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Leads -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title m-0 me-2">Recent Leads</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="recentLeadsBtn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentLeadsBtn">
                        <a class="dropdown-item" href="{{ route('admin.leads.index') }}">View All</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLeads as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td>
                                @if($lead->status == 'new')
                                    <span class="badge bg-label-warning">New</span>
                                @elseif($lead->status == 'contacted')
                                    <span class="badge bg-label-info">Contacted</span>
                                @else
                                    <span class="badge bg-label-success">{{ ucfirst($lead->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $lead->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No recent leads found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title m-0 me-2">Recent Activity</h5>
            </div>
            <div class="card-body">
                <ul class="timeline">
                    @forelse($recentActivity->take(5) as $activity)
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">{{ $activity->action }}</h6>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $activity->description }}</p>
                            @if($activity->user)
                                <small class="text-muted">By: {{ $activity->user->name }}</small>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="timeline-item timeline-item-transparent">
                        <div class="timeline-event">
                            <p class="mb-0">No recent activity.</p>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
