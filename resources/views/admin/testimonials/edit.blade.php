@extends('layouts.admin')
@php $pageTitle = 'Edit Testimonial — ' . $testimonial->client_name; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
        @csrf @method('PUT')
        @include('admin.testimonials._form')
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Update Testimonial</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
