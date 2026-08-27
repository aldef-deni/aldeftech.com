@props([
    'label' => null,
    'name',
    'options' => [],      // ['value' => 'Label'] or a flat list
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'help' => null,
    'col' => null,
])

@php
    $id = $attributes->get('id') ?? $name;
    $selected = old($name, $value);
    $isAssoc = array_keys($options) !== range(0, count($options) - 1);
@endphp

<div class="{{ $col ? 'col-'.$col : '' }} mb-4">
    @if($label)
    <label for="{{ $id }}" class="form-label">
        {{ $label }}@if($required) <span class="text-danger">*</span>@endif
    </label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->merge(['class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        @if($placeholder)<option value="">{{ $placeholder }}</option>@endif
        @foreach($options as $key => $text)
            @php $optValue = $isAssoc ? $key : $text; @endphp
            <option value="{{ $optValue }}" @selected((string) $selected === (string) $optValue)>{{ $text }}</option>
        @endforeach
    </select>

    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if($help)<div class="form-text">{{ $help }}</div>@endif
</div>
