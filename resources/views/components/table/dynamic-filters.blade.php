@props([
    // filters: associative array keyed by filter key (e.g. 'status', 'category')
    // Each filter: [
    //   'label' => 'Status',
    //   'options' => ['' => 'All', 'active' => 'Active'],
    //   'selected' => $statusFilter,
    //   'default' => '', // optional, defaults to ''
    //   'onSelect' => 'setStatusFilter', // Livewire method name
    // ]
    'filters' => [],
])

@php
    $filters = collect($filters)->map(function ($filter, $key) {
        $selected = (string)($filter['selected'] ?? '');
        $default = array_key_exists('default', $filter) ? (string) $filter['default'] : '';
        $active = $selected !== $default;

        $filter['key'] = $filter['key'] ?? $key;
        $filter['activeCount'] = $active ? 1 : 0;

        return $filter;
    });

    $totalActive = $filters->sum('activeCount');
    $firstKey = optional($filters->first())['key'] ?? '';
@endphp

@if ($filters->isNotEmpty())
    <div class="relative" x-data="{ open: false, activeFilter: '{{ $firstKey }}' }">
        <button
            type="button"
            @click="open = !open"
            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:bg-white/10"
            aria-label="Filters"
        >
            @svg('heroicon-m-adjustments-horizontal', 'h-5 w-5')
        </button>

        <div
            x-cloak
            x-show="open"
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1"
            class="absolute right-0 z-20 mt-2 rounded-2xl border border-slate-200 bg-white text-sm text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900/95 dark:text-white"
        >
            <div class="flex divide-x divide-slate-100 dark:divide-white/10">
                {{-- Left: filter list --}}
                <div class="w-40 p-3">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">Filters</p>

                    <div class="mt-3 space-y-1">
                        @foreach ($filters as $filter)
                            <button
                                type="button"
                                @click="activeFilter = '{{ $filter['key'] }}'"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-medium"
                                :class="activeFilter === '{{ $filter['key'] }}' ? 'bg-slate-100 text-slate-800 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5'"
                            >
                                <span>{{ $filter['label'] }}</span>
                                <span class="rounded-full bg-white/80 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500 shadow-sm dark:bg-slate-900 dark:text-white/70">
                                    {{ $filter['activeCount'] }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Right: options for active filter --}}
                <div class="w-56 p-3">
                    @foreach ($filters as $filter)
                        <div x-show="activeFilter === '{{ $filter['key'] }}'">
                            <p class="px-1 text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">{{ strtoupper($filter['label']) }}</p>
                            <div class="mt-1 max-h-60 space-y-1 overflow-y-auto">
                                @foreach ($filter['options'] as $optionKey => $optionLabel)
                                    @php
                                        $selected = (string)($filter['selected'] ?? '');
                                        $isSelected = $selected === (string) $optionKey;
                                    @endphp
                                    <button
                                        type="button"
                                        @click.prevent="open = false"
                                        @if (! empty($filter['onSelect'] ?? null))
                                            wire:click="{{ $filter['onSelect'] }}('{{ $optionKey }}')"
                                        @endif
                                        class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left {{ $isSelected ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-100 dark:text-white/80 dark:hover:bg-white/10' }}"
                                    >
                                        {{ $optionLabel }}
                                        @if ($isSelected)
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M5 10l3 3 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 flex items-center justify-between px-1 text-xs text-slate-500 dark:text-white/60">
                        {{-- Reset can be handled via a custom Livewire method if desired --}}
                        {{-- Example: pass a separate reset action prop in future if needed --}}
                        <span class="text-[11px] uppercase tracking-[0.2em]">
                            {{ $totalActive }} active
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
