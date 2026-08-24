@extends('layouts.admin')
@php $pageTitle = 'Create Testimonial'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.testimonials.store') }}">
        @csrf
        @include('admin.testimonials._form')
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Testimonial</button>
            <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
