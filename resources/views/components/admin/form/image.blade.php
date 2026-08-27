@props([
    'label' => 'Gambar',
    'name' => 'image',
    'value' => null,
    'help' => 'JPG, PNG, WEBP atau AVIF. Maksimal 5 MB.',
    'col' => null,
])

@php
    $id = $attributes->get('id') ?? $name;
    $current = media_url($value);
@endphp

<div class="{{ $col ? 'col-'.$col : '' }} mb-4" data-image-field>
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>

    <div class="d-flex align-items-start gap-3 flex-wrap">
        <img src="{{ $current ?: 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 16 10%22%3E%3C/svg%3E' }}"
             alt="" class="aldef-thumb-lg" data-image-preview
             style="{{ $current ? '' : 'display:none;' }}">

        <div class="flex-grow-1" style="min-width: 15rem;">
            <input type="file" id="{{ $id }}" name="{{ $name }}" accept="image/*"
                   {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
            @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            <div class="form-text">{{ $help }}</div>

            @if($current)
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="{{ $id }}_remove" name="remove_{{ $name }}" value="1">
                <label class="form-check-label small" for="{{ $id }}_remove">Hapus gambar saat ini</label>
            </div>
            @endif
        </div>
    </div>
</div>
