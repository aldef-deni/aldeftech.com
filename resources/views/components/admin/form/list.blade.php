@props([
    'label' => 'Daftar',
    'name',                 // submitted as name[]
    'items' => [],
    'placeholder' => '',
    'help' => null,
    'addLabel' => 'Tambah baris',
    'min' => 1,
])

@php
    // A single textarea with name="foo[]" collapses every line into ONE array
    // element; one input per row is what the controllers actually validate.
    $rows = old($name, is_array($items) ? $items : []);
    $rows = array_values(array_filter((array) $rows, fn ($v) => trim((string) $v) !== ''));
    if (count($rows) < $min) {
        $rows = array_pad($rows, $min, '');
    }
@endphp

<div class="mb-4" data-repeater>
    <label class="form-label">{{ $label }}</label>

    <div data-repeater-list>
        @foreach($rows as $row)
        <div class="input-group mb-2" data-repeater-item>
            <input type="text" name="{{ $name }}[]" value="{{ $row }}"
                   class="form-control" placeholder="{{ $placeholder }}">
            <button type="button" class="btn btn-outline-secondary" data-repeater-remove title="Hapus baris">
                <i class="icon-base ti tabler-x"></i>
            </button>
        </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-repeater-add>
        <i class="icon-base ti tabler-plus me-1"></i>{{ $addLabel }}
    </button>

    @if($help)<div class="form-text">{{ $help }}</div>@endif
    @error($name)<div class="text-danger mt-1"><small>{{ $message }}</small></div>@enderror
</div>
