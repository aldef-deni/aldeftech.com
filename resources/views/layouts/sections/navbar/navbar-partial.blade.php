@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $userRole = $user?->roles?->first()?->display_name
        ?? ucwords(str_replace('-', ' ', $user?->roles?->first()?->name ?? 'Pengguna'));

    // Cheap enough for a navbar badge; the table is small and indexed on status.
    try {
        $newLeads = \App\Models\Lead::where('status', 'new')->count();
    } catch (\Throwable $e) {
        $newLeads = 0;
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

        {{-- New leads --}}
        <li class="nav-item">
            <a class="nav-link position-relative" href="{{ route('admin.leads.index') }}" title="Leads baru">
                <i class="icon-base ti tabler-bell icon-md"></i>
                @if ($newLeads > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $newLeads > 99 ? '99+' : $newLeads }}
                    <span class="visually-hidden">leads baru</span>
                </span>
                @endif
            </a>
        </li>

        {{-- User --}}
        <li class="nav-item navbar-dropdown dropdown-user dropdown ms-2">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown" aria-label="Menu pengguna">
                <div class="avatar avatar-online">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        {{ initials_of($user?->name) }}
                    </span>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="dropdown-item mt-0 pe-none">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar avatar-online">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ initials_of($user?->name) }}
                                    </span>
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
