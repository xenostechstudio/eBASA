@props([
    'count' => 0,
    'total' => null,
    'description' => null,
    'selectAllAction' => 'selectAll',
    'selectAllLabel' => 'Select all',
    'selectPageAction' => 'selectPage',
    'selectPageLabel' => 'Select all on page',
    'deselectAction' => 'deselectAll',
    'deselectLabel' => 'Deselect all',
    'deleteAction' => 'deleteSelected',
    'deleteLabel' => 'Delete selected',
    'showDelete' => true,
])

@php
    $description = $description ?? \Illuminate\Support\Str::plural('item', $count) . ' selected';
@endphp

@if ($count > 0)
    <div class="flex flex-wrap items-center text-xs" x-data="{ open: false }">
        <div class="relative">
            <button
                type="button"
                @click="open = !open"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-slate-100 px-4 font-medium text-slate-700 transition hover:bg-slate-200 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20"
            >
                @svg('heroicon-o-check-circle', 'h-4 w-4 text-emerald-600 dark:text-emerald-400')
                <span>
                    <span class="font-semibold">{{ $count }}</span>
                    {{ $description }}
                </span>
                @svg('heroicon-o-chevron-down', 'h-3 w-3')
            </button>

            <div
                x-cloak
                x-show="open"
                x-transition.origin.top.left
                @click.outside="open = false"
                class="absolute left-0 z-40 mt-2 min-w-[12rem] rounded-xl border border-slate-200 bg-white p-1.5 text-sm text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900 dark:text-white"
            >
                @if ($showDelete && $deleteAction)
                    <button
                        type="button"
                        wire:click="{{ $deleteAction }}"
                        @click="open = false"
                        wire:confirm="Are you sure you want to delete {{ $count }} {{ \Illuminate\Support\Str::plural('item', $count) }}?"
                        class="mb-1 flex w-full items-center gap-2.5 rounded-lg bg-red-50 px-3 py-2.5 text-left text-red-600 transition hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20"
                    >
                        @svg('heroicon-o-trash', 'h-4 w-4')
                        <span>{{ $deleteLabel }}</span>
                    </button>
                @endif

                <div class="space-y-0.5">
                    @if ($selectAllAction && $total && $count < $total)
                        <button
                            type="button"
                            wire:click="{{ $selectAllAction }}"
                            @click="open = false"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left transition hover:bg-slate-100 dark:hover:bg-white/10"
                        >
                            @svg('heroicon-o-check-circle', 'h-4 w-4')
                            <span>{{ $selectAllLabel }} ({{ $total }})</span>
                        </button>
                    @endif

                    @if ($selectPageAction)
                        <button
                            type="button"
                            wire:click="{{ $selectPageAction }}"
                            @click="open = false"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left transition hover:bg-slate-100 dark:hover:bg-white/10"
                        >
                            @svg('heroicon-o-check', 'h-4 w-4')
                            <span>{{ $selectPageLabel }}</span>
                        </button>
                    @endif

                    @if ($deselectAction)
                        <button
                            type="button"
                            wire:click="{{ $deselectAction }}"
                            @click="open = false"
                            class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left transition hover:bg-slate-100 dark:hover:bg-white/10"
                        >
                            @svg('heroicon-o-x-mark', 'h-4 w-4')
                            <span>{{ $deselectLabel }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
