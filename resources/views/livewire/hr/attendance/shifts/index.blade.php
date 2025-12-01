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
            title="Shifts"
            description="Manage work shifts and schedules."
        >
            <x-slot:actions>
                <a
                    href="{{ route('hr.shifts.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Add Shift</span>
                </a>
            </x-slot:actions>
        </x-form.section-header>

        {{-- Table Card --}}
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Search & Filters --}}
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full md:max-w-xs">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search shifts..."
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-white/40"
                        >
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/40">
                            @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Filters --}}
                        <x-table.dynamic-filters :active-count="$activeFiltersCount">
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-white/60">Status</label>
                                    <select wire:model.live="filterStatus" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm dark:border-white/10 dark:bg-slate-900 dark:text-white">
                                        <option value="">All Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
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
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Code</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Name</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Time</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Break</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Working Hours</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($shifts as $shift)
                            <tr
                                wire:key="shift-{{ $shift->id }}"
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('hr.shifts.edit', $shift) }}'"
                            >
                                <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-white/70">{{ $shift->code }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $shift->name }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">
                                    {{ $shift->start_time?->format('H:i') }} - {{ $shift->end_time?->format('H:i') }}
                                    @if ($shift->is_overnight)
                                        <span class="ml-1 text-xs text-amber-600 dark:text-amber-400">(overnight)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">
                                    @if ($shift->break_start && $shift->break_end)
                                        {{ $shift->break_start->format('H:i') }} - {{ $shift->break_end->format('H:i') }}
                                    @else
                                        {{ $shift->break_duration }} min
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $shift->working_hours }}h</td>
                                <td class="px-6 py-4">
                                    @if ($shift->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300">Active</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-white/50">
                                    No shifts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($shifts->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $shifts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
