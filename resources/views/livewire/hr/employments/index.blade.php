<div class="space-y-10">
    <x-module.heading
        tagline="HR · People"
        title="Employment Records"
        description="Track employment classes, contract stages, and recent lifecycle updates across the organization."
    >
        <x-slot:actions>
            <x-ui.button as="a" href="{{ route('hr.employments.create') }}">Add employment</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Permanent</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['permanent'] }}</p>
            <p class="text-xs text-white/60">Active permanent contracts</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-amber-300/80">Probation</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['probation'] }}</p>
            <p class="text-xs text-white/60">Under evaluation</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-sky-300/80">Contract</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['contract'] }}</p>
            <p class="text-xs text-white/60">Fixed-term & part-time</p>
        </div>
    </section>

    <section class="rounded-[28px] border border-white/10 bg-white/5 overflow-hidden">
        <div class="px-6 pt-4 pb-4 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3 ml-auto">
                    <x-table.search wire:model.debounce.300ms="search" placeholder="Search code or name" />
                    <x-table.filter-dropdown
                        label="Status"
                        :options="['all' => 'All', 'active' => 'Active', 'on_leave' => 'On leave', 'probation' => 'Probation']"
                        :selected="$statusFilter"
                        on-select="setStatusFilter"
                    />
                    <x-table.column-dropdown :column-visibility="$columnVisibility" reset-action="resetColumns" />
                </div>
            </div>
        </div>

        <div class="-mx-6 -mb-6 overflow-hidden border-t border-white/10 bg-slate-900/40 pb-6">
            <div class="overflow-x-auto px-6 pb-4">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-white/70 text-xs uppercase tracking-[0.3em] bg-white/5">
                            <th class="px-4 py-4 w-16 text-center">
                                <label class="inline-flex items-center justify-center">
                                    <input
                                        type="checkbox"
                                        wire:click="toggleSelectPage"
                                        class="sr-only"
                                        @checked($selectPage)
                                        aria-label="Select page"
                                    >
                                    <span @class([
                                        'flex h-5 w-5 items-center justify-center rounded border transition',
                                        'border-white/40 bg-white text-slate-900' => $selectPage,
                                        'border-white/40 bg-white/5 text-transparent' => ! $selectPage,
                                    ])>
                                        @svg('heroicon-s-check', 'h-3 w-3')
                                    </span>
                                </label>
                            </th>
                            @if ($columnVisibility['employee'])
                                <th class="px-4 py-4 cursor-pointer" wire:click="setSort('full_name')">
                                    <span class="inline-flex items-center gap-2 font-semibold text-white">
                                        Employee
                                        <svg class="h-3 w-3 {{ $sortField === 'full_name' ? 'opacity-100 text-white' : 'opacity-0 text-white/60' }} {{ $sortField === 'full_name' && $sortDirection === 'asc' ? '' : 'rotate-180' }}" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 1.5L5 4.5L9 1.5" />
                                        </svg>
                                    </span>
                                </th>
                            @endif
                            @if ($columnVisibility['branch'])
                                <th class="px-4 py-4">Branch</th>
                            @endif
                            @if ($columnVisibility['department'])
                                <th class="px-4 py-4">Department</th>
                            @endif
                            @if ($columnVisibility['position'])
                                <th class="px-4 py-4">Position</th>
                            @endif
                            @if ($columnVisibility['class'])
                                <th class="px-4 py-4">Class</th>
                            @endif
                            @if ($columnVisibility['start'])
                                <th class="px-4 py-4 cursor-pointer" wire:click="setSort('start_date')">
                                    <span class="inline-flex items-center gap-2 font-semibold text-white/80">
                                        Start
                                        <svg class="h-3 w-3 {{ $sortField === 'start_date' ? 'opacity-100 text-white' : 'opacity-0 text-white/60' }} {{ $sortField === 'start_date' && $sortDirection === 'asc' ? '' : 'rotate-180' }}" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 1.5L5 4.5L9 1.5" />
                                        </svg>
                                    </span>
                                </th>
                            @endif
                            @if ($columnVisibility['status'])
                                <th class="px-4 py-4">Status</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($employments as $employment)
                            <tr class="hover:bg-white/5">
                                <td class="px-4 py-4 w-16 text-center">
                                    @php $rowSelected = in_array((string) $employment->id, $selectedEmployments, true); @endphp
                                    <label class="inline-flex items-center justify-center">
                                        <input
                                            type="checkbox"
                                            wire:model.live="selectedEmployments"
                                            value="{{ $employment->id }}"
                                            class="sr-only"
                                            @checked($rowSelected)
                                            aria-label="Select {{ $employment->full_name }}"
                                        >
                                        <span @class([
                                            'flex h-5 w-5 items-center justify-center rounded border transition',
                                            'border-white/40 bg-white text-slate-900' => $rowSelected,
                                            'border-white/40 bg-white/5 text-transparent' => ! $rowSelected,
                                        ])>
                                            @svg('heroicon-s-check', 'h-3 w-3')
                                        </span>
                                    </label>
                                </td>
                                @if ($columnVisibility['employee'])
                                    <td class="px-4 py-4">
                                        <p class="font-semibold text-white">{{ $employment->full_name }}</p>
                                        <p class="text-xs text-white/50">{{ $employment->code }}</p>
                                    </td>
                                @endif
                                @if ($columnVisibility['branch'])
                                    <td class="px-4 py-4 text-white/70">{{ $employment->branch?->name ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['department'])
                                    <td class="px-4 py-4 text-white/70">{{ $employment->department?->name ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['position'])
                                    <td class="px-4 py-4 text-white/70">{{ $employment->position?->title ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['class'])
                                    <td class="px-4 py-4">
                                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-white/70">{{ str($employment->employment_class ?: 'N/A')->headline() }}</span>
                                    </td>
                                @endif
                                @if ($columnVisibility['start'])
                                    <td class="px-4 py-4 text-white/70">{{ optional($employment->start_date)->format('M d, Y') ?? '—' }}</td>
                                @endif
                                @if ($columnVisibility['status'])
                                    <td class="px-4 py-4">
                                        @php
                                            $statusMap = [
                                                'active' => 'bg-emerald-300/15 text-emerald-200',
                                                'on_leave' => 'bg-amber-300/15 text-amber-200',
                                                'probation' => 'bg-sky-300/15 text-sky-200',
                                            ];
                                        @endphp
                                        <span class="rounded-full px-3 py-1 text-xs {{ $statusMap[$employment->status] ?? 'bg-white/10 text-white/60' }}">
                                            {{ str($employment->status)->headline() }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ collect($columnVisibility)->filter()->count() + 1 }}" class="px-4 py-10 text-center text-sm text-white/60">No employment records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6">
                <x-table.pagination :paginator="$employments" />
            </div>
        </div>
    </section>
</div>
