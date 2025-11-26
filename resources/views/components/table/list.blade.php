@props([
    'employees',
    'columnVisibility' => [],
    'sortField' => 'full_name',
    'sortDirection' => 'asc',
    'selectPage' => false,
    'selectedEmployees' => [],
])

<div class="overflow-x-auto px-5">
<table class="min-w-full text-sm">
    <thead>
        <tr class="text-left text-slate-500 dark:text-white/70 text-xs uppercase tracking-[0.3em] bg-slate-100 dark:bg-white/5">
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
                        'border-slate-400 bg-slate-900 text-white dark:border-white/40 dark:bg-white dark:text-slate-900' => $selectPage,
                        'border-slate-300 bg-white dark:border-white/40 dark:bg-white/5 text-transparent' => ! $selectPage,
                    ])>
                        @svg('heroicon-s-check', 'h-3 w-3')
                    </span>
                </label>
            </th>
            @if ($columnVisibility['employee'])
                <th class="px-2 py-4 cursor-pointer font-semibold text-slate-700 dark:text-white group" wire:click="setSort('full_name')">
                    <span class="inline-flex items-center gap-2">
                        Employee
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'full_name' ? 'opacity-100 text-slate-700 dark:text-white' : 'opacity-0 text-slate-400 dark:text-white/60 group-hover:opacity-60' }} {{ $sortField === 'full_name' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
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
                <th class="px-5 py-4 cursor-pointer font-semibold text-slate-600 dark:text-white/80 group" wire:click="setSort('department')">
                    <span class="inline-flex items-center gap-2">
                        Department
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'department' ? 'opacity-100 text-slate-700 dark:text-white' : 'opacity-0 text-slate-400 dark:text-white/60 group-hover:opacity-60' }} {{ $sortField === 'department' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
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
                <th class="px-5 py-4 cursor-pointer font-semibold text-slate-600 dark:text-white/80 group" wire:click="setSort('position')">
                    <span class="inline-flex items-center gap-2">
                        Position
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'position' ? 'opacity-100 text-slate-700 dark:text-white' : 'opacity-0 text-slate-400 dark:text-white/60 group-hover:opacity-60' }} {{ $sortField === 'position' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
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
                <th class="px-5 py-4 cursor-pointer font-semibold text-slate-700 dark:text-white group" wire:click="setSort('start_date')">
                    <span class="inline-flex items-center gap-2">
                        Start
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'start_date' ? 'opacity-100 text-slate-700 dark:text-white' : 'opacity-0 text-slate-400 dark:text-white/60 group-hover:opacity-60' }} {{ $sortField === 'start_date' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
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
                <th class="px-5 py-4 cursor-pointer font-semibold text-slate-700 dark:text-white group" wire:click="setSort('status')">
                    <span class="inline-flex items-center gap-2">
                        Status
                        <svg
                            class="h-3 w-3 transition {{ $sortField === 'status' ? 'opacity-100 text-slate-700 dark:text-white' : 'opacity-0 text-slate-400 dark:text-white/60 group-hover:opacity-60' }} {{ $sortField === 'status' && $sortDirection === 'asc' ? '' : 'rotate-180' }}"
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
            <th class="px-2 py-4 w-5">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
        @foreach ($employees as $employee)
            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
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
                            'border-slate-400 bg-slate-900 text-white dark:border-white/40 dark:bg-white dark:text-slate-900' => $rowSelected,
                            'border-slate-300 bg-white dark:border-white/40 dark:bg-white/5 text-transparent' => ! $rowSelected,
                        ])>
                            @svg('heroicon-s-check', 'h-3 w-3')
                        </span>
                    </label>
                </td>
                @if ($columnVisibility['employee'])
                    <td class="px-2 py-4">
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $employee->full_name }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $employee->code }} · {{ $employee->email }}</p>
                    </td>
                @endif
                @if ($columnVisibility['department'])
                    <td class="px-5 py-4 text-slate-600 dark:text-white/80">{{ $employee->department?->name ?? '—' }}</td>
                @endif
                @if ($columnVisibility['position'])
                    <td class="px-5 py-4 text-slate-600 dark:text-white/80">{{ $employee->position?->title ?? '—' }}</td>
                @endif
                @if ($columnVisibility['dates'])
                    <td class="px-5 py-4 text-slate-500 dark:text-white/60">{{ optional($employee->start_date)->format('M d, Y') }}</td>
                @endif
                @if ($columnVisibility['status'])
                    <td class="px-5 py-4">
                        @php
                            $statusMap = [
                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-300/15 dark:text-emerald-300',
                                'on_leave' => 'bg-amber-100 text-amber-700 dark:bg-amber-300/15 dark:text-amber-200',
                                'probation' => 'bg-sky-100 text-sky-700 dark:bg-sky-300/15 dark:text-sky-200',
                            ];
                        @endphp
                        <span class="rounded-full px-3 py-1 text-xs {{ $statusMap[$employee->status] ?? 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/70' }}">
                            {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                        </span>
                    </td>
                @endif
                <td class="px-2 py-4 w-10">
                    <div class="relative" data-dropdown>
                        <button
                            type="button"
                            data-dropdown-trigger
                            class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:text-white/50 dark:hover:bg-white/10 dark:hover:text-white"
                            aria-label="Row actions"
                        >
                            @svg('heroicon-o-ellipsis-vertical', 'h-4 w-4')
                        </button>
                        <div
                            data-dropdown-panel
                            class="absolute right-0 z-40 mt-2 hidden min-w-[10rem] rounded-xl border border-slate-200 bg-white p-1 text-xs text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900/95 dark:text-white"
                        >
                            <a
                                href="{{ route('hr.employees.edit', $employee) }}"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-white/10"
                            >
                                @svg('heroicon-o-pencil-square', 'h-4 w-4')
                                <span>Edit</span>
                            </a>
                            <button
                                type="button"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10"
                            >
                                @svg('heroicon-o-trash', 'h-4 w-4')
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
