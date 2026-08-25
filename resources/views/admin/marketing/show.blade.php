@extends('layouts.admin')
@php $pageTitle = 'AI Marketing: ' . $campaign->name; @endphp

@section('content')
<div class="space-y-6">
    <div class="bg-white border border-slate-200 rounded-xl p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs px-2 py-1 rounded-full {{ $campaign->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($campaign->status) }}</span>
                    <span class="text-xs text-slate-500">Priority {{ $campaign->priority }}</span>
                </div>
                <p class="text-sm text-slate-600 max-w-3xl">{{ $campaign->objective ?: $campaign->description }}</p>
                <div class="flex flex-wrap gap-1.5 mt-3">
                    @foreach($campaign->platforms ?? [] as $platform)
                    <span class="text-[0.7rem] px-2 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">{{ ucfirst($platform) }}</span>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('admin.marketing.generate-ideas', $campaign) }}" class="flex items-center gap-2">
                @csrf
                <input type="number" name="limit" value="12" min="1" max="50" class="w-20 border border-slate-300 rounded-xl px-3 py-2 text-sm">
                <button type="submit" class="btn-primary text-sm py-2 px-4">Generate Ideas</button>
            </form>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-base font-display font-bold text-slate-900">Content Ideas</h2>
            <span class="text-sm text-slate-500">{{ $campaign->contentIdeas->count() }} ideas</span>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">Idea</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">Audience</th>
                    <th class="text-center px-5 py-3 text-xs font-medium text-slate-500 uppercase">Stage</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-slate-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($campaign->contentIdeas as $idea)
                <tr class="hover:bg-slate-50 align-top">
                    <td class="px-5 py-4">
                        <div class="font-medium text-sm text-slate-900">{{ $idea->title }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $idea->hook }}</div>
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-600">{{ $idea->audience?->name ?: '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600">{{ ucfirst($idea->funnel_stage ?? 'n/a') }}</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        @if($idea->contents->isEmpty())
                        <form method="POST" action="{{ route('admin.marketing.generate-content', $idea) }}">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Generate Content</button>
                        </form>
                        @else
                        <span class="text-sm text-emerald-700">Generated</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">No ideas yet. Generate ideas to start the campaign.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-display font-bold text-slate-900">Generated Content</h2>
            <span class="text-sm text-slate-500">{{ $contents->count() }} drafts</span>
        </div>

        @forelse($contents as $content)
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">{{ ucfirst($content->status) }}</span>
                        <span class="text-xs text-slate-500">{{ $content->generated_at?->format('d M Y H:i') }}</span>
                    </div>
                    <h3 class="text-lg font-display font-bold text-slate-900">{{ $content->title }}</h3>
                    <p class="text-sm text-slate-600 mt-1">{{ $content->excerpt }}</p>
                    <div class="text-xs text-slate-500 mt-2">SEO: {{ $content->seo_keywords }}</div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($content->status === 'draft')
                    <form method="POST" action="{{ route('admin.marketing.approve-content', $content) }}">
                        @csrf
                        <button type="submit" class="btn-secondary text-xs py-2 px-3">Approve</button>
                    </form>
                    @endif
                    @if(in_array($content->status, ['draft', 'approved']))
                    <form method="POST" action="{{ route('admin.marketing.publish-content', $content) }}">
                        @csrf
                        <button type="submit" class="btn-primary text-xs py-2 px-3">Publish Blog</button>
                    </form>
                    @endif
                    @if($content->blogPost)
                    <a href="{{ route('blog.show', $content->blogPost->slug) }}" target="_blank" class="btn-secondary text-xs py-2 px-3">View Blog</a>
                    @endif
                </div>
            </div>

            @if($content->distribution_checklist)
            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($content->distribution_checklist as $item)
                <div class="flex items-start gap-2 text-sm text-slate-600">
                    <span class="mt-1 h-2 w-2 rounded-full bg-blue-500 shrink-0"></span>
                    <span>{{ $item }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if($content->platform_posts)
            <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4">
                @foreach($content->platform_posts as $platform => $post)
                <div class="border border-slate-200 rounded-xl p-4" x-data="{ copied: false }">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-display font-bold text-slate-900">{{ ucfirst($platform) }}</h4>
                        <button
                            type="button"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-800"
                            @click="navigator.clipboard.writeText($refs.copy.value); copied = true; setTimeout(() => copied = false, 1500)"
                            x-text="copied ? 'Copied' : 'Copy'"
                        ></button>
                    </div>
                    <textarea x-ref="copy" rows="8" readonly class="w-full border border-slate-200 rounded-xl p-3 text-xs text-slate-700 bg-slate-50 resize-y">{{ trim(($post['hook'] ?? '') . "\n\n" . ($post['caption'] ?? '') . "\n\n" . ($post['hashtags'] ?? '') . "\n\n" . ($post['cta'] ?? '')) }}</textarea>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white border border-slate-200 rounded-xl px-5 py-10 text-center text-sm text-slate-500">
            No generated content yet.
        </div>
        @endforelse
    </div>
</div>
@endsection
