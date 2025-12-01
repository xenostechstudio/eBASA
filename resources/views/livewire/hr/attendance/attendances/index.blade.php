<div>
    {{-- Flash Message --}}
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="Daily Attendance"
            description="Track and manage employee attendance records."
        >
            <x-slot:actions>
                <a
                    href="{{ route('hr.attendances.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Record Attendance</span>
                </a>
            </x-slot:actions>
        </x-form.section-header>

        {{-- Stats Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-400/20 dark:text-emerald-300">
                        @svg('heroicon-o-check-circle', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $presentCount }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Present</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-400/20 dark:text-amber-300">
                        @svg('heroicon-o-clock', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $lateCount }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Late</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-400/20 dark:text-red-300">
                        @svg('heroicon-o-x-circle', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $absentCount }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Absent</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-400/20 dark:text-blue-300">
                        @svg('heroicon-o-calendar-days', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $leaveCount }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">On Leave</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Search & Filters --}}
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative w-full md:max-w-xs">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search employees..."
                                class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-white/40"
                            >
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/40">
                                @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                            </span>
                        </div>
                        <input
                            type="date"
                            wire:model.live="filterDate"
                            class="h-10 rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                        >
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Filters --}}
                        <x-table.dynamic-filters :active-count="$activeFiltersCount">
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-white/60">Status</label>
                                    <select wire:model.live="filterStatus" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm dark:border-white/10 dark:bg-slate-900 dark:text-white">
                                        <option value="">All Status</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}">{{ str($status)->headline() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-white/60">Shift</label>
                                    <select wire:model.live="filterShift" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm dark:border-white/10 dark:bg-slate-900 dark:text-white">
                                        <option value="">All Shifts</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-white/60">Branch</label>
                                    <select wire:model.live="filterBranch" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm dark:border-white/10 dark:bg-slate-900 dark:text-white">
                                        <option value="">All Branches</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($activeFiltersCount > 0)
                                    <button type="button" wire:click="resetFilters" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:text-white/60 dark:hover:bg-white/5">
                                        Clear Filters
                                    </button>
                                @endif
                            </div>
                        </x-table.dynamic-filters>

                        {{-- Export --}}
                        <x-table.export-dropdown export-pdf="exportPdf" export-excel="exportExcel" />
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Employee</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Shift</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Clock In</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Clock Out</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Worked</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($attendances as $attendance)
                            <tr
                                wire:key="attendance-{{ $attendance->id }}"
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('hr.attendances.edit', $attendance) }}'"
                            >
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $attendance->employee?->full_name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $attendance->employee?->code }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $attendance->shift?->name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($attendance->clock_in)
                                        <span class="font-mono text-slate-900 dark:text-white">{{ $attendance->clock_in->format('H:i') }}</span>
                                        @if ($attendance->late_minutes > 0)
                                            <span class="ml-1 text-xs text-amber-600 dark:text-amber-400">+{{ $attendance->late_minutes }}m</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 dark:text-white/40">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($attendance->clock_out)
                                        <span class="font-mono text-slate-900 dark:text-white">{{ $attendance->clock_out->format('H:i') }}</span>
                                        @if ($attendance->early_leave_minutes > 0)
                                            <span class="ml-1 text-xs text-amber-600 dark:text-amber-400">-{{ $attendance->early_leave_minutes }}m</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 dark:text-white/40">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">
                                    @if ($attendance->worked_minutes > 0)
                                        {{ floor($attendance->worked_minutes / 60) }}h {{ $attendance->worked_minutes % 60 }}m
                                        @if ($attendance->overtime_minutes > 0)
                                            <span class="ml-1 text-xs text-emerald-600 dark:text-emerald-400">+{{ floor($attendance->overtime_minutes / 60) }}h OT</span>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'present' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300',
                                            'absent' => 'bg-red-100 text-red-700 dark:bg-red-400/20 dark:text-red-300',
                                            'late' => 'bg-amber-100 text-amber-700 dark:bg-amber-400/20 dark:text-amber-300',
                                            'half_day' => 'bg-orange-100 text-orange-700 dark:bg-orange-400/20 dark:text-orange-300',
                                            'leave' => 'bg-blue-100 text-blue-700 dark:bg-blue-400/20 dark:text-blue-300',
                                            'holiday' => 'bg-purple-100 text-purple-700 dark:bg-purple-400/20 dark:text-purple-300',
                                            'weekend' => 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$attendance->status] ?? $statusColors['present'] }}">
                                        {{ str($attendance->status)->headline() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-white/50">
                                    No attendance records found for this date.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($attendances->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
