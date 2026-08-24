@extends('layouts.admin')
@php $pageTitle = 'Create Portfolio'; @endphp
@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.portfolio.store') }}">
        @csrf
        @include('admin.portfolio._form')
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Portfolio</button>
            <a href="{{ route('admin.portfolio.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
