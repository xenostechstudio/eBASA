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
            title="Payroll Runs"
            description="Manage payroll processing periods and calculations."
        >
            <x-slot:actions>
                <a
                    href="{{ route('hr.payroll-runs.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>New Run</span>
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
                            placeholder="Search runs..."
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
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}">{{ str($status)->headline() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-medium text-slate-600 dark:text-white/60">Payroll Group</label>
                                    <select wire:model.live="filterPayrollGroup" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-sm dark:border-white/10 dark:bg-slate-900 dark:text-white">
                                        <option value="">All Groups</option>
                                        @foreach ($payrollGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
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
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Code</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Name</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Period</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Payroll Group</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Total Amount</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($runs as $run)
                            <tr
                                wire:key="run-{{ $run->id }}"
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('hr.payroll-runs.edit', $run) }}'"
                            >
                                <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-white/70">{{ $run->code }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $run->name }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">
                                    {{ $run->period_start?->format('d M') }} - {{ $run->period_end?->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $run->payrollGroup?->name ?? '—' }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                    Rp {{ number_format($run->total_net ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60',
                                            'processing' => 'bg-amber-100 text-amber-700 dark:bg-amber-400/20 dark:text-amber-300',
                                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300',
                                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-400/20 dark:text-red-300',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$run->status] ?? $statusColors['draft'] }}">
                                        {{ str($run->status)->headline() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-white/50">
                                    No payroll runs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($runs->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $runs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
