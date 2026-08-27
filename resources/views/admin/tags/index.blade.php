@extends('layouts.layoutMaster')

@section('title', 'Tag')

@section('content')

<x-admin.page-head
    eyebrow="Blog"
    title="Tag"
    subtitle="{{ $tags->count() }} tag">
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Tag
    </a>
</x-admin.page-head>

<div class="card">
    <div class="card-body">
        @if($tags->isEmpty())
            <x-admin.empty
                icon="tabler-tag"
                title="Belum ada tag"
                message="Tag memudahkan pengelompokan topik lintas kategori.">
                <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Tag
                </a>
            </x-admin.empty>
        @else
        <div class="d-flex flex-wrap gap-2">
            @foreach($tags as $tag)
            <div class="d-inline-flex align-items-center gap-1 border rounded-pill ps-3 pe-1 py-1">
                <a href="{{ route('admin.tags.edit', $tag) }}" class="text-body text-decoration-none">{{ $tag->name }}</a>
                <span class="badge bg-label-secondary rounded-pill">{{ $tag->posts_count ?? 0 }}</span>
                <x-admin.delete
                    :action="route('admin.tags.destroy', $tag)"
                    :confirm="'Hapus tag \'' . $tag->name . '\'?'"
                    class="btn btn-sm btn-icon btn-text-danger rounded-circle" />
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection
