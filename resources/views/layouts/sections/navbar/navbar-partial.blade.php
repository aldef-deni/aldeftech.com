@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $userRole = $user?->roles?->first()?->display_name
        ?? ucwords(str_replace('-', ' ', $user?->roles?->first()?->name ?? 'Pengguna'));

    // The bell is only meaningful to someone who can open a lead.
    $canSeeLeads = (bool) $user?->hasPermission('manage-leads');

    // Cheap enough for a navbar badge: read_at is indexed and the list is capped.
    try {
        $unreadLeads = $canSeeLeads ? \App\Models\Lead::unread()->count() : 0;
        $recentLeads = $canSeeLeads
            ? \App\Models\Lead::unread()->orderByDesc('created_at')->orderByDesc('id')->take(5)->get()
            : collect();
    } catch (\Throwable $e) {
        $unreadLeads = 0;
        $recentLeads = collect();
    }
@endphp

{{-- Brand (navbar-full layouts only) --}}
@if (isset($navbarFull))
<div class="navbar-brand app-brand d-none d-xl-flex py-0 me-4 ms-0">
    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
        <span class="app-brand-logo">@include('_partials.macros')</span>
        <span class="app-brand-text menu-text fw-bold ms-2">{{ config('variables.templateName') }}</span>
    </a>

    @if (isset($menuHorizontal))
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
        <i class="icon-base ti tabler-x icon-sm d-flex align-items-center justify-content-center"></i>
    </a>
    @endif
</div>
@endif

{{-- Sidebar toggle --}}
@if (!isset($navbarHideToggle))
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }}{{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)" aria-label="Buka menu">
        <i class="icon-base ti tabler-menu-2 icon-md"></i>
    </a>
</div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">

        {{-- View public site --}}
        <li class="nav-item d-none d-sm-block">
            <a class="nav-link" href="{{ route('home') }}" target="_blank" rel="noopener" title="Lihat situs">
                <i class="icon-base ti tabler-external-link icon-md"></i>
            </a>
        </li>

        {{-- Leads baru --}}
        @if ($canSeeLeads)
        <li class="nav-item navbar-dropdown dropdown">
            <a class="nav-link position-relative" href="javascript:void(0);"
               data-bs-toggle="dropdown" data-bs-auto-close="outside"
               aria-expanded="false" aria-label="Notifikasi lead baru">
                <i class="icon-base ti tabler-bell icon-md"></i>
                @if ($unreadLeads > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $unreadLeads > 99 ? '99+' : $unreadLeads }}
                    <span class="visually-hidden">lead belum dibaca</span>
                </span>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end aldef-notif">

                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center justify-content-between py-3">
                        <h6 class="mb-0 me-auto">Lead Baru</h6>
                        @if ($unreadLeads > 0)
                        <span class="badge bg-label-primary me-2">{{ $unreadLeads }} baru</span>
                        <form method="POST" action="{{ route('admin.leads.read-all') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-text-secondary btn-icon btn-sm rounded-pill"
                                    title="Tandai semua sudah dibaca">
                                <i class="icon-base ti tabler-mail-opened icon-sm"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </li>

                <li class="dropdown-notifications-list">
                    <ul class="list-group list-group-flush">
                        @forelse ($recentLeads as $lead)
                        <li class="list-group-item list-group-item-action aldef-notif-item">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar avatar-sm flex-shrink-0">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ initials_of($lead->name) }}
                                    </span>
                                </div>

                                <a href="{{ route('admin.leads.show', $lead) }}" class="flex-grow-1 min-w-0 text-body">
                                    <span class="d-block fw-semibold text-truncate">{{ $lead->name }}</span>
                                    <small class="d-block text-body-secondary text-truncate">
                                        {{ $lead->company ?: $lead->email }}
                                    </small>
                                    <small class="d-block text-body-secondary mt-1">
                                        {{ $lead->created_at?->diffForHumans() }}
                                    </small>
                                </a>

                                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}"
                                      class="flex-shrink-0"
                                      onsubmit="return confirm('Hapus lead dari {{ addslashes($lead->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-text-danger btn-icon btn-sm rounded-pill"
                                            title="Hapus lead">
                                        <i class="icon-base ti tabler-trash icon-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-body-secondary py-5">
                            <i class="icon-base ti tabler-inbox icon-lg d-block mb-2 opacity-50"></i>
                            <small>Tidak ada lead baru.</small>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <li class="dropdown-menu-footer border-top">
                    <a href="{{ route('admin.leads.index') }}"
                       class="dropdown-item d-flex justify-content-center py-3 text-primary">
                        Lihat semua lead
                    </a>
                </li>
            </ul>
        </li>
        @endif

        {{-- User --}}
        <li class="nav-item navbar-dropdown dropdown-user dropdown ms-2">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown" aria-label="Menu pengguna">
                <div class="avatar avatar-online">
                    @if($user?->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle">
                    @else
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        {{ initials_of($user?->name) }}
                    </span>
                    @endif
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="dropdown-item mt-0 pe-none">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar avatar-online">
                                    @if($user?->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle">
                                    @else
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ initials_of($user?->name) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $user?->name ?? 'Pengguna' }}</h6>
                                <small class="text-body-secondary">{{ $userRole }}</small>
                            </div>
                        </div>
                    </div>
                </li>

                <li><div class="dropdown-divider my-1 mx-n2"></div></li>

                @if ($user?->hasRole('super-admin'))
                <li>
                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                        <i class="icon-base ti tabler-users me-3 icon-md"></i><span class="align-middle">Kelola Pengguna</span>
                    </a>
                </li>
                @endif

                <li>
                    <a class="dropdown-item" href="{{ route('admin.settings.site') }}">
                        <i class="icon-base ti tabler-settings me-3 icon-md"></i><span class="align-middle">Pengaturan Situs</span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item" href="{{ route('admin.activity-logs.index') }}">
                        <i class="icon-base ti tabler-history me-3 icon-md"></i><span class="align-middle">Log Aktivitas</span>
                    </a>
                </li>

                <li><div class="dropdown-divider my-1 mx-n2"></div></li>

                <li>
                    <div class="d-grid px-2 pt-1 pb-1">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger d-flex w-100 justify-content-center align-items-center">
                                <small class="align-middle">Keluar</small>
                                <i class="icon-base ti tabler-logout ms-2 icon-14px"></i>
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
</div>
