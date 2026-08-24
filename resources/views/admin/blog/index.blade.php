@extends('layouts.admin')
@php $pageTitle = 'Blog Posts'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-text-muted text-sm">{{ $posts->count() }} posts</p>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}" class="btn-secondary text-sm py-2 px-4">Categories</a>
        <a href="{{ route('admin.tags.index') }}" class="btn-secondary text-sm py-2 px-4">Tags</a>
        <a href="{{ route('admin.blog.create') }}" class="btn-primary text-sm py-2 px-4">+ New Post</a>
    </div>
</div>
<div class="bg-brand-surface border border-brand-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-brand-border">
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Title</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Category</th>
                <th class="text-left px-5 py-3 text-xs font-medium text-text-muted uppercase">Author</th>
                <th class="text-center px-5 py-3 text-xs font-medium text-text-muted uppercase">Status</th>
                <th class="text-right px-5 py-3 text-xs font-medium text-text-muted uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-brand-border">
            @forelse($posts as $post)
            <tr class="hover:bg-brand-surface-2/50">
                <td class="px-5 py-4">
                    <div class="font-medium text-sm text-text-primary">{{ $post->title }}</div>
                    <div class="text-xs text-text-muted mt-0.5">{{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}</div>
                </td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $post->category->name ?? '—' }}</td>
                <td class="px-5 py-4 text-sm text-text-muted">{{ $post->author->name ?? '—' }}</td>
                <td class="px-5 py-4 text-center">
                    <form method="POST" action="{{ route('admin.blog.toggle-publish', $post) }}">
                        @csrf
                        <button type="submit" class="text-xs px-2 py-1 rounded-full {{ $post->status === 'published' ? 'bg-green-500/10 text-green-400' : 'bg-brand-surface-2 text-text-muted' }}">
                            {{ ucfirst($post->status) }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($post->isPublished())
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-xs text-text-muted hover:text-text-primary">View</a>
                        @endif
                        <a href="{{ route('admin.blog.edit', $post) }}" class="text-xs text-accent hover:text-accent-light">Edit</a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-danger hover:text-danger-dark">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-8 text-center text-text-muted text-sm">No blog posts yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
