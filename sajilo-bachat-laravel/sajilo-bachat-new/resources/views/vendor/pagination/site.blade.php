@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="mt-10 flex flex-wrap items-center justify-center gap-1.5">
        @if ($paginator->onFirstPage())
            <span class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-300" aria-disabled="true">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-brand-50" aria-label="Previous">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-slate-400">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="rounded-lg bg-brand-600 px-3.5 py-2 text-sm font-bold text-white">{{ is_ne() ? devanagari_digits((string) $page) : $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rounded-lg border border-slate-200 px-3.5 py-2 text-sm hover:bg-brand-50">{{ is_ne() ? devanagari_digits((string) $page) : $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-brand-50" aria-label="Next">›</a>
        @else
            <span class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-300" aria-disabled="true">›</span>
        @endif
    </nav>
@endif
