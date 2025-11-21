@props([
    'employees',
    'columnVisibility' => [],
    'sortField' => 'full_name',
    'sortDirection' => 'asc',
    'selectPage' => false,
    'selectedEmployees' => [],
])

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
                <th class="px-2 py-4 cursor-pointer font-semibold text-white group" wire:click="setSort('full_name')">
                    <span class="inline-flex items-center gap-2">
                        Employee
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'full_name' ? 'opacity-100 text-white' : 'opacity-0 text-white/60 group-hover:opacity-60' }} {{ $sortField === 'full_name' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                            viewBox="0 0 10 6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M1 1.5L5 4.5L9 1.5" />
                        </svg>
                    </span>
                </th>
            @endif
            @if ($columnVisibility['department'])
                <th class="px-5 py-4 cursor-pointer font-semibold text-white/80 group" wire:click="setSort('department')">
                    <span class="inline-flex items-center gap-2">
                        Department
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'department' ? 'opacity-100 text-white' : 'opacity-0 text-white/60 group-hover:opacity-60' }} {{ $sortField === 'department' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                            viewBox="0 0 10 6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M1 1.5L5 4.5L9 1.5" />
                        </svg>
                    </span>
                </th>
            @endif
            @if ($columnVisibility['position'])
                <th class="px-5 py-4 cursor-pointer font-semibold text-white/80 group" wire:click="setSort('position')">
                    <span class="inline-flex items-center gap-2">
                        Position
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'position' ? 'opacity-100 text-white' : 'opacity-0 text-white/60 group-hover:opacity-60' }} {{ $sortField === 'position' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                            viewBox="0 0 10 6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M1 1.5L5 4.5L9 1.5" />
                        </svg>
                    </span>
                </th>
            @endif
            @if ($columnVisibility['dates'])
                <th class="px-5 py-4 cursor-pointer font-semibold text-white group" wire:click="setSort('start_date')">
                    <span class="inline-flex items-center gap-2">
                        Start
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'start_date' ? 'opacity-100 text-white' : 'opacity-0 text-white/60 group-hover:opacity-60' }} {{ $sortField === 'start_date' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                            viewBox="0 0 10 6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M1 1.5L5 4.5L9 1.5" />
                        </svg>
                    </span>
                </th>
            @endif
            @if ($columnVisibility['status'])
                <th class="px-5 py-4 text-right cursor-pointer font-semibold text-white group" wire:click="setSort('status')">
                    <span class="inline-flex items-center gap-2 justify-end w-full">
                        Status
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'status' ? 'opacity-100 text-white' : 'opacity-0 text-white/60 group-hover:opacity-60' }} {{ $sortField === 'status' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                            viewBox="0 0 10 6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M1 1.5L5 4.5L9 1.5" />
                        </svg>
                    </span>
                </th>
            @endif
        </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
        @foreach ($employees as $employee)
            <tr class="hover:bg-white/5">
                <td class="px-6 py-4 w-16 text-center">
                    @php
                        $rowSelected = in_array((string) $employee->id, $selectedEmployees, true);
                    @endphp
                    <label class="inline-flex items-center justify-center">
                        <input
                            type="checkbox"
                            wire:model.live="selectedEmployees"
                            value="{{ $employee->id }}"
                            class="sr-only"
                            @checked($rowSelected)
                            aria-label="Select {{ $employee->full_name }}"
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
                    <td class="px-2 py-4">
                        <p class="font-semibold text-white">{{ $employee->full_name }}</p>
                        <p class="text-xs text-white/50">{{ $employee->code }} · {{ $employee->email }}</p>
                    </td>
                @endif
                @if ($columnVisibility['department'])
                    <td class="px-5 py-4 text-white/80">{{ $employee->department?->name ?? '—' }}</td>
                @endif
                @if ($columnVisibility['position'])
                    <td class="px-5 py-4 text-white/80">{{ $employee->position?->title ?? '—' }}</td>
                @endif
                @if ($columnVisibility['dates'])
                    <td class="px-5 py-4 text-white/60">{{ optional($employee->start_date)->format('M d, Y') }}</td>
                @endif
                @if ($columnVisibility['status'])
                    <td class="px-5 py-4 text-right">
                        @php
                            $statusMap = [
                                'active' => 'bg-emerald-300/15 text-emerald-300',
                                'on_leave' => 'bg-amber-300/15 text-amber-200',
                                'probation' => 'bg-sky-300/15 text-sky-200',
                            ];
                        @endphp
                        <span class="rounded-full px-3 py-1 text-xs {{ $statusMap[$employee->status] ?? 'bg-white/10 text-white/70' }}">
                            {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                        </span>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
</div>
