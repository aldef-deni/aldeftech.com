@if ($paginator->hasPages())
<nav role="navigation" aria-label="Navigasi halaman" class="flex items-center justify-between gap-4 flex-wrap">

    <p class="text-xs text-graphite-500 tabular">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }}
    </p>

    <div class="flex items-center gap-1.5">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="page-pill is-disabled" aria-disabled="true">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-pill" aria-label="Halaman sebelumnya">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
        @endif

        {{-- Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-pill is-gap" aria-hidden="true">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-pill is-current tabular" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-pill tabular">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-pill" aria-label="Halaman berikutnya">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="page-pill is-disabled" aria-disabled="true">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif
    </div>
</nav>
@endif
