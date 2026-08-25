@extends('layouts.admin')
@php $pageTitle = 'Create AI Campaign'; @endphp

@section('content')
<form method="POST" action="{{ route('admin.marketing.store') }}" class="max-w-4xl">
    @csrf

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <x-admin.form.input label="Campaign Name" name="name" required placeholder="Contoh: Digitalisasi Bisnis Q3" />

        <x-admin.form.textarea
            label="Objective"
            name="objective"
            :rows="3"
            placeholder="Contoh: Mendatangkan leads untuk jasa custom software, AI automation, dan system integration."
        />

        <x-admin.form.textarea
            label="Description"
            name="description"
            :rows="4"
            placeholder="Konteks campaign, penawaran utama, atau segmentasi market."
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-admin.form.input label="Start Date" name="start_date" type="date" />
            <x-admin.form.input label="End Date" name="end_date" type="date" />
            <x-admin.form.input label="Priority" name="priority" type="number" value="80" />
        </div>

        <div class="mb-5">
            <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
            <select name="status" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-600">
                <option value="active" selected>Active</option>
                <option value="draft">Draft</option>
                <option value="paused">Paused</option>
            </select>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-medium text-slate-700 mb-2">Target Audience</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach($audiences as $audience)
                <label class="flex gap-3 rounded-xl border border-slate-200 p-3 hover:border-blue-200 cursor-pointer">
                    <input type="checkbox" name="target_audiences[]" value="{{ $audience->id }}" class="mt-1 rounded border-slate-300 text-blue-600" checked>
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">{{ $audience->name }}</span>
                        <span class="block text-xs text-slate-500">{{ $audience->decision_maker }}</span>
                    </span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">Platforms</label>
            <div class="flex flex-wrap gap-2">
                @foreach($platforms as $value => $label)
                <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm cursor-pointer hover:border-blue-200">
                    <input type="checkbox" name="platforms[]" value="{{ $value }}" class="rounded border-slate-300 text-blue-600" @checked(in_array($value, ['blog', 'linkedin', 'facebook', 'instagram']))>
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary text-sm py-2 px-5">Create Campaign</button>
            <a href="{{ route('admin.marketing.index') }}" class="btn-secondary text-sm py-2 px-5">Cancel</a>
        </div>
    </div>
</form>
@endsection
