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
            title="Payroll Adjustments"
            description="Manage one-time payroll adjustments for employees."
        >
            <x-slot:actions>
                <a
                    href="{{ route('hr.payroll-adjustments.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>New Adjustment</span>
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
                            placeholder="Search adjustments..."
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
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Employee</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Type</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Amount</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Effective Date</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($adjustments as $adjustment)
                            <tr
                                wire:key="adjustment-{{ $adjustment->id }}"
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('hr.payroll-adjustments.edit', $adjustment) }}'"
                            >
                                <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-white/70">{{ $adjustment->code }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $adjustment->employee?->full_name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($adjustment->type === 'addition')
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300">Addition</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700 dark:bg-rose-400/20 dark:text-rose-300">Deduction</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium {{ $adjustment->type === 'addition' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $adjustment->type === 'addition' ? '+' : '-' }} Rp {{ number_format($adjustment->amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $adjustment->effective_date?->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @if ($adjustment->is_processed)
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300">Processed</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-400/20 dark:text-amber-300">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-white/50">
                                    No payroll adjustments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($adjustments->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $adjustments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
