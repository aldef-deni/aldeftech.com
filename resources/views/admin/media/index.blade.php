@extends('layouts.admin')
@php $pageTitle = 'Media Library'; @endphp
@section('content')
<div class="mb-6">
    <form method="POST" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" id="media-upload-form">
        @csrf
        <div class="bg-brand-surface border-2 border-dashed border-brand-border rounded-xl p-8 text-center"
             x-data="{ dragging: false }"
             @dragover.prevent="dragging = true"
             @dragleave="dragging = false"
             @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.form.submit();"
             :class="dragging ? 'border-accent bg-accent/5' : ''">
            <svg class="w-10 h-10 mx-auto text-text-muted mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-text-muted text-sm mb-2">Drop files here or click to upload</p>
            <input type="file" name="file" x-ref="fileInput" accept="image/*" class="hidden" onchange="this.form.submit()">
            <button type="button" onclick="this.closest('form').querySelector('input[type=file]').click()" class="btn-primary text-sm py-2 px-4">Select File</button>
        </div>
    </form>
</div>

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files..."
           class="bg-brand-surface border border-brand-border rounded-lg px-3 py-2 text-sm text-text-primary focus:outline-none focus:border-accent w-64">
    <button type="submit" class="btn-secondary text-sm py-2 px-4">Search</button>
</form>

{{-- Media Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @forelse($media as $item)
    <div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden group">
        <div class="aspect-square bg-brand-surface-2 flex items-center justify-center p-2">
            @if(str_starts_with($item->mime_type, 'image/'))
            <img src="{{ $item->url }}" alt="{{ $item->original_name }}" class="w-full h-full object-cover rounded">
            @else
            <span class="text-text-muted text-xs">{{ $item->mime_type }}</span>
            @endif
        </div>
        <div class="p-2">
            <div class="text-xs text-text-primary truncate">{{ $item->original_name }}</div>
            <div class="text-xs text-text-dark mt-0.5">{{ number_format($item->size / 1024, 1) }} KB</div>
            <div class="flex items-center justify-between mt-2">
                <button onclick="navigator.clipboard.writeText('{{ $item->path }}'); showToast('Path copied!')" class="text-xs text-accent hover:text-accent-light">Copy Path</button>
                <form method="POST" action="{{ route('admin.media.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-text-muted text-sm">No media files yet.</div>
    @endforelse
</div>

<div class="mt-4">{{ $media->links() }}</div>
@endsection
