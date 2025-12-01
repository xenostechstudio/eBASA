<div>
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card
            label="Total Warehouses"
            :value="number_format($stats['total'])"
            description="Registered warehouses"
            tone="neutral"
        >
            <x-slot:icon>
                @svg('heroicon-o-building-storefront', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card
            label="Active"
            :value="number_format($stats['active'])"
            description="Online locations"
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

        {{-- Warehouses Table --}}
        <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Warehouses</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        Manage storage locations linked to branches.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search warehouses..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>

                    {{-- Filters --}}
                    <x-table.dynamic-filters :filters="[
                        'status' => [
                            'label' => 'Status',
                            'options' => [
                                '' => 'All status',
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ],
                            'selected' => $statusFilter,
                            'default' => '',
                            'onSelect' => 'setStatusFilter',
                        ],
                        'branch' => [
                            'label' => 'Branch',
                            'options' => collect($branches)->mapWithKeys(fn($b) => [
                                (string) $b->id => $b->code . ' - ' . $b->name,
                            ])->prepend('All branches', ''),
                            'selected' => (string) $branchFilter,
                            'default' => '',
                            'onSelect' => 'setBranchFilter',
                        ],
                    ]" />

                    <x-table.export-dropdown aria-label="Export warehouses" />

                    <a
                        href="{{ route('general-setup.warehouses.create') }}"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                    >
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Warehouse</span>
                    </a>
                </div>
            </div>
        </div>

        @php
            $statusLabel = null;
            $statusClasses = '';
            $warehousesCount = $warehouses->total();

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
                        <span>{{ $statusLabel }} ({{ $warehousesCount }})</span>
                        <button
                            type="button"
                            wire:click="setStatusFilter('')"
                            class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20"
                            aria-label="Reset status filter"
                        >
                            @svg('heroicon-o-x-mark', 'h-3 w-3')
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if ($warehouses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                            <th class="px-5 py-3">CODE</th>
                            <th class="px-5 py-3">WAREHOUSE</th>
                            <th class="px-5 py-3">BRANCH</th>
                            <th class="px-5 py-3">LOCATION</th>
                            <th class="px-5 py-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($warehouses as $warehouse)
                            <tr
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('general-setup.warehouses.edit', $warehouse) }}'"
                            >
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-mono text-slate-700 dark:text-white/80">
                                    {{ $warehouse->code }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $warehouse->name }}</div>
                                    @if ($warehouse->contact_name)
                                        <p class="text-xs text-slate-500 dark:text-white/50">Contact: {{ $warehouse->contact_name }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($warehouse->branch)
                                        <p class="text-sm text-slate-700 dark:text-white/80">{{ $warehouse->branch->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">Code: {{ $warehouse->branch->code }}</p>
                                    @else
                                        <span class="text-sm text-slate-400 dark:text-white/40">Unassigned</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="text-slate-700 dark:text-white/80">{{ $warehouse->city ?? '—' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-white/50">{{ $warehouse->province ?? '—' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($warehouse->is_active)
                                        <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-table.pagination :paginator="$warehouses" :per-page-options="$perPageOptions" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-building-storefront', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No warehouses found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Create your first warehouse to organize stock locations</p>
            </div>
        @endif
    </div>
</div>
