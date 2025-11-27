@props([
    'columnVisibility' => [],
    'resetAction' => 'resetColumns',
])

<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="inline-flex h-11 w-11 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 dark:text-white/70 dark:hover:bg-white/10 dark:hover:text-white"
        aria-label="Toggle columns"
    >
        @svg('heroicon-s-view-columns', 'h-5 w-5')
    </button>
    <div
        x-cloak
        x-show="open"
        @click.away="open = false"
        class="absolute right-0 z-20 mt-2 w-48 rounded-2xl border border-slate-200 bg-white p-2 text-sm text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900/95 dark:text-white"
    >
        @php
            $activeColumns = array_filter($columnVisibility, fn ($visible) => $visible);
            $hiddenColumns = array_filter($columnVisibility, fn ($visible) => ! $visible);
        @endphp

        <div class="flex items-center justify-between px-3 text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">
            <span>Columns</span>
            <button
                type="button"
                wire:click="{{ $resetAction }}"
                class="rounded-full p-1.5 text-slate-500 transition hover:text-slate-700 dark:text-white/70 dark:hover:text-white"
                aria-label="Reset columns"
            >
                @svg('heroicon-s-arrow-path', 'h-4 w-4')
            </button>
        </div>

        <div class="mt-3 max-h-72 space-y-4 overflow-y-auto pr-1">
            @foreach ([['label' => 'Visible', 'columns' => $activeColumns], ['label' => 'Hidden', 'columns' => $hiddenColumns]] as $section)
                @if (count($section['columns']))
                    <div>
                        <div class="space-y-2">
                            @foreach ($section['columns'] as $column => $visible)
                                <label class="flex w-full items-center justify-between rounded-2xl border px-3 py-2 text-left transition {{ $visible ? 'border-slate-900 text-white bg-slate-900 dark:border-white dark:text-slate-900 dark:bg-white font-semibold' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 dark:border-white/15 dark:bg-white/5 dark:text-white/80 dark:hover:border-white/40' }}">
                                    <input type="checkbox" wire:model.live="columnVisibility.{{ $column }}" class="sr-only">
                                    <div>
                                        <p class="text-sm font-semibold">{{ ucfirst(str_replace('_', ' ', $column)) }}</p>
                                    </div>
                                    <span class="{{ $visible ? 'text-emerald-500 dark:text-emerald-400' : 'text-slate-400 dark:text-white/40' }}" aria-label="{{ $visible ? 'Active column' : 'Hidden column' }}">
                                        @if ($visible)
                                            @svg('heroicon-s-check-circle', 'h-5 w-5')
                                        @else
                                            @svg('heroicon-o-eye-slash', 'h-5 w-5')
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
