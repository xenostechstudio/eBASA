@props([
    'paginator',
    'perPageOptions' => [],
])

@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $startPage = max(1, $currentPage - 2);
    $endPage = min($lastPage, $currentPage + 2);
    $pageName = $paginator->getPageName();
@endphp

<div class="flex flex-col gap-3 border-t border-slate-200 dark:border-white/10 px-6 py-4 text-sm text-slate-700 dark:text-white/70 md:flex-row md:items-center">
    <div class="md:w-1/3">
        @if ($paginator->total())
            <p>Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} results</p>
        @else
            <p>No records found</p>
        @endif
    </div>

    <div class="md:w-1/3 flex justify-start md:justify-center">
        <div class="relative">
            <div class="flex divide-x divide-slate-300 dark:divide-white/10 overflow-hidden rounded-lg border border-slate-300 dark:border-white/10 bg-white dark:bg-slate-950/40 text-center text-slate-600 dark:text-white/60">
                <div class="flex min-w-[64px] items-center justify-center px-4 py-1 text-[11px] uppercase tracking-[0.35em]">Rows</div>
                <div class="relative flex min-w-[64px] items-center justify-center px-2 py-1">
                    <select
                        wire:model.live="perPage"
                        class="w-full appearance-none border-none bg-transparent px-0 pr-6 text-sm font-semibold text-slate-900 dark:text-white text-center leading-tight focus:outline-none focus:ring-0"
                        style="background-image:none;text-align:center;text-align-last:center;"
                    >
                        @foreach ($perPageOptions as $option)
                            <option class="bg-white dark:bg-slate-900 text-left" value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 h-3 w-3 -translate-y-1/2 text-slate-400 dark:text-white/60" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 1.5L5 4.5L9 1.5" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="md:w-1/3 flex justify-end">
        <div class="flex divide-x divide-slate-300 dark:divide-white/10 overflow-hidden rounded-xl border border-slate-300 dark:border-white/15 bg-white dark:bg-slate-950/30 text-slate-700 dark:text-white">
            <button
                type="button"
                wire:click="previousPage('{{ $pageName }}')"
                @disabled($paginator->onFirstPage())
                class="flex h-10 w-10 items-center justify-center text-slate-500 dark:text-white/70 transition hover:text-slate-900 dark:hover:text-white disabled:opacity-30"
            >
                @svg('heroicon-s-chevron-left', 'h-4 w-4')
            </button>

            <div class="flex divide-x divide-slate-300 dark:divide-white/10">
                @foreach (range($startPage, $endPage) as $page)
                    <button
                        type="button"
                        wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                        class="flex h-10 w-10 items-center justify-center text-sm font-semibold {{ $page === $currentPage ? 'bg-slate-900 text-white shadow-sm dark:bg-white dark:text-slate-900' : 'text-slate-500 hover:bg-slate-100 dark:text-white/70 dark:hover:bg-white/15 dark:hover:text-white' }}"
                    >
                        {{ $page }}
                    </button>
                @endforeach
            </div>

            <button
                type="button"
                wire:click="nextPage('{{ $pageName }}')"
                @disabled(! $paginator->hasMorePages())
                class="flex h-10 w-10 items-center justify-center text-slate-500 dark:text-white/70 transition hover:text-slate-900 dark:hover:text-white disabled:opacity-30"
            >
                @svg('heroicon-s-chevron-right', 'h-4 w-4')
            </button>
        </div>
    </div>
</div>
