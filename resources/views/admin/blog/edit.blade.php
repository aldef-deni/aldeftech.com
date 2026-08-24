@extends('layouts.admin')
@php $pageTitle = 'Edit — ' . $post->title; @endphp
@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.blog.update', $post) }}">
        @csrf @method('PUT')
        @include('admin.blog._form')
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Update Post</button>
            <a href="{{ route('admin.blog.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
