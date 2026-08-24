@extends('layouts.admin')
@php $pageTitle = 'Edit User — ' . $user->name; @endphp
@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <x-admin.form.input label="Name" name="name" :value="$user->name" required />
        <x-admin.form.input label="Email" name="email" type="email" :value="$user->email" required />
        <x-admin.form.input label="New Password" name="password" type="password" placeholder="Leave blank to keep current" />
        <x-admin.form.input label="Confirm Password" name="password_confirmation" type="password" />
        <div class="mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-1.5">Role <span class="text-danger">*</span></label>
            <select name="role" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent" required>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Update User</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
