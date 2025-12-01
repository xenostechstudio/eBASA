<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Employees" :value="number_format($stats['total'])" description="Records in directory" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-user-group', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Active" :value="number_format($stats['active'])" description="In-good-standing" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-badge', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="On Leave" :value="number_format($stats['on_leave'])" description="Currently offline" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-calendar-days', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Probation" :value="number_format($stats['probation'])" description="Under evaluation" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-clock', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Employees Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-end gap-3">
                {{-- Selection Summary --}}
                <x-table.selection-summary
                    :count="count($selectedEmployees)"
                    :total="$employees->total()"
                    description="employees selected"
                    select-all-action="selectAllEmployees"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    delete-action="deleteSelected"
                />

                {{-- Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search employees..."
                        class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

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
                    'department' => [
                        'label' => 'Department',
                        'options' => array_merge(
                            ['all' => 'All departments'],
                            $departments->pluck('name', 'id')->toArray()
                        ),
                        'selected' => $departmentFilter,
                        'default' => 'all',
                        'onSelect' => 'setDepartmentFilter',
                    ],
                    'position' => [
                        'label' => 'Position',
                        'options' => array_merge(
                            ['all' => 'All positions'],
                            $positions->pluck('title', 'id')->toArray()
                        ),
                        'selected' => $positionFilter,
                        'default' => 'all',
                        'onSelect' => 'setPositionFilter',
                    ],
                ]" />

                <x-table.export-dropdown aria-label="Export employees" />

                {{-- Add Employee --}}
                <a href="{{ route('hr.employees.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Add Employee</span>
                </a>
            </div>
        </div>

        @php
            $hasActiveFilters = $statusFilter !== 'all' || $departmentFilter !== 'all' || $positionFilter !== 'all';
        @endphp

        @if ($hasActiveFilters)
            <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 text-xs text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-white/70">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($statusFilter !== 'all')
                        <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                            <span>Status: {{ ucfirst(str_replace('_', ' ', $statusFilter)) }}</span>
                            <button type="button" wire:click="setStatusFilter('all')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20" aria-label="Reset">
                                @svg('heroicon-o-x-mark', 'h-3 w-3')
                            </button>
                        </div>
                    @endif

                    @if ($departmentFilter !== 'all')
                        @php $dept = $departments->firstWhere('id', (int) $departmentFilter); @endphp
                        @if ($dept)
                            <div class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1 text-[11px] font-medium text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                <span>Department: {{ $dept->name }}</span>
                                <button type="button" wire:click="setDepartmentFilter('all')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20" aria-label="Reset">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </div>
                        @endif
                    @endif

                    @if ($positionFilter !== 'all')
                        @php $pos = $positions->firstWhere('id', (int) $positionFilter); @endphp
                        @if ($pos)
                            <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-medium text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                <span>Position: {{ $pos->title }}</span>
                                <button type="button" wire:click="setPositionFilter('all')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20" aria-label="Reset">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endif

        @if ($employees->count() > 0)
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
                            <th class="px-5 py-3">
                                <button wire:click="setSort('department')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    DEPARTMENT
                                    @if ($sortField === 'department')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">
                                <button wire:click="setSort('position')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    POSITION
                                    @if ($sortField === 'position')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">STATUS</th>
                            <th class="px-5 py-3">
                                <button wire:click="setSort('start_date')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    START DATE
                                    @if ($sortField === 'start_date')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($employees as $employee)
                            @php $isSelected = in_array($employee->id, $selectedEmployees); @endphp
                            <tr
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}"
                                onclick="window.location='{{ route('hr.employees.edit', $employee) }}'"
                            >
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedEmployees" value="{{ $employee->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-sm font-medium text-slate-600 dark:bg-white/10 dark:text-white/80">
                                            {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-white">{{ $employee->full_name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-white/50">{{ $employee->code }} · {{ $employee->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $employee->department?->name ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $employee->position?->title ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                            'on_leave' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                            'probation' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                            'terminated' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$employee->status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $employee->start_date?->format(config('basa.date_format', 'd M Y')) ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('hr.employees.edit', $employee) }}"
                                            wire:click.stop
                                            onclick="event.stopPropagation()"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                            title="Edit">
                                            @svg('heroicon-o-pencil', 'h-4 w-4')
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-table.pagination :paginator="$employees" :per-page-options="$perPageOptions" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-user-group', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No employees found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $hasActiveFilters)
                        Try adjusting your search or filters
                    @else
                        Get started by adding your first employee
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
