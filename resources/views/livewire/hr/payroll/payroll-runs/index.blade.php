<div>
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Stats Cards --}}
        @php
            $totalRuns = $runs->total();
            $draftCount = \App\Models\PayrollRun::where('status', 'draft')->count();
            $processingCount = \App\Models\PayrollRun::where('status', 'processing')->count();
            $paidCount = \App\Models\PayrollRun::where('status', 'paid')->count();
        @endphp
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat.card label="Total Runs" :value="number_format($totalRuns)" description="Payroll runs" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-banknotes', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Draft" :value="number_format($draftCount)" description="Pending processing" tone="warning">
                <x-slot:icon>@svg('heroicon-o-pencil-square', 'h-5 w-5 text-amber-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Processing" :value="number_format($processingCount)" description="In progress" tone="info">
                <x-slot:icon>@svg('heroicon-o-arrow-path', 'h-5 w-5 text-sky-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Paid" :value="number_format($paidCount)" description="Completed" tone="success">
                <x-slot:icon>@svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')</x-slot:icon>
            </x-stat.card>
        </div>

        {{-- Table Card --}}
        <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Toolbar --}}
            <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    {{-- Left: Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search runs..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                    </div>

                    {{-- Right: Filters, Export, Add --}}
                    <div class="flex items-center gap-2">
                        {{-- Dynamic Filters --}}
                        <x-table.dynamic-filters :filters="[
                            'status' => [
                                'label' => 'Status',
                                'options' => array_merge(
                                    ['all' => 'All status'],
                                    collect($statuses)->mapWithKeys(fn($s) => [$s => str($s)->headline()->toString()])->toArray()
                                ),
                                'selected' => $filterStatus ?: 'all',
                                'default' => 'all',
                                'onSelect' => 'setStatusFilter',
                            ],
                            'payroll_group' => [
                                'label' => 'Payroll Group',
                                'options' => array_merge(
                                    ['all' => 'All groups'],
                                    $payrollGroups->pluck('name', 'id')->toArray()
                                ),
                                'selected' => $filterPayrollGroup ?: 'all',
                                'default' => 'all',
                                'onSelect' => 'setPayrollGroupFilter',
                            ],
                            'branch' => [
                                'label' => 'Branch',
                                'options' => array_merge(
                                    ['all' => 'All branches'],
                                    $branches->pluck('name', 'id')->toArray()
                                ),
                                'selected' => $filterBranch ?: 'all',
                                'default' => 'all',
                                'onSelect' => 'setBranchFilter',
                            ],
                        ]" />

                        {{-- Export --}}
                        <x-table.export-dropdown aria-label="Export payroll runs" />

                        {{-- Add Button --}}
                        <a href="{{ route('hr.payroll-runs.create') }}"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                            @svg('heroicon-o-plus', 'h-4 w-4')
                            <span>New Run</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Active Filters Pills --}}
            @php
                $hasActiveFilters = $filterStatus !== '' || $filterPayrollGroup !== '' || $filterBranch !== '';
            @endphp
            @if ($hasActiveFilters)
                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 dark:border-white/10 dark:bg-white/5">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($filterStatus !== '')
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                <span>Status: {{ str($filterStatus)->headline() }}</span>
                                <button type="button" wire:click="$set('filterStatus', '')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </span>
                        @endif

                        @if ($filterPayrollGroup !== '')
                            @php $groupName = $payrollGroups->firstWhere('id', (int) $filterPayrollGroup)?->name; @endphp
                            <span class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1 text-[11px] font-medium text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                <span>Group: {{ $groupName }}</span>
                                <button type="button" wire:click="$set('filterPayrollGroup', '')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </span>
                        @endif

                        @if ($filterBranch !== '')
                            @php $branchName = $branches->firstWhere('id', (int) $filterBranch)?->name; @endphp
                            <span class="inline-flex items-center gap-2 rounded-full bg-violet-50 px-3 py-1 text-[11px] font-medium text-violet-700 dark:bg-violet-500/10 dark:text-violet-300">
                                <span>Branch: {{ $branchName }}</span>
                                <button type="button" wire:click="$set('filterBranch', '')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </span>
                        @endif

                        <button type="button" wire:click="resetFilters" class="text-[11px] font-medium text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white/70">
                            Clear all
                        </button>
                    </div>
                </div>
            @endif

            {{-- Table --}}
            @if ($runs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                                <th class="px-5 py-3">Code</th>
                                <th class="px-5 py-3">Name</th>
                                <th class="px-5 py-3">Period</th>
                                <th class="px-5 py-3">Payroll Group</th>
                                <th class="px-5 py-3 text-right">Total Amount</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60',
                                    'processing' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                    'approved' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                    'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                    'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                                ];
                            @endphp
                            @foreach ($runs as $run)
                                <tr
                                    wire:key="run-{{ $run->id }}"
                                    class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                    onclick="window.location='{{ route('hr.payroll-runs.edit', $run) }}'"
                                >
                                    <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-slate-600 dark:text-white/70">{{ $run->code }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $run->name }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        {{ $run->period_start?->format('d M') }} - {{ $run->period_end?->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        {{ $run->payrollGroup?->name ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        <p class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($run->total_net ?? 0, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$run->status] ?? $statusColors['draft'] }}">
                                            {{ str($run->status)->headline() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-400')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($runs->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4 dark:border-white/10">
                        {{ $runs->links() }}
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    @svg('heroicon-o-banknotes', 'h-12 w-12 text-slate-300 dark:text-white/20')
                    <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No payroll runs found</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                        @if ($search || $hasActiveFilters)
                            Try adjusting your search or filters
                        @else
                            Get started by creating your first payroll run
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
