@props([
    'label' => 'Status',
    'options' => [],
    'selected' => null,
    'onSelect' => null,
])

@php
    $selectedLabel = null;

    if ($selected !== null && array_key_exists($selected, $options)) {
        $selectedLabel = $options[$selected];
    } elseif (array_key_exists('all', $options)) {
        $selectedLabel = $options['all'];
    } else {
        $selectedLabel = reset($options) ?: 'All';
    }
@endphp

<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="mt-1 inline-flex items-center justify-center text-slate-500 transition hover:text-slate-700 dark:text-white/70 dark:hover:text-white"
        aria-label="{{ $label }} filter"
    >
        @svg('heroicon-m-square-3-stack-3d', 'h-7 w-7')
    </button>
    <div
        x-cloak
        x-show="open"
        @click.away="open = false"
        class="absolute right-0 z-20 mt-2 rounded-2xl border border-slate-200 bg-white text-sm text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900/95 dark:text-white"
    >
        <div class="flex divide-x divide-slate-100 dark:divide-white/10">
            {{-- Left: filter list (scales to multiple filters later) --}}
            <div class="w-40 p-3">
                <p class="text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">Filters</p>

                <div class="mt-3 space-y-1">
                    <div class="flex items-center justify-between rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white">
                        <span>{{ $label }}</span>
                        <span class="rounded-full bg-white/80 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500 shadow-sm dark:bg-slate-900 dark:text-white/70">
                            {{ $selectedLabel }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right: active filter options (Status) --}}
            <div class="w-56 p-3">
                <p class="px-1 text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">{{ strtoupper($label) }}</p>

                <div class="mt-1 space-y-1">
                    @foreach ($options as $key => $optionLabel)
                        <button
                            type="button"
                            @click.prevent="open = false"
                            @if ($onSelect)
                                wire:click="{{ $onSelect }}('{{ $key }}')"
                            @endif
                            class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left {{ $selected === $key ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-100 dark:text-white/80 dark:hover:bg-white/10' }}"
                        >
                            {{ $optionLabel }}
                            @if ($selected === $key)
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M5 10l3 3 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
