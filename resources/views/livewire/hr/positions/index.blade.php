<div class="space-y-10">
    <x-module.heading
        tagline="HR · People"
        title="Positions"
        description="Track roles, reporting structures, and people manager responsibilities."
    >
        <x-slot:actions>
            <x-ui.button as="a" href="{{ route('hr.positions.create') }}">Add position</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Total</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-white/60">Positions</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-emerald-300/80">Managers</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['peopleManagers'] }}</p>
            <p class="text-xs text-white/60">People manager roles</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-sky-300/80">ICs</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['individualContributors'] }}</p>
            <p class="text-xs text-white/60">Individual contributors</p>
        </div>
    </section>

    <section class="rounded-[28px] border border-white/10 bg-white/5 overflow-hidden">
        <div class="px-6 pt-4 pb-4 space-y-4">
            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-table.search wire:model.debounce.300ms="search" placeholder="Search title or code" />
                <x-table.filter-dropdown
                    label="Branch"
                    :options="array_merge(['all' => 'All branches'], $branches->pluck('name', 'id')->toArray())"
                    :selected="$branchFilter"
                    on-select="setBranchFilter"
                />
                <x-table.filter-dropdown
                    label="Department"
                    :options="array_merge(['all' => 'All departments'], $departments->pluck('name', 'id')->toArray())"
                    :selected="$departmentFilter"
                    on-select="setDepartmentFilter"
                />
                <x-table.column-dropdown :column-visibility="$columnVisibility" reset-action="resetColumns" />
            </div>
        </div>

        <div class="-mx-6 -mb-6 overflow-hidden border-t border-white/10 bg-slate-900/40 pb-6">
            <div class="overflow-x-auto px-6 pb-4">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-white/70 text-xs uppercase tracking-[0.3em] bg-white/5">
                            <th class="px-4 py-4 w-16 text-center">
                                <label class="inline-flex items-center justify-center">
                                    <input type="checkbox" wire:click="toggleSelectPage" class="sr-only" @checked($selectPage)>
                                    <span @class([
                                        'flex h-5 w-5 items-center justify-center rounded border transition',
                                        'border-white/40 bg-white text-slate-900' => $selectPage,
                                        'border-white/40 bg-white/5 text-transparent' => ! $selectPage,
                                    ])>
                                        @svg('heroicon-s-check', 'h-3 w-3')
                                    </span>
                                </label>
                            </th>
                            @if ($columnVisibility['position'])
                                <th class="px-4 py-4 cursor-pointer" wire:click="setSort('title')">
                                    <span class="inline-flex items-center gap-2 font-semibold text-white">
                                        Position
                                        <svg class="h-3 w-3 {{ $sortField === 'title' ? 'opacity-100 text-white' : 'opacity-0 text-white/60' }} {{ $sortField === 'title' && $sortDirection === 'asc' ? '' : 'rotate-180' }}" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 1.5L5 4.5L9 1.5" />
                                        </svg>
                                    </span>
                                </th>
                            @endif
                            @if ($columnVisibility['department'])
                                <th class="px-4 py-4">Department</th>
                            @endif
                            @if ($columnVisibility['branch'])
                                <th class="px-4 py-4">Branch</th>
                            @endif
                            @if ($columnVisibility['level'])
                                <th class="px-4 py-4">Level</th>
                            @endif
                            @if ($columnVisibility['job_family'])
                                <th class="px-4 py-4">Job family</th>
                            @endif
                            @if ($columnVisibility['people_manager'])
                                <th class="px-4 py-4">People manager</th>
                            @endif
                            @if ($columnVisibility['employees'])
                                <th class="px-4 py-4 cursor-pointer" wire:click="setSort('employees_count')">
                                    <span class="inline-flex items-center gap-2 font-semibold text-white/80">
                                        Employees
                                        <svg class="h-3 w-3 {{ $sortField === 'employees_count' ? 'opacity-100 text-white' : 'opacity-0 text-white/60' }} {{ $sortField === 'employees_count' && $sortDirection === 'asc' ? '' : 'rotate-180' }}" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 1.5L5 4.5L9 1.5" />
                                        </svg>
                                    </span>
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($positions as $position)
                            <tr class="hover:bg-white/5">
                                <td class="px-4 py-4 w-16 text-center">
                                    @php $rowSelected = in_array((string) $position->id, $selectedPositions, true); @endphp
                                    <label class="inline-flex items-center justify-center">
                                        <input type="checkbox" wire:model.live="selectedPositions" value="{{ $position->id }}" class="sr-only" @checked($rowSelected)>
                                        <span @class([
                                            'flex h-5 w-5 items-center justify-center rounded border transition',
                                            'border-white/40 bg-white text-slate-900' => $rowSelected,
                                            'border-white/40 bg-white/5 text-transparent' => ! $rowSelected,
                                        ])>
                                            @svg('heroicon-s-check', 'h-3 w-3')
                                        </span>
                                    </label>
                                </td>
                                @if ($columnVisibility['position'])
                                    <td class="px-4 py-4">
                                        <p class="font-semibold text-white">{{ $position->title }}</p>
                                        <p class="text-xs text-white/50">{{ $position->code }}</p>
                                    </td>
                                @endif
                                @if ($columnVisibility['department'])
                                    <td class="px-4 py-4 text-white/70">{{ $position->department?->name ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['branch'])
                                    <td class="px-4 py-4 text-white/70">{{ $position->branch?->name ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['level'])
                                    <td class="px-4 py-4 text-white/70">{{ $position->level ?: '—' }}</td>
                                @endif
                                @if ($columnVisibility['job_family'])
                                    <td class="px-4 py-4 text-white/70">{{ $position->job_family ?: '—' }}</td>
                                @endif
                                @if ($columnVisibility['people_manager'])
                                    <td class="px-4 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs {{ $position->is_people_manager ? 'bg-emerald-300/15 text-emerald-200' : 'bg-white/10 text-white/60' }}">
                                            {{ $position->is_people_manager ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                @endif
                                @if ($columnVisibility['employees'])
                                    <td class="px-4 py-4 text-white/70">{{ $position->employees_count }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ collect($columnVisibility)->filter()->count() + 1 }}" class="px-4 py-10 text-center text-sm text-white/60">No positions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6">
                <x-table.pagination :paginator="$positions" />
            </div>
        </div>
    </section>
</div>
