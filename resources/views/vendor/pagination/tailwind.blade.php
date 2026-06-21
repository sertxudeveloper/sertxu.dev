@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
        <div class="flex gap-1 sm:hidden">
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
        </div>

        <div class="hidden sm:flex gap-1 items-center">
            {{-- First Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.first') }}">
                    <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-500 bg-neutral-900 border border-neutral-800 cursor-not-allowed rounded-lg leading-5" aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M15.707 4.293a1 1 0 010 1.414L12.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M9.707 4.293a1 1 0 010 1.414L6.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            @else
                <a href="{{ $paginator->url(1) }}" class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-300 bg-neutral-900 border border-neutral-800 rounded-lg leading-5 hover:bg-neutral-800 transition-all duration-200" aria-label="{{ __('pagination.first') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M15.707 4.293a1 1 0 010 1.414L12.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M9.707 4.293a1 1 0 010 1.414L6.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-500 bg-neutral-900 border border-neutral-800 cursor-not-allowed rounded-lg leading-5" aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-300 bg-neutral-900 border border-neutral-800 rounded-lg leading-5 hover:bg-neutral-800 transition-all duration-200" aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Window Pagination Links --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $side = $paginator->onEachSide;
                $start = max($currentPage - $side, 1);
                $end = min($currentPage + $side, $lastPage);
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <span aria-current="page">
                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium font-mono text-white bg-ocean border border-ocean cursor-default leading-5 rounded-lg">{{ $i }}</span>
                    </span>
                @else
                    <a href="{{ $paginator->url($i) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium font-mono text-neutral-300 bg-neutral-900 border border-neutral-800 leading-5 rounded-lg hover:bg-neutral-800 transition-all duration-200" aria-label="{{ __('Go to page :page', ['page' => $i]) }}">
                        {{ $i }}
                    </a>
                @endif
            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-300 bg-neutral-900 border border-neutral-800 rounded-lg leading-5 hover:bg-neutral-800 transition-all duration-200" aria-label="{{ __('pagination.next') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-500 bg-neutral-900 border border-neutral-800 cursor-not-allowed rounded-lg leading-5" aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->currentPage() === $paginator->lastPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.last') }}">
                    <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-500 bg-neutral-900 border border-neutral-800 cursor-not-allowed rounded-lg leading-5" aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L7.586 10 4.293 6.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M10.293 4.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 10l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </span>
            @else
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-300 bg-neutral-900 border border-neutral-800 rounded-lg leading-5 hover:bg-neutral-800 transition-all duration-200" aria-label="{{ __('pagination.last') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L7.586 10 4.293 6.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M10.293 4.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L13.586 10l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>
    </nav>
@endif
