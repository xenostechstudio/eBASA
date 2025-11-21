<div class="space-y-10">
    <x-module.heading
        tagline="HR · People"
        title="Departments"
        description="Manage organizational units, reporting lines, and department leads."
    >
        <x-slot:actions>
            <x-ui.button as="a" href="{{ route('hr.departments.create') }}">Add department</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Total</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-white/60">Departments</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-emerald-300/80">With lead</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['withLead'] }}</p>
            <p class="text-xs text-white/60">Assigned leads</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-red-300/80">Unassigned</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['withoutLead'] }}</p>
            <p class="text-xs text-white/60">Need lead assignment</p>
        </div>
    </section>

    <section class="rounded-[28px] border border-white/10 bg-white/5 overflow-hidden">
        <div class="px-6 pt-4 pb-4 space-y-4">
            <div class="flex flex-wrap items-center justify-end gap-3">
                <x-table.search wire:model.debounce.300ms="search" placeholder="Search name or code" />
                <x-table.filter-dropdown
                    label="Branch"
                    :options="array_merge(['all' => 'All branches'], $branches->pluck('name', 'id')->toArray())"
                    :selected="$branchFilter"
                    on-select="setBranchFilter"
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
                            @if ($columnVisibility['department'])
                                <th class="px-4 py-4 cursor-pointer" wire:click="setSort('name')">
                                    <span class="inline-flex items-center gap-2 font-semibold text-white">
                                        Department
                                        <svg class="h-3 w-3 {{ $sortField === 'name' ? 'opacity-100 text-white' : 'opacity-0 text-white/60' }} {{ $sortField === 'name' && $sortDirection === 'asc' ? '' : 'rotate-180' }}" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 1.5L5 4.5L9 1.5" />
                                        </svg>
                                    </span>
                                </th>
                            @endif
                            @if ($columnVisibility['branch'])
                                <th class="px-4 py-4">Branch</th>
                            @endif
                            @if ($columnVisibility['parent'])
                                <th class="px-4 py-4">Parent</th>
                            @endif
                            @if ($columnVisibility['lead'])
                                <th class="px-4 py-4">Lead</th>
                            @endif
                            @if ($columnVisibility['positions'])
                                <th class="px-4 py-4 cursor-pointer" wire:click="setSort('positions_count')">
                                    <span class="inline-flex items-center gap-2 font-semibold text-white/80">
                                        Positions
                                        <svg class="h-3 w-3 {{ $sortField === 'positions_count' ? 'opacity-100 text-white' : 'opacity-0 text-white/60' }} {{ $sortField === 'positions_count' && $sortDirection === 'asc' ? '' : 'rotate-180' }}" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 1.5L5 4.5L9 1.5" />
                                        </svg>
                                    </span>
                                </th>
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
                        @forelse ($departments as $department)
                            <tr class="hover:bg-white/5">
                                <td class="px-4 py-4 w-16 text-center">
                                    @php $rowSelected = in_array((string) $department->id, $selectedDepartments, true); @endphp
                                    <label class="inline-flex items-center justify-center">
                                        <input type="checkbox" wire:model.live="selectedDepartments" value="{{ $department->id }}" class="sr-only" @checked($rowSelected)>
                                        <span @class([
                                            'flex h-5 w-5 items-center justify-center rounded border transition',
                                            'border-white/40 bg-white text-slate-900' => $rowSelected,
                                            'border-white/40 bg-white/5 text-transparent' => ! $rowSelected,
                                        ])>
                                            @svg('heroicon-s-check', 'h-3 w-3')
                                        </span>
                                    </label>
                                </td>
                                @if ($columnVisibility['department'])
                                    <td class="px-4 py-4">
                                        <p class="font-semibold text-white">{{ $department->name }}</p>
                                        <p class="text-xs text-white/50">{{ $department->code }}</p>
                                    </td>
                                @endif
                                @if ($columnVisibility['branch'])
                                    <td class="px-4 py-4 text-white/70">{{ $department->branch?->name ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['parent'])
                                    <td class="px-4 py-4 text-white/70">{{ $department->parent?->name ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['lead'])
                                    <td class="px-4 py-4">
                                        <div class="text-white">
                                            <p class="text-sm font-semibold">{{ $department->lead_name ?: '—' }}</p>
                                            <p class="text-xs text-white/60">{{ $department->lead_email ?: 'No email' }}</p>
                                        </div>
                                    </td>
                                @endif
                                @if ($columnVisibility['positions'])
                                    <td class="px-4 py-4 text-white/70">{{ $department->positions_count }}</td>
                                @endif
                                @if ($columnVisibility['employees'])
                                    <td class="px-4 py-4 text-white/70">{{ $department->employees_count }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ collect($columnVisibility)->filter()->count() + 1 }}" class="px-4 py-10 text-center text-sm text-white/60">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6">
                <x-table.pagination :paginator="$departments" />
            </div>
        </div>
    </section>
</div>
