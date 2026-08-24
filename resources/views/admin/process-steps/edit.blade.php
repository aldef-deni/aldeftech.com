@extends('layouts.admin')
@php $pageTitle = 'Edit Process Step'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.process-steps.update', $step) }}">
        @csrf @method('PUT')
        <x-admin.form.input label="Step Number" name="step_number" type="number" :value="$step->step_number" required />
        <x-admin.form.input label="Title" name="title" :value="$step->title" required />
        <x-admin.form.textarea label="Description" name="description" :value="$step->description" required :rows="3" />
        <x-admin.form.input label="Icon" name="icon" :value="$step->icon ?? ''" />
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_published" value="1" {{ $step->is_published ? 'checked' : '' }} class="w-4 h-4 rounded border-brand-border bg-brand-surface-2 text-accent">
            <label class="text-sm text-text-secondary">Published</label>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Update Step</button>
            <a href="{{ route('admin.process-steps.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
