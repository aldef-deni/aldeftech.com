@extends('layouts.admin')
@php $pageTitle = 'WhatsApp Settings'; @endphp
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}">
        @csrf @method('PUT')
        <x-admin.form.input label="WhatsApp Number" name="whatsapp_number" :value="\App\Models\SiteSetting::get('whatsapp_number', config('aldeftech.whatsapp.number'))" placeholder="6281234567890" help="Include country code, no + or spaces" />
        <x-admin.form.textarea label="Default Message" name="whatsapp_default_message" :value="\App\Models\SiteSetting::get('whatsapp_default_message', config('aldeftech.whatsapp.default_message'))" :rows="3" />
        <div class="mt-4 p-4 bg-brand-surface-2 rounded-xl">
            <p class="text-xs text-text-muted">
                Preview: <a href="#" class="text-accent" id="wa-preview" target="_blank">https://wa.me/...</a>
            </p>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn-primary text-sm py-2.5 px-6">Save WhatsApp Settings</button>
        </div>
    </form>
</div>
@endsection
