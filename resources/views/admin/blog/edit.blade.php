@extends('layouts.layoutMaster')

@section('title', 'Ubah Artikel')

@section('content')

<form method="POST" action="{{ route('admin.blog.update', $post) }}">
    @csrf
    @method('PUT')

    <x-admin.page-head
        eyebrow="Blog"
        :title="$post->title"
        subtitle="Terakhir diubah {{ $post->updated_at?->diffForHumans() }}"
        :back="route('admin.blog.index')">
        @if($post->status === 'published' && $post->slug)
        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
            <i class="icon-base ti tabler-external-link me-2"></i>Lihat
        </a>
        @endif
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-device-floppy me-2"></i>Simpan Perubahan
        </button>
    </x-admin.page-head>

    @include('admin.blog._form')
</form>

@endsection
