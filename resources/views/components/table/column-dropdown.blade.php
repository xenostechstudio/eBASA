@props([
    'columnVisibility' => [],
    'resetAction' => 'resetColumns',
])

<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="mt-1 inline-flex items-center justify-center text-white/70 transition hover:text-white"
        aria-label="Toggle columns"
    >
        @svg('heroicon-s-view-columns', 'h-7 w-7')
    </button>
    <div
        x-cloak
        x-show="open"
        @click.away="open = false"
        class="absolute right-0 z-20 mt-2 w-48 rounded-2xl border border-white/10 bg-slate-900/95 p-2 text-sm text-white shadow-xl"
    >
        @php
            $activeColumns = array_filter($columnVisibility, fn ($visible) => $visible);
            $hiddenColumns = array_filter($columnVisibility, fn ($visible) => ! $visible);
        @endphp

        <div class="flex items-center justify-between px-3 text-[11px] uppercase tracking-[0.35em] text-white/40">
            <span>Columns</span>
            <button
                type="button"
                wire:click="{{ $resetAction }}"
                class="rounded-full p-1.5 text-white/70 transition hover:text-white"
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
                                <label class="flex w-full items-center justify-between rounded-2xl border px-3 py-2 text-left transition {{ $visible ? 'border-white text-slate-900 bg-white font-semibold' : 'border-white/15 bg-white/5 text-white/80 hover:border-white/40' }}">
                                    <input type="checkbox" wire:model.live="columnVisibility.{{ $column }}" class="sr-only">
                                    <div>
        									<p class="text-sm font-semibold">{{ ucfirst(str_replace('_', ' ', $column)) }}</p>
                                    </div>
                                    <span class="text-xs {{ $visible ? 'text-emerald-400' : 'text-white/40' }}" aria-label="{{ $visible ? 'Active column' : 'Hidden column' }}">
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
