@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-amber-50 hover:border-amber-300 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                {!! __('pagination.previous') !!}
            </a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-amber-50 hover:border-amber-300 hover:text-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 cursor-not-allowed rounded-lg">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
