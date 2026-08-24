@extends('layouts.admin')
@php $pageTitle = 'Users'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $users->count() }} users</p>
    <a href="{{ route('admin.users.create') }}" class="btn-primary text-sm py-2 px-4">+ Add User</a>
</div>
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Name</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Email</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Role</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Joined</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($users as $user)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center text-accent text-sm font-semibold">{{ substr($user->name, 0, 1) }}</div>
                        <span class="text-sm font-medium text-text-primary">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $user->email }}</td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $user->roles->first()->display_name ?? 'No role' }}</td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted text-sm">No users.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
