@if ($paginator->hasPages())
    @php
        $item = 'inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-lg border px-3 font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-brand';
    @endphp

    <nav role="navigation" aria-label="Navigasi halaman">
        <ul class="flex flex-wrap items-center gap-1">
            {{-- Sebelumnya --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true"
                          class="{{ $item }} cursor-not-allowed border-stone-200 bg-stone-50 text-stone-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                        <span class="sr-only">Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       class="{{ $item }} border-stone-300 bg-white text-stone-700 hover:bg-stone-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                        <span class="sr-only">Sebelumnya</span>
                    </a>
                @endif
            </li>

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-hidden="true">
                        <span class="inline-flex h-9 items-center px-2 text-stone-400">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                      class="{{ $item }} border-brand bg-brand text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="Ke halaman {{ $page }}"
                                   class="{{ $item }} border-stone-300 bg-white text-stone-700 hover:bg-stone-50">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                       class="{{ $item }} border-stone-300 bg-white text-stone-700 hover:bg-stone-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="sr-only">Berikutnya</span>
                    </a>
                @else
                    <span aria-disabled="true"
                          class="{{ $item }} cursor-not-allowed border-stone-200 bg-stone-50 text-stone-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                        <span class="sr-only">Berikutnya</span>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
