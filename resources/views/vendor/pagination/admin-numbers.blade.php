{{-- Page numbers only, for admin lists that print their own "x–y dari z" line. --}}
@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman">
        <ul class="pagination pagination-sm mb-0 flex-wrap">
            @if ($paginator->onFirstPage())
                <li class="page-item prev disabled"><span class="page-link" aria-hidden="true"><i class="icon-base ti tabler-chevron-left icon-sm"></i></span></li>
            @else
                <li class="page-item prev"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"><i class="icon-base ti tabler-chevron-left icon-sm"></i></a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item next"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"><i class="icon-base ti tabler-chevron-right icon-sm"></i></a></li>
            @else
                <li class="page-item next disabled"><span class="page-link" aria-hidden="true"><i class="icon-base ti tabler-chevron-right icon-sm"></i></span></li>
            @endif
        </ul>
    </nav>
@endif
