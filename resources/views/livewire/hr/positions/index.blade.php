<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card label="Total Positions" :value="number_format($stats['total'])" description="Job roles" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-briefcase', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Managers" :value="number_format($stats['peopleManagers'])" description="People manager roles" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-user-group', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="ICs" :value="number_format($stats['individualContributors'])" description="Individual contributors" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-user', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Positions Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-end gap-3">
                {{-- Selection Summary --}}
                <x-table.selection-summary
                    :count="count($selectedPositions)"
                    :total="$positions->total()"
                    description="positions selected"
                    select-all-action="selectAllPositions"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    delete-action="deleteSelected"
                />

                {{-- Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search positions..."
                        class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                {{-- Dynamic Filters --}}
                <x-table.dynamic-filters :filters="[
                    'branch' => [
                        'label' => 'Branch',
                        'options' => array_merge(['all' => 'All branches'], $branches->pluck('name', 'id')->toArray()),
                        'selected' => $branchFilter,
                        'default' => 'all',
                        'onSelect' => 'setBranchFilter',
                    ],
                    'department' => [
                        'label' => 'Department',
                        'options' => array_merge(['all' => 'All departments'], $departments->pluck('name', 'id')->toArray()),
                        'selected' => $departmentFilter,
                        'default' => 'all',
                        'onSelect' => 'setDepartmentFilter',
                    ],
                ]" />

                <x-table.export-dropdown aria-label="Export positions" />

                {{-- Add Position --}}
                <a href="{{ route('hr.positions.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Add Position</span>
                </a>
            </div>
        </div>

        @if ($positions->count() > 0)
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
                                <button wire:click="setSort('title')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    POSITION
                                    @if ($sortField === 'title')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">DEPARTMENT</th>
                            <th class="px-5 py-3">BRANCH</th>
                            <th class="px-5 py-3">LEVEL</th>
                            <th class="px-5 py-3">MANAGER</th>
                            <th class="px-5 py-3">
                                <button wire:click="setSort('employees_count')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    EMPLOYEES
                                    @if ($sortField === 'employees_count')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($positions as $position)
                            @php $isSelected = in_array($position->id, $selectedPositions); @endphp
                            <tr
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}"
                                onclick="window.location='{{ route('hr.positions.edit', $position) }}'"
                            >
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop onclick="event.stopPropagation()">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedPositions" value="{{ $position->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $position->title }}</p>
                                    <p class="text-xs text-slate-500 dark:text-white/50">{{ $position->code }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $position->department?->name ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $position->branch?->name ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $position->level ?: '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $position->is_people_manager ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60' }}">
                                        {{ $position->is_people_manager ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $position->employees_count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-table.pagination :paginator="$positions" :per-page-options="[10, 25, 50, 100]" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-briefcase', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No positions found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $branchFilter !== 'all' || $departmentFilter !== 'all')
                        Try adjusting your search or filters
                    @else
                        Get started by adding your first position
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
