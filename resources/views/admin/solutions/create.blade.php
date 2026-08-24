@extends('layouts.admin')
@php $pageTitle = 'Create Solution'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.solutions.store') }}">
        @csrf
        @include('admin.solutions._form')
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save Solution</button>
            <a href="{{ route('admin.solutions.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
