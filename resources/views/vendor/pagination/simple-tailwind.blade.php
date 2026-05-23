@if ($paginator->hasPages())
<nav class="flex items-center justify-between gap-4">
    <div class="text-sm text-surface-400">
        Page {{ $paginator->currentPage() }}
        @if(method_exists($paginator, 'lastPage'))
        sur {{ $paginator->lastPage() }}
        @endif
    </div>
    <div class="flex items-center gap-2">
        @if ($paginator->onFirstPage())
        <span class="px-4 py-2 rounded-lg bg-surface-800 text-surface-600 text-sm font-medium cursor-not-allowed inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Précédent
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-4 py-2 rounded-lg bg-surface-800 hover:bg-surface-700 text-surface-300 hover:text-surface-100 text-sm font-medium transition-all duration-150 inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Précédent
        </a>
        @endif

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-4 py-2 rounded-lg bg-surface-800 hover:bg-surface-700 text-surface-300 hover:text-surface-100 text-sm font-medium transition-all duration-150 inline-flex items-center gap-1.5">
            Suivant
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @else
        <span class="px-4 py-2 rounded-lg bg-surface-800 text-surface-600 text-sm font-medium cursor-not-allowed inline-flex items-center gap-1.5">
            Suivant
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
        @endif
    </div>
</nav>
@endif
