@extends('layouts.layoutMaster')

@section('title', 'Pengguna')

@section('content')

<x-admin.page-head
    eyebrow="Sistem"
    title="Pengguna"
    subtitle="{{ $users->count() }} akun dengan akses ke konsol admin">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Pengguna
    </a>
</x-admin.page-head>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th class="d-none d-md-table-cell">Peran</th>
                    <th class="d-none d-lg-table-cell">Bergabung</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-sm">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary">{{ initials_of($user->name) }}</span>
                                @endif
                            </span>
                            <div class="text-truncate">
                                <a href="{{ route('admin.users.edit', $user) }}" class="fw-medium text-body d-block text-truncate">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-label-primary ms-1">Anda</span>
                                    @endif
                                </a>
                                <small class="text-body-secondary d-block text-truncate">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        @forelse($user->roles as $role)
                            <span class="badge bg-label-{{ $role->name === 'super-admin' ? 'danger' : 'secondary' }}">
                                {{ $role->display_name ?? $role->name }}
                            </span>
                        @empty
                            <span class="text-body-secondary">—</span>
                        @endforelse
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <small class="text-body-secondary">{{ $user->created_at?->translatedFormat('d M Y') }}</small>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <x-admin.delete
                            :action="route('admin.users.destroy', $user)"
                            :confirm="'Hapus akun ' . $user->name . '? Akses mereka ke konsol admin akan dicabut.'" />
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
