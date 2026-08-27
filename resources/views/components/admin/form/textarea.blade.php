@props([
    'label' => null,
    'name',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'rows' => 4,
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

    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
              @if($placeholder) placeholder="{{ $placeholder }}" @endif
              @if($required) required @endif
              {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>{{ old($name, $value) }}</textarea>

    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if($help)<div class="form-text">{{ $help }}</div>@endif
</div>
