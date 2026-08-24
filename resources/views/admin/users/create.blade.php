@extends('layouts.admin')
@php $pageTitle = 'Create User'; @endphp
@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <x-admin.form.input label="Name" name="name" required />
        <x-admin.form.input label="Email" name="email" type="email" required />
        <x-admin.form.input label="Password" name="password" type="password" required />
        <x-admin.form.input label="Confirm Password" name="password_confirmation" type="password" required />
        <div class="mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-1.5">Role <span class="text-danger">*</span></label>
            <select name="role" class="w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent" required>
                @foreach($roles as $role)
                <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
