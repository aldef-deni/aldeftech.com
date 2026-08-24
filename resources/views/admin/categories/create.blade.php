@extends('layouts.admin')
@php $pageTitle = 'Create Category'; @endphp
@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <x-admin.form.input label="Name" name="name" required />
        <x-admin.form.textarea label="Description" name="description" :rows="2" />
        <x-admin.form.input label="Sort Order" name="sort_order" type="number" value="0" />
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
