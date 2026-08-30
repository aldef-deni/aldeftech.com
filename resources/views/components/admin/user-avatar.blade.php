@props([
    'user' => null,
    'size' => null,      // Vuexy modifier: xs, sm, lg, xl — null for the default
    'online' => false,
])

@php
    $src = $user?->avatar_url;
    $classes = 'avatar' . ($size ? " avatar-{$size}" : '') . ($online ? ' avatar-online' : '');
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $user->name }}" class="rounded-circle">
    @else
        {{-- Initials, not a stock silhouette: initials read as a person,
             a generic placeholder reads as a broken image. --}}
        <span class="avatar-initial rounded-circle bg-label-primary">{{ initials_of($user?->name) }}</span>
    @endif
</span>
