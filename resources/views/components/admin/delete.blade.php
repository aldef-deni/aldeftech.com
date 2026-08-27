@props([
    'action',
    'label' => 'Hapus',
    'confirm' => 'Hapus data ini? Tindakan ini tidak dapat dibatalkan.',
    'icon' => true,
    'class' => 'btn btn-sm btn-icon btn-text-danger',
])

<form method="POST" action="{{ $action }}" class="d-inline"
      onsubmit="return confirm(@js($confirm));">
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $class }}" title="{{ $label }}">
        @if($icon)<i class="icon-base ti tabler-trash"></i>@endif
        @if(trim($slot ?? '') !== ''){{ $slot }}@endif
    </button>
</form>
