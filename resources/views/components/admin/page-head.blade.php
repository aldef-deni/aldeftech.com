@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'back' => null,
])

<div class="aldef-page-head">
    <div>
        @if($eyebrow)<span class="aldef-eyebrow">{{ $eyebrow }}</span>@endif

        <h4 class="fw-bold mb-0 d-flex align-items-center gap-2">
            @if($back)
            <a href="{{ $back }}" class="text-body-secondary d-inline-flex" title="Kembali">
                <i class="icon-base ti tabler-arrow-left icon-md"></i>
            </a>
            @endif
            {{ $title }}
        </h4>

        @if($subtitle)<p class="text-body-secondary mb-0 mt-1">{{ $subtitle }}</p>@endif
    </div>

    @if(trim($slot ?? '') !== '')
    <div class="d-flex align-items-center gap-2 flex-wrap">{{ $slot }}</div>
    @endif
</div>
