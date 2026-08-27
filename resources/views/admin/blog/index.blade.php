@extends('layouts.layoutMaster')

@section('title', 'Artikel')

@section('content')

<x-admin.page-head
    eyebrow="Blog"
    title="Artikel"
    subtitle="{{ $posts->total() ?? $posts->count() }} artikel">
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tulis Artikel
    </a>
</x-admin.page-head>

<div class="card">
    @if($posts->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-news"
                title="Belum ada artikel"
                message="Artikel membantu situs ditemukan lewat pencarian dan membangun kredibilitas.">
                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tulis Artikel
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Artikel</th>
                    <th class="d-none d-lg-table-cell">Kategori</th>
                    <th class="d-none d-md-table-cell">Terbit</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($src = media_url($post->featured_image))
                                <img src="{{ $src }}" alt="" class="aldef-thumb">
                            @else
                                <span class="aldef-thumb d-inline-flex align-items-center justify-content-center text-body-secondary">
                                    <i class="icon-base ti tabler-article"></i>
                                </span>
                            @endif
                            <div class="text-truncate">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="fw-medium text-body d-block text-truncate">{{ $post->title }}</a>
                                <small class="text-body-secondary">{{ $post->author->name ?? 'Tanpa penulis' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-label-secondary">{{ $post->category->name ?? '—' }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <small class="text-body-secondary">{{ $post->published_at?->translatedFormat('d M Y') ?? '—' }}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-label-{{ match($post->status) {
                            'published' => 'success', 'scheduled' => 'info', default => 'secondary',
                        } }}">{{ ['draft' => 'Draf', 'published' => 'Terbit', 'scheduled' => 'Terjadwal'][$post->status] ?? $post->status }}</span>
                    </td>
                    <td class="text-end text-nowrap">
                        @if($post->status === 'published' && $post->slug)
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener"
                           class="btn btn-sm btn-icon btn-text-secondary" title="Lihat di situs">
                            <i class="icon-base ti tabler-external-link"></i>
                        </a>
                        @endif
                        <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                            <i class="icon-base ti tabler-pencil"></i>
                        </a>
                        <x-admin.delete
                            :action="route('admin.blog.destroy', $post)"
                            :confirm="'Hapus artikel \'' . $post->title . '\'?'" />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(method_exists($posts, 'hasPages') && $posts->hasPages())
    <div class="card-footer">{{ $posts->links() }}</div>
    @endif
    @endif
</div>

@endsection
