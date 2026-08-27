@props([
    'label' => 'Gambar',
    'name' => 'image',
    'value' => null,
    'help' => null,
    'col' => null,
    'ratio' => '16 / 10',      // preview shape; use '1 / 1' for square art
    'hint' => 'Seret berkas ke sini atau klik untuk memilih',
    'dark' => false,           // preview on obsidian, for logos with transparency
    'fallback' => null,        // shown when the field is empty (e.g. bundled default)
])

@php
    $id = $name . '_' . \Illuminate\Support\Str::random(6);
    $stored = old($name, $value);
    $preview = media_url($stored, $fallback);
    $allowed = config('aldeftech.upload.allowed_mimes', ['jpg', 'jpeg', 'png', 'webp']);
    $accept = collect($allowed)->map(fn ($ext) => '.' . $ext)->implode(',');
    // 'jpeg' is the same thing as 'jpg' to an editor, so only name it once.
    $formatLabel = collect($allowed)->reject(fn ($ext) => $ext === 'jpeg')
        ->map(fn ($ext) => strtoupper($ext))->implode(', ');
@endphp

<div class="{{ $col ? 'col-' . $col : '' }} mb-4">
    <label class="form-label" for="{{ $id }}">{{ $label }}</label>

    <div class="aldef-uploader{{ $preview ? ' has-image' : '' }}{{ $dark ? ' is-dark' : '' }}"
         data-uploader
         data-uploader-url="{{ route('admin.uploads.store') }}"
         style="--aldef-uploader-ratio: {{ $ratio }};">

        {{-- What the form actually submits: a path string, exactly as before. --}}
        <input type="hidden" name="{{ $name }}" value="{{ $stored }}" data-uploader-value>

        <input type="file" id="{{ $id }}" class="aldef-uploader-input" accept="{{ $accept }}"
               data-uploader-file tabindex="-1">

        <button type="button" class="aldef-uploader-drop" data-uploader-trigger>
            <img src="{{ $preview ?: '' }}" alt="" class="aldef-uploader-preview"
                 data-uploader-preview @style(['display: none' => ! $preview])>

            <span class="aldef-uploader-empty" @style(['display: none' => (bool) $preview])>
                <i class="icon-base ti tabler-photo-up"></i>
                <span class="aldef-uploader-hint">{{ $hint }}</span>
                <span class="aldef-uploader-meta">{{ $formatLabel }} · maks {{ max_upload_label() }}</span>
            </span>

            <span class="aldef-uploader-veil" data-uploader-veil hidden>
                <span class="aldef-uploader-spinner"></span>
                <span class="aldef-uploader-veil-text">Mengunggah…</span>
            </span>
        </button>

        <div class="aldef-uploader-bar" data-uploader-bar hidden><span></span></div>

        <div class="aldef-uploader-actions" data-uploader-actions @style(['display: none' => ! $preview])>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-uploader-trigger>
                <i class="icon-base ti tabler-refresh me-1"></i>Ganti
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-uploader-clear>
                <i class="icon-base ti tabler-trash me-1"></i>Hapus
            </button>
            <span class="aldef-uploader-name" data-uploader-name></span>
        </div>

        <div class="aldef-uploader-error" data-uploader-error hidden></div>
    </div>

    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if($help)<div class="form-text">{{ $help }}</div>@endif
</div>
