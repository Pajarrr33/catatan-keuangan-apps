@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
@endphp

@if ($paginator->hasPages())
    <div class="flex justify-center items-center mt-6">
        <nav class="flex items-center gap-1 sm:gap-3 md:gap-5" aria-label="Pagination">
            
            {{-- First Page --}}
            <a href="{{ $paginator->url(1) }}"
               class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors {{ $paginator->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
               aria-label="Go to first page">
                <i class="bi bi-chevron-double-left"></i>
            </a>

            {{-- Previous Page --}}
            <a href="{{ $paginator->previousPageUrl() }}"
               class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors {{ $paginator->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
               aria-label="Go to previous page">
                <i class="bi bi-chevron-left"></i>
            </a>

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-gray-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <a href="{{ $url }}"
                           class="w-10 h-10 rounded-lg text-sm flex items-center justify-center 
                                  {{ $page == $currentPage ? 'bg-primary text-white font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
                           aria-current="{{ $page == $currentPage ? 'page' : false }}"
                           aria-label="Page {{ $page }}">
                            {{ $page }}
                        </a>
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page --}}
            <a href="{{ $paginator->nextPageUrl() }}"
               class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors {{ !$paginator->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
               aria-label="Go to next page">
                <i class="bi bi-chevron-right"></i>
            </a>

            {{-- Last Page --}}
            <a href="{{ $paginator->url($lastPage) }}"
               class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors {{ !$paginator->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
               aria-label="Go to last page">
                <i class="bi bi-chevron-double-right"></i>
            </a>
        </nav>
    </div>
@endif
