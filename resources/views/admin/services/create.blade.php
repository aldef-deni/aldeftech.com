@extends('layouts.admin')
@php $pageTitle = 'Create Service'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.services.store') }}">
        @csrf
        @include('admin.services._form')
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Service</button>
            <a href="{{ route('admin.services.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
