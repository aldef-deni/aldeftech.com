@props([
    'model' => null,
    'fields' => [],      // ['title' => ['label' => 'Judul', 'type' => 'text'|'textarea'|'list', 'rows' => 3]]
    'locale' => 'en',
    'title' => null,
])

@php
    $meta = config('locales.available.' . $locale, []);
    $label = $meta['native'] ?? strtoupper($locale);
@endphp

<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-label-primary">{{ $meta['short'] ?? strtoupper($locale) }}</span>
        <div>
            <h5 class="card-title mb-0">{{ $title ?? $label }}</h5>
            <small class="text-body-secondary">
                Dikosongkan berarti memakai teks Bahasa Indonesia di atas.
            </small>
        </div>
    </div>

    <div class="card-body">
        @foreach($fields as $name => $opts)
            @php
                $value = $model?->translate($name, $locale);
                $type  = $opts['type'] ?? 'text';
                $key   = "translations[{$locale}][{$name}]";
                $old   = old("translations.{$locale}.{$name}", $value);
            @endphp

            @if($type === 'list')
                {{-- The bracketed name makes PHP parse it into translations[locale][field][] --}}
                <x-admin.form.list
                    :label="$opts['label']"
                    :name="'translations[' . $locale . '][' . $name . ']'"
                    :items="is_array($old) ? $old : (array) $value"
                    :placeholder="$opts['placeholder'] ?? ''"
                    add-label="Tambah baris" />
            @elseif($type === 'textarea')
                <div class="mb-4">
                    <label class="form-label" for="{{ $locale }}_{{ $name }}">{{ $opts['label'] }}</label>
                    <textarea id="{{ $locale }}_{{ $name }}" name="{{ $key }}"
                              rows="{{ $opts['rows'] ?? 4 }}" class="form-control"
                              placeholder="{{ $opts['placeholder'] ?? '' }}">{{ $old }}</textarea>
                </div>
            @else
                <div class="mb-4">
                    <label class="form-label" for="{{ $locale }}_{{ $name }}">{{ $opts['label'] }}</label>
                    <input type="text" id="{{ $locale }}_{{ $name }}" name="{{ $key }}"
                           value="{{ $old }}" class="form-control"
                           placeholder="{{ $opts['placeholder'] ?? '' }}">
                </div>
            @endif
        @endforeach
    </div>
</div>
