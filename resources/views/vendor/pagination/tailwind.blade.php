<div class="w-full">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-3 w-full">
            {{-- Mobile Pagination --}}
            <div class="flex justify-between items-center w-full sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-400 bg-white border border-gray-200 rounded-xl cursor-not-allowed shadow-2xs">
                        &larr; Previous
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:border-emerald-300 hover:text-emerald-700 rounded-xl transition shadow-2xs active:scale-95">
                        &larr; Previous
                    </a>
                @endif

                <span class="text-xs text-gray-500 font-medium">
                    Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
                </span>

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:border-emerald-300 hover:text-emerald-700 rounded-xl transition shadow-2xs active:scale-95">
                        Next &rarr;
                    </a>
                @else
                    <span class="inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-400 bg-white border border-gray-200 rounded-xl cursor-not-allowed shadow-2xs">
                        Next &rarr;
                    </span>
                @endif
            </div>

            {{-- Desktop Pagination --}}
            <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
                <div>
                    <p class="text-xs text-gray-500">
                        Showing
                        <span class="font-bold text-gray-900">{{ $paginator->firstItem() ?? 0 }}</span>
                        to
                        <span class="font-bold text-gray-900">{{ $paginator->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-bold text-gray-900">{{ number_format($paginator->total()) }}</span>
                        records
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-xl shadow-2xs border border-gray-200 bg-white divide-x divide-gray-200 overflow-hidden">
                        {{-- Previous Page Button --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-gray-300 bg-gray-50/50 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </span>
                            </span>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition" aria-label="{{ __('pagination.previous') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-400 bg-gray-50/50 cursor-default">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                            <span class="relative z-10 inline-flex items-center px-3.5 py-2 text-xs font-bold text-white bg-emerald-600 cursor-default">{{ $page }}</span>
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Button --}}
                        @if ($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition" aria-label="{{ __('pagination.next') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                <span class="relative inline-flex items-center px-3 py-2 text-xs font-medium text-gray-300 bg-gray-50/50 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
