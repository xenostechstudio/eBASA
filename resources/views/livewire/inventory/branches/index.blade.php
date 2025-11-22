<div class="space-y-10">
    <x-module.heading
        tagline="Inventory · Branches"
        title="Branch directory"
        description="Monitor BASA retail footprint, availability status, and operational contacts."
    >
        <x-slot:actions>
            <x-ui.button variant="secondary">Export list</x-ui.button>
            <x-ui.button as="a" href="{{ route('inventory.branches.create') }}">New branch</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Total</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-white/60">Registered branches</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-emerald-300/80">Active</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['active'] }}</p>
            <p class="text-xs text-white/60">Accepting operations</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-300/80">Inactive</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['inactive'] }}</p>
            <p class="text-xs text-white/60">Temporarily offline</p>
        </div>
    </section>

    <section class="rounded-[28px] border border-white/10 bg-white/5 overflow-hidden">
        <div class="px-6 pt-4 pb-4 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <x-table.search wire:model.debounce.300ms="search" placeholder="Search branch" />
                    <x-table.filter-dropdown
                        label="Status"
                        :options="['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive']"
                        :selected="$statusFilter"
                        on-select="setStatusFilter"
                    />
                </div>
                <x-table.pagination-controls
                    :paginator="$branches"
                    :per-page-options="$perPageOptions"
                    wire:model.live="perPage"
                />
            </div>
        </div>

        <div class="-mx-6 -mb-6 overflow-hidden border-t border-white/10 bg-slate-900/40 pb-6">
            <div class="min-w-full overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-white/60">
                            <th class="px-6 py-3 font-medium">Branch</th>
                            <th class="px-6 py-3 font-medium">Location</th>
                            <th class="px-6 py-3 font-medium">Contact</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-white/80">
                        @forelse ($branches as $branch)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-white">{{ $branch->name }}</div>
                                    <p class="text-xs text-white/50">Code: {{ $branch->code ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p>{{ $branch->city ?? '—' }}</p>
                                    <p class="text-xs text-white/50">{{ $branch->province ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p>{{ $branch->manager_name ?? '—' }}</p>
                                    <p class="text-xs text-white/50">{{ $branch->phone ?? $branch->email ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide',
                                        'bg-emerald-400/20 text-emerald-200' => $branch->is_active,
                                        'bg-rose-400/20 text-rose-200' => ! $branch->is_active,
                                    ])>
                                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-white/50">
                                    No branches found. Try adjusting filters or create a new entry.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6">
                {{ $branches->links() }}
            </div>
        </div>
    </section>
</div>
