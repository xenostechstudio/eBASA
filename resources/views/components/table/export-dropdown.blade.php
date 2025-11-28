@props([
    'method' => 'export',
    'pdfLabel' => 'Export as PDF',
    'excelLabel' => 'Export as Excel',
    'ariaLabel' => 'Export data',
])

<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:bg-white/10"
        :aria-expanded="open"
        aria-haspopup="menu"
        aria-label="{{ $ariaLabel }}"
    >
        @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
    </button>

    <div
        x-cloak
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-10 mt-2 w-44 origin-top-right rounded-xl border border-slate-200 bg-white p-1 shadow-lg dark:border-white/10 dark:bg-slate-900"
        role="menu"
    >
        <button
            type="button"
            @click="open = false"
            wire:click="{{ $method }}('pdf')"
            class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5"
            role="menuitem"
        >
            @svg('heroicon-o-document-text', 'h-5 w-5 text-red-500')
            <span>{{ $pdfLabel }}</span>
        </button>
        <button
            type="button"
            @click="open = false"
            wire:click="{{ $method }}('excel')"
            class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5"
            role="menuitem"
        >
            @svg('heroicon-o-table-cells', 'h-5 w-5 text-emerald-500')
            <span>{{ $excelLabel }}</span>
        </button>
    </div>
</div>
