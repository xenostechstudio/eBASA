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
            title="Payroll Payouts"
            description="View and manage individual employee payouts."
        >
            <x-slot:actions>
                <x-table.export-dropdown export-pdf="exportPdf" export-excel="exportExcel" />
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
                            placeholder="Search payouts..."
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-white/40"
                        >
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/40">
                            @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                        </span>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Employee</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Payroll Run</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Gross</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Deductions</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Net Pay</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($payouts as $payout)
                            <tr wire:key="payout-{{ $payout->id }}" class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $payout->employee?->full_name ?? '—' }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $payout->employee?->code }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $payout->payrollRun?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-emerald-600 dark:text-emerald-400">Rp {{ number_format($payout->gross_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-rose-600 dark:text-rose-400">Rp {{ number_format($payout->total_deductions ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">Rp {{ number_format($payout->net_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-400/20 dark:text-amber-300',
                                            'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300',
                                            'failed' => 'bg-red-100 text-red-700 dark:bg-red-400/20 dark:text-red-300',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$payout->status] ?? $statusColors['pending'] }}">
                                        {{ str($payout->status)->headline() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-white/50">
                                    No payroll payouts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($payouts->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $payouts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
