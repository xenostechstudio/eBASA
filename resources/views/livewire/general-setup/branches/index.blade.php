<div>
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            <x-stat.card
                label="Total Branches"
                :value="number_format($stats['total'])"
                description="Registered branches"
                tone="neutral"
            >
                <x-slot:icon>
                    @svg('heroicon-o-building-office', 'h-5 w-5 text-slate-500')
                </x-slot:icon>
            </x-stat.card>

            <x-stat.card
                label="Active"
                :value="number_format($stats['active'])"
                description="Accepting operations"
                tone="success"
            >
                <x-slot:icon>
                    @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
                </x-slot:icon>
            </x-stat.card>

            <x-stat.card
                label="Inactive"
                :value="number_format($stats['inactive'])"
                description="Temporarily offline"
                tone="danger"
            >
                <x-slot:icon>
                    @svg('heroicon-o-no-symbol', 'h-5 w-5 text-rose-500')
                </x-slot:icon>
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
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search branches..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>

                    {{-- Right: Filters, Export, Add --}}
                    <div class="flex items-center gap-2">
                        <x-table.dynamic-filters :filters="[
                            'status' => [
                                'label' => 'Status',
                                'options' => [
                                    'all' => 'All status',
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
                                ],
                                'selected' => $statusFilter,
                                'default' => 'all',
                                'onSelect' => 'setStatusFilter',
                            ],
                        ]" />

                        <x-table.export-dropdown aria-label="Export branches" />

                        <a
                            href="{{ route('general-setup.branches.create') }}"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                        >
                            @svg('heroicon-o-plus', 'h-4 w-4')
                            <span>Add Branch</span>
                        </a>
                    </div>
                </div>
            </div>

            @php
                $statusLabel = null;
                $statusClasses = '';
                $branchesCount = $branches->total();

                if ($statusFilter === 'active') {
                    $statusLabel = 'Status: Active';
                    $statusClasses = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300';
                } elseif ($statusFilter === 'inactive') {
                    $statusLabel = 'Status: Inactive';
                    $statusClasses = 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/80';
                }
            @endphp

            @if ($statusLabel)
                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 text-xs text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-white/70">
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-medium {{ $statusClasses }}">
                            <span>{{ $statusLabel }} ({{ $branchesCount }})</span>
                            <button
                                type="button"
                                wire:click="setStatusFilter('all')"
                                class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20"
                                aria-label="Reset status filter"
                            >
                                @svg('heroicon-o-x-mark', 'h-3 w-3')
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($branches->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                                <th class="px-5 py-3">BRANCH</th>
                                <th class="px-5 py-3">LOCATION</th>
                                <th class="px-5 py-3">CONTACT</th>
                                <th class="px-5 py-3">STATUS</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                            @foreach ($branches as $branch)
                                <tr
                                    wire:key="branch-{{ $branch->id }}"
                                    class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                    onclick="window.location='{{ route('general-setup.branches.edit', $branch) }}'"
                                >
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="font-medium text-slate-900 dark:text-white">{{ $branch->name }}</div>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $branch->code ?? '—' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="text-slate-700 dark:text-white/80">{{ $branch->city ?? '—' }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $branch->province ?? '—' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="text-slate-700 dark:text-white/80">{{ $branch->manager_name ?? '—' }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $branch->phone ?? $branch->email ?? '—' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span @class([
                                            'inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' => $branch->is_active,
                                            'inline-flex items-center rounded-lg bg-rose-100 px-2 py-1 text-xs font-medium text-rose-700 dark:bg-rose-500/20 dark:text-rose-300' => ! $branch->is_active,
                                        ])>
                                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
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

                <x-table.pagination :paginator="$branches" :per-page-options="$perPageOptions" />
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    @svg('heroicon-o-building-office', 'h-12 w-12 text-slate-300 dark:text-white/20')
                    <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No branches found</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Try adjusting your search or create a new branch</p>
                </div>
            @endif
        </div>
    </div>
</div>
