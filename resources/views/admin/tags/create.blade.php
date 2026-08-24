@extends('layouts.admin')
@php $pageTitle = 'Create Tag'; @endphp
@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.tags.store') }}">
        @csrf
        <x-admin.form.input label="Name" name="name" required />
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Tag</button>
            <a href="{{ route('admin.tags.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
