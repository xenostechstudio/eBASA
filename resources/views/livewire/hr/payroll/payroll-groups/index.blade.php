<div>
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat.card label="Total Groups" :value="number_format($groups->total())" description="Payroll groups" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-rectangle-stack', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Active" :value="number_format(\App\Models\PayrollGroup::where('is_active', true)->count())" description="Currently active" tone="success">
                <x-slot:icon>@svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Monthly" :value="number_format(\App\Models\PayrollGroup::where('pay_frequency', 'monthly')->count())" description="Monthly frequency" tone="info">
                <x-slot:icon>@svg('heroicon-o-calendar', 'h-5 w-5 text-sky-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Biweekly" :value="number_format(\App\Models\PayrollGroup::where('pay_frequency', 'biweekly')->count())" description="Biweekly frequency" tone="warning">
                <x-slot:icon>@svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')</x-slot:icon>
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
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search groups..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                    </div>

                    {{-- Right: Filters, Export, Add --}}
                    <div class="flex items-center gap-2">
                        {{-- Dynamic Filters --}}
                        <x-table.dynamic-filters :filters="[
                            'frequency' => [
                                'label' => 'Frequency',
                                'options' => array_merge(
                                    ['all' => 'All frequencies'],
                                    collect($frequencies)->mapWithKeys(fn($f) => [$f => str($f)->headline()->toString()])->toArray()
                                ),
                                'selected' => $filterFrequency ?: 'all',
                                'default' => 'all',
                                'onSelect' => 'setFrequencyFilter',
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
                            'status' => [
                                'label' => 'Status',
                                'options' => [
                                    'all' => 'All status',
                                    '1' => 'Active',
                                    '0' => 'Inactive',
                                ],
                                'selected' => $filterStatus !== '' ? $filterStatus : 'all',
                                'default' => 'all',
                                'onSelect' => 'setStatusFilter',
                            ],
                        ]" />

                        {{-- Export --}}
                        <x-table.export-dropdown aria-label="Export payroll groups" />

                        {{-- Add Button --}}
                        <a href="{{ route('hr.payroll-groups.create') }}"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                            @svg('heroicon-o-plus', 'h-4 w-4')
                            <span>Add Group</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Active Filters Pills --}}
            @php
                $hasActiveFilters = $filterFrequency !== '' || $filterBranch !== '' || $filterStatus !== '';
            @endphp
            @if ($hasActiveFilters)
                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 dark:border-white/10 dark:bg-white/5">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($filterFrequency !== '')
                            <span class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1 text-[11px] font-medium text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                <span>Frequency: {{ str($filterFrequency)->headline() }}</span>
                                <button type="button" wire:click="$set('filterFrequency', '')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
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

                        @if ($filterStatus !== '')
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                <span>Status: {{ $filterStatus === '1' ? 'Active' : 'Inactive' }}</span>
                                <button type="button" wire:click="$set('filterStatus', '')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
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
            @if ($groups->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                                <th class="px-5 py-3">Code</th>
                                <th class="px-5 py-3">Name</th>
                                <th class="px-5 py-3">Frequency</th>
                                <th class="px-5 py-3">Pay Day</th>
                                <th class="px-5 py-3">Branch</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                            @foreach ($groups as $group)
                                <tr
                                    wire:key="group-{{ $group->id }}"
                                    class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                    onclick="window.location='{{ route('hr.payroll-groups.edit', $group) }}'"
                                >
                                    <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-slate-600 dark:text-white/70">{{ $group->code }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $group->name }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/70">
                                            {{ str($group->pay_frequency)->headline() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        Day {{ $group->pay_day }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        {{ $group->branch?->name ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($group->is_active)
                                            <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">Active</span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60">Inactive</span>
                                        @endif
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
                @if ($groups->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4 dark:border-white/10">
                        {{ $groups->links() }}
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    @svg('heroicon-o-rectangle-stack', 'h-12 w-12 text-slate-300 dark:text-white/20')
                    <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No payroll groups found</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                        @if ($search || $hasActiveFilters)
                            Try adjusting your search or filters
                        @else
                            Get started by adding your first payroll group
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
