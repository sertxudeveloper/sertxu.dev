@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-1 items-center justify-center">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-500 bg-neutral-900 border border-neutral-800 cursor-not-allowed leading-5 rounded-lg">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-300 bg-neutral-900 border border-neutral-800 leading-5 rounded-lg hover:bg-neutral-800 transition-all duration-200">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-300 bg-neutral-900 border border-neutral-800 leading-5 rounded-lg hover:bg-neutral-800 transition-all duration-200">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-500 bg-neutral-900 border border-neutral-800 cursor-not-allowed leading-5 rounded-lg">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
