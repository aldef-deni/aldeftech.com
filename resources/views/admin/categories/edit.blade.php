@extends('layouts.admin')
@php $pageTitle = 'Edit Category — ' . $category->name; @endphp
@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf @method('PUT')
        <x-admin.form.input label="Name" name="name" :value="$category->name" required />
        <x-admin.form.textarea label="Description" name="description" :value="$category->description ?? ''" :rows="2" />
        <x-admin.form.input label="Sort Order" name="sort_order" type="number" :value="$category->sort_order" />
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
