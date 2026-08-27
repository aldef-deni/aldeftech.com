@props([
    'label',
    'name',
    'checked' => false,
    'help' => null,
    'value' => 1,
    'col' => null,
])

@php $id = $attributes->get('id') ?? $name; @endphp

<div class="{{ $col ? 'col-'.$col : '' }} mb-4">
    {{-- Unchecked switches submit nothing, so pair it with a hidden 0. --}}
    <input type="hidden" name="{{ $name }}" value="0">

    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch"
               id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
               @checked(old($name, $checked))>
        <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    </div>

    @if($help)<div class="form-text">{{ $help }}</div>@endif
    @error($name)<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
</div>
