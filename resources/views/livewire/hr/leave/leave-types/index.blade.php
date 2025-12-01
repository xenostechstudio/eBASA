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
            title="Leave Types"
            description="Manage leave categories and their configurations."
        >
            <x-slot:actions>
                <a
                    href="{{ route('hr.leave-types.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Add Type</span>
                </a>
            </x-slot:actions>
        </x-form.section-header>

        {{-- Table Card --}}
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Search --}}
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full md:max-w-xs">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search leave types..."
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-white/40"
                        >
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/40">
                            @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
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
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Default Days</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Paid</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Requires Approval</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($leaveTypes as $type)
                            <tr
                                wire:key="type-{{ $type->id }}"
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('hr.leave-types.edit', $type) }}'"
                            >
                                <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-white/70">{{ $type->code }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if ($type->color)
                                            <span class="h-3 w-3 rounded-full" style="background-color: {{ $type->color }}"></span>
                                        @endif
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $type->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $type->default_days }} days</td>
                                <td class="px-6 py-4">
                                    @if ($type->is_paid)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300">Paid</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">Unpaid</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($type->requires_approval)
                                        @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
                                    @else
                                        @svg('heroicon-o-minus-circle', 'h-5 w-5 text-slate-300 dark:text-white/20')
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($type->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300">Active</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-white/50">
                                    No leave types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($leaveTypes->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $leaveTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
