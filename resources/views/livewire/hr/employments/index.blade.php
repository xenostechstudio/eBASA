<div>
    @if (session()->has('status'))
        <x-alert type="success">{{ session('status') }}</x-alert>
    @endif

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat.card label="Total Records" :value="number_format($employments->total())" description="Employment records" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-document-text', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Permanent" :value="number_format($stats['permanent'])" description="Active permanent" tone="success">
                <x-slot:icon>@svg('heroicon-o-check-badge', 'h-5 w-5 text-emerald-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Probation" :value="number_format($stats['probation'])" description="Under evaluation" tone="warning">
                <x-slot:icon>@svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Contract" :value="number_format($stats['contract'])" description="Fixed-term" tone="info">
                <x-slot:icon>@svg('heroicon-o-clipboard-document-list', 'h-5 w-5 text-sky-500')</x-slot:icon>
            </x-stat.card>
        </div>

        {{-- Table Card --}}
        <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Toolbar --}}
            <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    {{-- Left: Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search employees..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                    </div>

                    {{-- Right: Selection, Filters, Export, Add --}}
                    <div class="flex items-center gap-2">
                        {{-- Selection Summary --}}
                        <x-table.selection-summary
                            :count="count($selectedEmployments)"
                            :total="$employments->total()"
                            description="records selected"
                            select-all-action="selectAllEmployments"
                            select-page-action="selectPage"
                            deselect-action="deselectAll"
                            delete-action="deleteSelected"
                        />

                        {{-- Dynamic Filters --}}
                        <x-table.dynamic-filters :filters="[
                            'status' => [
                                'label' => 'Status',
                                'options' => [
                                    'all' => 'All status',
                                    'active' => 'Active',
                                    'on_leave' => 'On Leave',
                                    'probation' => 'Probation',
                                    'terminated' => 'Terminated',
                                ],
                                'selected' => $statusFilter,
                                'default' => 'all',
                                'onSelect' => 'setStatusFilter',
                            ],
                        ]" />

                        <x-table.export-dropdown aria-label="Export employments" />

                        {{-- Add Employment --}}
                        <a href="{{ route('hr.employments.create') }}"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                            @svg('heroicon-o-plus', 'h-4 w-4')
                            <span>Add Employment</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Active Filters Pills --}}
            @php $hasActiveFilters = $statusFilter !== 'all'; @endphp
            @if ($hasActiveFilters)
                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 dark:border-white/10 dark:bg-white/5">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($statusFilter !== 'all')
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                <span>Status: {{ str($statusFilter)->headline() }}</span>
                                <button type="button" wire:click="setStatusFilter('all')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Table --}}
            @if ($employments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                                <th class="w-12 px-5 py-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:click="toggleSelectPage" @checked($selectPage)
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </th>
                                <th class="px-5 py-3">
                                    <button wire:click="setSort('full_name')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                        EMPLOYEE
                                        @if ($sortField === 'full_name')
                                            @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                        @endif
                                    </button>
                                </th>
                                <th class="px-5 py-3">DEPARTMENT</th>
                                <th class="px-5 py-3">POSITION</th>
                                <th class="px-5 py-3">
                                    <div class="flex items-center gap-1">
                                        CLASS
                                        <span class="text-slate-400" title="Employment classification (permanent, probation, contract)">@svg('heroicon-o-information-circle', 'h-3.5 w-3.5')</span>
                                    </div>
                                </th>
                                <th class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        BASE SALARY
                                        <span class="text-slate-400" title="Monthly base salary before allowances">@svg('heroicon-o-information-circle', 'h-3.5 w-3.5')</span>
                                    </div>
                                </th>
                                <th class="px-5 py-3">
                                    <button wire:click="setSort('start_date')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                        START DATE
                                        @if ($sortField === 'start_date')
                                            @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                        @endif
                                    </button>
                                </th>
                                <th class="px-5 py-3">STATUS</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                            @php
                                $statusColors = [
                                    'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                    'on_leave' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                    'probation' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                    'terminated' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                                ];
                            @endphp
                            @foreach ($employments as $employment)
                                @php $isSelected = in_array($employment->id, $selectedEmployments); @endphp
                                <tr
                                    wire:key="emp-{{ $employment->id }}"
                                    class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}"
                                    onclick="window.location='{{ route('hr.employments.edit', $employment) }}'"
                                >
                                    <td class="whitespace-nowrap px-5 py-4" wire:click.stop onclick="event.stopPropagation()">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model.live="selectedEmployments" value="{{ $employment->id }}"
                                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                        </label>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $employment->full_name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $employment->code }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        {{ $employment->department?->name ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        {{ $employment->position?->title ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @php
                                            $classColors = [
                                                'permanent' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                                'probation' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                                'contract' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $classColors[$employment->employment_class] ?? 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/70' }}">
                                            {{ str($employment->employment_class ?: 'N/A')->headline() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        @if ($employment->base_salary)
                                            <p class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($employment->base_salary, 0, ',', '.') }}</p>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-white/40">Not set</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                        {{ optional($employment->start_date)->format('d M Y') ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$employment->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ str($employment->status)->headline() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-400')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-table.pagination :paginator="$employments" :per-page-options="[10, 25, 50, 100]" />
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    @svg('heroicon-o-document-text', 'h-12 w-12 text-slate-300 dark:text-white/20')
                    <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No employment records found</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                        @if ($search || $hasActiveFilters)
                            Try adjusting your search or filters
                        @else
                            Get started by adding your first employment record
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
