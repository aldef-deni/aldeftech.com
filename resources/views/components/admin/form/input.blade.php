@props(['label', 'name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false, 'help' => ''])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-text-secondary mb-1.5">
        {{ $label }}
        @if($required) <span class="text-danger">*</span> @endif
    </label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           @if($required) required @endif
           {{ $attributes->merge(['class' => 'w-full bg-brand-surface-2 border border-brand-border rounded-xl px-4 py-2.5 text-text-primary text-sm focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 transition-colors']) }}>
    @error($name)
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
    @if($help)
        <p class="text-text-dark text-xs mt-1">{{ $help }}</p>
    @endif
</div>
