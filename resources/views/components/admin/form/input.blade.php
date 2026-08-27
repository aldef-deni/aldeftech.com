@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'help' => null,
    'col' => null,
])

@php $id = $attributes->get('id') ?? $name; @endphp

<div class="{{ $col ? 'col-'.$col : '' }} mb-4">
    @if($label)
    <label for="{{ $id }}" class="form-label">
        {{ $label }}@if($required) <span class="text-danger">*</span>@endif
    </label>
    @endif

    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
           value="{{ old($name, $value) }}"
           @if($placeholder) placeholder="{{ $placeholder }}" @endif
           @if($required) required @endif
           {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>

    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if($help)<div class="form-text">{{ $help }}</div>@endif
</div>
