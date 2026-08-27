@props([
    'icon' => 'tabler-inbox',
    'title' => 'Belum ada data',
    'message' => null,
])

<div class="aldef-empty">
    <i class="icon-base ti {{ $icon }}"></i>
    <h6 class="mb-1">{{ $title }}</h6>
    @if($message)<p class="text-body-secondary mb-3">{{ $message }}</p>@endif
    @if(trim($slot ?? '') !== ''){{ $slot }}@endif
</div>
