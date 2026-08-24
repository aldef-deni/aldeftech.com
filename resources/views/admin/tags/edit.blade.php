@extends('layouts.admin')
@php $pageTitle = 'Edit Tag — ' . $tag->name; @endphp
@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
        @csrf @method('PUT')
        <x-admin.form.input label="Name" name="name" :value="$tag->name" required />
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Update Tag</button>
            <a href="{{ route('admin.tags.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
