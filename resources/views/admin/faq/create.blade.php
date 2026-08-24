@extends('layouts.admin')
@php $pageTitle = 'Create FAQ'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.faq.store') }}">
        @csrf
        @include('admin.faq._form')
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save FAQ</button>
            <a href="{{ route('admin.faq.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
