@props([
    'count' => 0,
    'description' => null,
    'selectAllAction' => null,
    'selectAllLabel' => 'Select all on page',
    'deselectAction' => null,
    'deselectLabel' => 'Deselect all',
    'deleteAction' => null,
    'deleteLabel' => 'Delete selected',
])

@php
    $description = $description ?? \Illuminate\Support\Str::plural('selected', $count);
@endphp

@if ($count > 0)
    <div class="flex flex-wrap items-center text-xs">
        <div class="relative" x-data="{ open: false }">
            <button
                type="button"
                @click="open = !open"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-slate-100 px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-200 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20"
            >
                @svg('heroicon-o-check-circle', 'h-4 w-4')
                <span>
                    <span class="font-semibold">{{ $count }}</span>
                    {{ $description }}
                </span>
                @svg('heroicon-o-chevron-down', 'h-4 w-4')
            </button>
            <div
                x-cloak
                x-show="open"
                x-transition.origin.top.left
                @click.outside="open = false"
                class="absolute left-0 z-40 mt-2 min-w-[11rem] rounded-xl border border-slate-200 bg-white p-1 text-xs text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900/95 dark:text-white"
            >
                @if ($deleteAction)
                    <button
                        type="button"
                        wire:click="{{ $deleteAction }}"
                        class="mb-1 flex w-full items-center gap-2 rounded-lg bg-red-50 px-3 py-2 text-left text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/20"
                    >
                        @svg('heroicon-o-trash', 'h-4 w-4')
                        <span>{{ $deleteLabel }}</span>
                    </button>
                @endif

                @if ($selectAllAction)
                    <button
                        type="button"
                        wire:click="{{ $selectAllAction }}"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-white/10"
                    >
                        @svg('heroicon-o-check', 'h-4 w-4')
                        <span>{{ $selectAllLabel }}</span>
                    </button>
                @endif

                @if ($deselectAction)
                    <button
                        type="button"
                        wire:click="{{ $deselectAction }}"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-white/10"
                    >
                        @svg('heroicon-o-x-mark', 'h-4 w-4')
                        <span>{{ $deselectLabel }}</span>
                    </button>
                @endif

                <div class="my-1 border-t border-slate-200 dark:border-white/10"></div>
                <p class="px-3 py-1 text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/30">Selection</p>
            </div>
        </div>
    </div>
@endif
