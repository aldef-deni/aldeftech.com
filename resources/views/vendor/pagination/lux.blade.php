@if ($paginator->hasPages())
@once
    @push('styles')
    <style>
        /* Pagination — an ivory tray that lifts the page pills off the page,
           so the current page reads as a champagne seal rather than just
           another button. Styles travel with the partial (and only the blog
           uses it) rather than waiting on a stylesheet rebuild. */
        .blog-pagination {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.875rem 1rem;
        }
        .blog-pagination-count {
            font-size: 0.75rem;
            letter-spacing: 0.02em;
            color: #8D97A2;
        }
        .blog-pagination-pills {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem;
            border: 1px solid var(--color-line, #E8E3D8);
            border-radius: 1rem;
            background: linear-gradient(180deg, #F9F7F2 0%, #F3F0E8 100%);
            box-shadow:
                inset 0 1px 0 rgb(255 255 255 / 85%),
                0 20px 44px -36px rgb(13 20 32 / 55%);
        }
        .blog-pagination-pills .page-pill {
            min-width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.625rem;
        }
        @media (min-width: 640px) {
            .blog-pagination { justify-content: space-between; }
        }
    </style>
    @endpush
@endonce

<nav role="navigation" aria-label="{{ __('pages.blog.pagination') }}" class="blog-pagination">

    <p class="blog-pagination-count tabular">
        {{ __('pages.blog.showing', ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) }}
    </p>

    <div class="blog-pagination-pills">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="page-pill is-disabled" aria-disabled="true">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-pill" aria-label="{{ __('pages.blog.previous_page') }}">
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
                        <a href="{{ $url }}" class="page-pill tabular" aria-label="{{ __('pages.blog.page_number', ['page' => $page]) }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-pill" aria-label="{{ __('pages.blog.next_page') }}">
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
