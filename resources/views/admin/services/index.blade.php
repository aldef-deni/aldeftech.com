@extends('layouts.admin')
@php $pageTitle = 'Services'; @endphp
@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-slate-500 text-sm font-medium">{{ $services->count() }} services configured</p>
    <a href="{{ route('admin.services.create') }}" class="btn-primary text-sm py-2 px-4 shadow-sm">+ Add Service</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Title</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Icon</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Sort</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($services as $service)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-bold text-sm text-slate-900">{{ $service->title }}</div>
                        <div class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $service->short_description }}</div>
                    </td>
                    <td class="px-5 py-4 text-sm text-slate-600">{{ $service->icon ?? '—' }}</td>
                    <td class="px-5 py-4 text-center text-sm font-semibold text-slate-700">{{ $service->sort_order }}</td>
                    <td class="px-5 py-4 text-center">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $service->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                            {{ $service->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.services.edit', $service) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Edit</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-sm">No services configured yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
