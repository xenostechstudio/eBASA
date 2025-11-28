<div class="space-y-6">
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

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

                    <x-ui.button type="button" wire:click="openCreateModal">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Warehouse</span>
                    </x-ui.button>
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
                                wire:click="openEditModal({{ $warehouse->id }})"
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

    {{-- Create/Edit Warehouse Modal --}}
    @if ($showCreateModal || $showEditModal)
        @php $isEditing = ! is_null($editingWarehouseId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" aria-labelledby="warehouse-modal-title" role="dialog" aria-modal="true">
            <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

            <div class="relative z-10 my-6 w-full max-w-2xl max-h-[calc(100vh-6rem)] flex flex-col overflow-hidden rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 id="warehouse-modal-title" class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit Warehouse' : 'Add New Warehouse' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        {{ $isEditing ? 'Update warehouse information' : 'Create a new warehouse record' }}
                    </p>
                </div>

                <div class="space-y-5 px-6 py-5 overflow-y-auto">
                    <div class="space-y-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                            Warehouse Information
                        </p>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="warehouse-code" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                    Code
                                </label>
                                <input
                                    type="text"
                                    id="warehouse-code"
                                    wire:model="code"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                    placeholder="e.g. WH-TGL-01"
                                >
                                @error('code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="warehouse-name" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                    Name
                                </label>
                                <input
                                    type="text"
                                    id="warehouse-name"
                                    wire:model="name"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                    placeholder="e.g. Central Warehouse Tegal"
                                >
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="warehouse-branch" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                    Linked Branch
                                </label>
                                <select
                                    id="warehouse-branch"
                                    wire:model="branch_id"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                                >
                                    <option value="">All branches</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->code }} - {{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @error('branch_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="warehouse-city" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                    City
                                </label>
                                <input
                                    type="text"
                                    id="warehouse-city"
                                    wire:model="city"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                    placeholder="e.g. Tegal"
                                >
                                @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="warehouse-province" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                    Province
                                </label>
                                <input
                                    type="text"
                                    id="warehouse-province"
                                    wire:model="province"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                    placeholder="e.g. Jawa Tengah"
                                >
                                @error('province') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="warehouse-phone" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                    Phone
                                </label>
                                <input
                                    type="text"
                                    id="warehouse-phone"
                                    wire:model="phone"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                    placeholder="e.g. 021-123456"
                                >
                                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="warehouse-address" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Address
                            </label>
                            <textarea
                                id="warehouse-address"
                                wire:model="address"
                                rows="3"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                placeholder="Street, district, city"
                            ></textarea>
                            @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                            Contact & Status
                        </p>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="warehouse-contact" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                    Contact Person
                                </label>
                                <input
                                    type="text"
                                    id="warehouse-contact"
                                    wire:model="contact_name"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                    placeholder="e.g. Budi Santoso"
                                >
                                @error('contact_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-6 md:mt-7">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        wire:model="is_active"
                                        class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                                    >
                                    <span class="text-sm font-medium text-slate-700 dark:text-white/80">
                                        Warehouse is active and accepting stock
                                    </span>
                                </label>
                                @error('is_active') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-end">
                    <div class="flex items-center justify-end gap-3 md:ml-auto">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                        >
                            <span wire:loading.remove wire:target="save">
                                @svg('heroicon-o-check', 'h-4 w-4')
                            </span>
                            <span wire:loading wire:target="save">
                                @svg('heroicon-o-arrow-path', 'h-4 w-4 animate-spin')
                            </span>
                            <span>{{ $isEditing ? 'Save changes' : 'Create warehouse' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
