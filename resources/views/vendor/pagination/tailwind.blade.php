@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile Navigation --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-lg shadow-sm dark:text-gray-500 dark:bg-gray-800 dark:border-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 leading-5 rounded-lg shadow-sm hover:bg-blue-500 hover:text-white hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-600 transition-all duration-200 ease-in-out transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-3 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 leading-5 rounded-lg shadow-sm hover:bg-blue-500 hover:text-white hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-600 transition-all duration-200 ease-in-out transform hover:scale-105">
                    {!! __('pagination.next') !!}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-3 ml-3 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default leading-5 rounded-lg shadow-sm dark:text-gray-500 dark:bg-gray-800 dark:border-gray-700">
                    {!! __('pagination.next') !!}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>

        {{-- Desktop Navigation --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-600 leading-5 bg-white px-4 py-2 rounded-lg border border-gray-300">
                    <span class="font-medium text-gray-700">Menampilkan</span>
                    @if ($paginator->firstItem())
                        <span class="font-bold text-blue-600">{{ $paginator->firstItem() }}</span>
                        <span class="mx-1">sampai</span>
                        <span class="font-bold text-blue-600">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-bold text-blue-600">{{ $paginator->count() }}</span>
                    @endif
                    <span class="mx-1">dari</span>
                    <span class="font-bold text-gray-800">{{ $paginator->total() }}</span>
                    <span class="ml-1">hasil</span>
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-lg rounded-xl bg-white border border-gray-300 overflow-hidden">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-3 py-3 text-sm font-medium text-gray-400 bg-white border-r border-gray-300 cursor-default" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-3 text-sm font-medium text-gray-600 bg-white border-r border-gray-300 hover:bg-blue-500 hover:text-white focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset active:bg-blue-600 transition-all duration-200 ease-in-out transform hover:scale-110" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif

                    {{-- Smart Pagination Elements --}}
                    @php
                        $current = $paginator->currentPage();
                        $last = $paginator->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                        
                        // Adjust window if at beginning or end
                        if ($current <= 3) {
                            $end = min($last, 5);
                        }
                        if ($current >= $last - 2) {
                            $start = max(1, $last - 4);
                        }
                    @endphp

                    {{-- First page --}}
                    @if ($start > 1)
                        @if ($current == 1)
                            <span aria-current="page">
                                <span class="relative inline-flex items-center px-4 py-3 text-sm font-bold text-white bg-blue-500 border-r border-gray-300 cursor-default shadow-md transform scale-110">1</span>
                            </span>
                        @else
                            <a href="{{ $paginator->url(1) }}" class="relative inline-flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-white border-r border-gray-300 hover:bg-blue-500 hover:text-white focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset active:bg-blue-600 transition-all duration-200 ease-in-out transform hover:scale-105 hover:-translate-y-1 hover:shadow-md" aria-label="{{ __('Go to page :page', ['page' => 1]) }}">
                                1
                            </a>
                        @endif

                        @if ($start > 2)
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-3 py-3 text-sm font-medium text-gray-500 bg-white border-r border-gray-300 cursor-default">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="3" cy="10" r="1.5"></circle>
                                        <circle cx="10" cy="10" r="1.5"></circle>
                                        <circle cx="17" cy="10" r="1.5"></circle>
                                    </svg>
                                </span>
                            </span>
                        @endif
                    @endif

                    {{-- Page Numbers --}}
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <span aria-current="page">
                                <span class="relative inline-flex items-center px-4 py-3 text-sm font-bold text-white bg-blue-500 border-r border-gray-300 cursor-default shadow-md transform scale-110">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $paginator->url($page) }}" class="relative inline-flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-white border-r border-gray-300 hover:bg-blue-500 hover:text-white focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset active:bg-blue-600 transition-all duration-200 ease-in-out transform hover:scale-105 hover:-translate-y-1 hover:shadow-md" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    {{-- Last page --}}
                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-3 py-3 text-sm font-medium text-gray-500 bg-white border-r border-gray-300 cursor-default">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="3" cy="10" r="1.5"></circle>
                                        <circle cx="10" cy="10" r="1.5"></circle>
                                        <circle cx="17" cy="10" r="1.5"></circle>
                                    </svg>
                                </span>
                            </span>
                        @endif

                        @if ($current == $last)
                            <span aria-current="page">
                                <span class="relative inline-flex items-center px-4 py-3 text-sm font-bold text-white bg-blue-500 border-r border-gray-300 cursor-default shadow-md transform scale-110">{{ $last }}</span>
                            </span>
                        @else
                            <a href="{{ $paginator->url($last) }}" class="relative inline-flex items-center px-4 py-3 text-sm font-medium text-gray-700 bg-white border-r border-gray-300 hover:bg-blue-500 hover:text-white focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset active:bg-blue-600 transition-all duration-200 ease-in-out transform hover:scale-105 hover:-translate-y-1 hover:shadow-md" aria-label="{{ __('Go to page :page', ['page' => $last]) }}">
                                {{ $last }}
                            </a>
                        @endif
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-3 text-sm font-medium text-gray-600 bg-white hover:bg-blue-500 hover:text-white focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset active:bg-blue-600 transition-all duration-200 ease-in-out transform hover:scale-110" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-3 py-3 text-sm font-medium text-gray-400 bg-white cursor-default" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif