@props([
    'statusFilter' => 'all',
    'departmentFilter' => 'all',
    'positionFilter' => 'all',
    'departments' => collect(),
    'positions' => collect(),
])

@php
    $statusOptions = [
        'all' => 'All',
        'active' => 'Active',
        'on_leave' => 'On leave',
        'probation' => 'Probation',
    ];

    $departmentOptions = array_merge(
        ['all' => 'All departments'],
        $departments->pluck('name', 'id')->toArray()
    );

    $positionOptions = array_merge(
        ['all' => 'All positions'],
        $positions->pluck('title', 'id')->toArray()
    );

    $statusActiveCount = $statusFilter !== 'all' ? 1 : 0;
    $departmentActiveCount = $departmentFilter !== 'all' ? 1 : 0;
    $positionActiveCount = $positionFilter !== 'all' ? 1 : 0;
    $totalActiveFilters = $statusActiveCount + $departmentActiveCount + $positionActiveCount;
@endphp

<div class="relative" x-data="{ open: false, activeFilter: 'status' }">
    <button
        type="button"
        @click="open = !open"
        class="inline-flex h-11 w-11 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 dark:text-white/70 dark:hover:bg-white/10 dark:hover:text-white"
        aria-label="Filters"
    >
        @svg('heroicon-m-adjustments-horizontal', 'h-5 w-5')
    </button>

    <div
        x-cloak
        x-show="open"
        @click.away="open = false"
        class="absolute right-0 z-20 mt-2 rounded-2xl border border-slate-200 bg-white text-sm text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900/95 dark:text-white"
    >
        <div class="flex divide-x divide-slate-100 dark:divide-white/10">
            {{-- Left: filter list --}}
            <div class="w-40 p-3">
                <p class="text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">Filters</p>

                <div class="mt-3 space-y-1">
                    {{-- Status row --}}
                    <button
                        type="button"
                        @click="activeFilter = 'status'"
                        class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-medium"
                        :class="activeFilter === 'status' ? 'bg-slate-100 text-slate-800 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5'"
                    >
                        <span>Status</span>
                        <span class="rounded-full bg-white/80 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500 shadow-sm dark:bg-slate-900 dark:text-white/70">
                            {{ $statusActiveCount }}
                        </span>
                    </button>

                    {{-- Department row --}}
                    <button
                        type="button"
                        @click="activeFilter = 'department'"
                        class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-medium"
                        :class="activeFilter === 'department' ? 'bg-slate-100 text-slate-800 dark:bg-white/10 dark:text:white' : 'text-slate-600 hover:bg-slate-50 dark:text:white/70 dark:hover:bg-white/5'"
                    >
                        <span>Department</span>
                        <span class="rounded-full bg-white/80 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500 shadow-sm dark:bg-slate-900 dark:text:white/70">
                            {{ $departmentActiveCount }}
                        </span>
                    </button>

                    {{-- Position row --}}
                    <button
                        type="button"
                        @click="activeFilter = 'position'"
                        class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-medium"
                        :class="activeFilter === 'position' ? 'bg-slate-100 text-slate-800 dark:bg-white/10 dark:text:white' : 'text-slate-600 hover:bg-slate-50 dark:text:white/70 dark:hover:bg-white/5'"
                    >
                        <span>Position</span>
                        <span class="rounded-full bg-white/80 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500 shadow-sm dark:bg-slate-900 dark:text:white/70">
                            {{ $positionActiveCount }}
                        </span>
                    </button>
                </div>
            </div>

            {{-- Right: options for active filter --}}
            <div class="w-56 p-3">
                {{-- Status options --}}
                <div x-show="activeFilter === 'status'">
                    <p class="px-1 text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">STATUS</p>
                    <div class="mt-1 space-y-1">
                        @foreach ($statusOptions as $key => $optionLabel)
                            <button
                                type="button"
                                @click.prevent="open = false"
                                wire:click="setStatusFilter('{{ $key }}')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left {{ $statusFilter === $key ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-100 dark:text-white/80 dark:hover:bg-white/10' }}"
                            >
                                {{ $optionLabel }}
                                @if ($statusFilter === $key)
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M5 10l3 3 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Department options --}}
                <div x-show="activeFilter === 'department'">
                    <p class="px-1 text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">DEPARTMENT</p>
                    <div class="mt-1 max-h-60 space-y-1 overflow-y-auto">
                        @foreach ($departmentOptions as $key => $optionLabel)
                            <button
                                type="button"
                                @click.prevent="open = false"
                                wire:click="setDepartmentFilter('{{ $key }}')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left {{ $departmentFilter === (string) $key ? 'bg-slate-900 text-white dark:bg:white dark:text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-100 dark:text-white/80 dark:hover:bg:white/10' }}"
                            >
                                {{ $optionLabel }}
                                @if ($departmentFilter === (string) $key)
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M5 10l3 3 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Position options --}}
                <div x-show="activeFilter === 'position'">
                    <p class="px-1 text-[11px] uppercase tracking-[0.35em] text-slate-400 dark:text:white/40">POSITION</p>
                    <div class="mt-1 max-h-60 space-y-1 overflow-y-auto">
                        @foreach ($positionOptions as $key => $optionLabel)
                            <button
                                type="button"
                                @click.prevent="open = false"
                                wire:click="setPositionFilter('{{ $key }}')"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left {{ $positionFilter === (string) $key ? 'bg-slate-900 text-white dark:bg:white dark:text-slate-900 font-semibold' : 'text-slate-600 hover:bg-slate-100 dark:text:white/80 dark:hover:bg:white/10' }}"
                            >
                                {{ $optionLabel }}
                                @if ($positionFilter === (string) $key)
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M5 10l3 3 7-7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between px-1 text-xs text-slate-500 dark:text-white/60">
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-medium text-slate-600 hover:bg-slate-100 dark:text-white/70 dark:hover:bg-white/10"
                    >
                        @svg('heroicon-m-arrow-path', 'h-3 w-3')
                        <span>Reset filters</span>
                    </button>

                    <span class="text-[11px] uppercase tracking-[0.2em]">
                        {{ $totalActiveFilters }} active
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
