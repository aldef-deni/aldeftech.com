@extends('layouts.admin')
@php $pageTitle = 'AI Marketing Center'; @endphp

@section('content')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-slate-500">Kelola campaign, ide konten, artikel SEO, dan caption sosial dari AI.</p>
        </div>
        <a href="{{ route('admin.marketing.create') }}" class="btn-primary text-sm py-2 px-4">+ New Campaign</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach([
            'Campaign' => $stats['campaigns'],
            'Active' => $stats['active_campaigns'],
            'Ideas' => $stats['ideas'],
            'Draft' => $stats['drafts'],
            'Published' => $stats['published'],
        ] as $label => $value)
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $label }}</div>
            <div class="mt-2 text-2xl font-display font-bold text-slate-900">{{ $value }}</div>
        </div>
        @endforeach
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200">
            <h2 class="text-base font-display font-bold text-slate-900">Campaigns</h2>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">Platforms</th>
                    <th class="text-center px-5 py-3 text-xs font-medium text-slate-500 uppercase">Ideas</th>
                    <th class="text-center px-5 py-3 text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-slate-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($campaigns as $campaign)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-4">
                        <div class="font-medium text-sm text-slate-900">{{ $campaign->name }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $campaign->objective ?: 'No objective set' }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($campaign->platforms ?? [] as $platform)
                            <span class="text-[0.7rem] px-2 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">{{ ucfirst($platform) }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center text-sm text-slate-600">{{ $campaign->content_ideas_count }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-xs px-2 py-1 rounded-full {{ $campaign->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.marketing.show', $campaign) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Open</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">No AI marketing campaigns yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200">
            <h2 class="text-base font-display font-bold text-slate-900">Recent AI Content</h2>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">Content</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">Campaign</th>
                    <th class="text-center px-5 py-3 text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-slate-500 uppercase">Published</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($recentContents as $content)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-4">
                        <div class="font-medium text-sm text-slate-900">{{ $content->title }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $content->idea?->audience?->name }}</div>
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-600">{{ $content->campaign?->name ?: '-' }}</td>
                    <td class="px-5 py-4 text-center text-sm text-slate-600">{{ ucfirst($content->status) }}</td>
                    <td class="px-5 py-4 text-right text-sm">
                        @if($content->blogPost)
                        <a href="{{ route('blog.show', $content->blogPost->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800">View blog</a>
                        @else
                        <span class="text-slate-400">Not yet</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500">No AI content generated yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
