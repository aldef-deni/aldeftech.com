@props([
    'client',
    // The marquee renders the same logos a second time to make the loop
    // seamless. That pass is decoration, so it is hidden from screen readers
    // instead of being announced twice.
    'decorative' => false,
])

@php
    $href = $decorative ? null : $client->website();
    $logo = media_url($client->logo);
@endphp

{{-- 180×72 matches the tile's proportions, so the browser reserves the space
     before the file lands and the band never shifts as logos load. --}}
@if($href)
    <a {{ $attributes->merge(['class' => 'client-logo']) }}
       href="{{ $href }}" target="_blank" rel="noopener noreferrer">
        <img src="{{ $logo }}" alt="{{ $client->name }}"
             width="180" height="72" loading="lazy" decoding="async">
    </a>
@else
    <span {{ $attributes->merge(['class' => 'client-logo']) }} @if($decorative) aria-hidden="true" @endif>
        <img src="{{ $logo }}" alt="{{ $client->name }}"
             width="180" height="72" loading="lazy" decoding="async">
    </span>
@endif
