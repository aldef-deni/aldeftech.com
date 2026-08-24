@extends('layouts.admin')
@php $pageTitle = 'Create Process Step'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.process-steps.store') }}">
        @csrf
        <x-admin.form.input label="Step Number" name="step_number" type="number" :value="$nextNumber" required />
        <x-admin.form.input label="Title" name="title" required placeholder="e.g. Consultation" />
        <x-admin.form.textarea label="Description" name="description" required :rows="3" />
        <x-admin.form.input label="Icon" name="icon" placeholder="Icon or emoji" />
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" value="1" checked class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
            <label class="text-sm text-text-secondary">Published</label>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Step</button>
            <a href="{{ route('admin.process-steps.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
