@extends('layouts.admin')
@php $pageTitle = 'Edit Portfolio — ' . $portfolio->title; @endphp
@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.portfolio.update', $portfolio) }}">
        @csrf @method('PUT')
        @include('admin.portfolio._form')

        {{-- Existing Gallery Images --}}
        @if($portfolio->images->count())
        <div class="mt-6 mb-4">
            <label class="block text-sm font-medium text-text-secondary mb-2">Gallery Images</label>
            <div class="grid grid-cols-3 gap-3">
                @foreach($portfolio->images as $image)
                <div class="bg-brand-surface-2 border border-brand-border rounded-lg p-2 relative">
                    <div class="aspect-video bg-brand-surface-3 rounded flex items-center justify-center text-xs text-text-muted">
                        {{ $image->image }}
                    </div>
                    <div class="mt-1 text-xs text-text-muted truncate">{{ $image->caption }}</div>
                    <form method="POST" action="{{ route('admin.portfolio.delete-image', [$portfolio, $image]) }}" class="absolute top-1 right-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-5 h-5 bg-danger/80 rounded-full flex items-center justify-center text-white text-xs" onclick="return confirm('Delete image?')">×</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Update Portfolio</button>
            <a href="{{ route('admin.portfolio.index') }}" class="btn-secondary text-sm py-2.5 px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection
