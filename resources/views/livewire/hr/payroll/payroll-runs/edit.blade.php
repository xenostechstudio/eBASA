<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="Edit Payroll Run"
            description="Update payroll run details and status."
        />

        @include('livewire.hr.payroll.payroll-runs._form', ['isEditing' => true])

        {{-- Unified Employee Payroll Table --}}
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Header --}}
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Employee Payroll</h3>
                        <p class="text-xs text-slate-500 dark:text-white/60">
                            {{ $payoutCount }} of {{ $totalGroupEmployees }} employees have payouts generated
                            <span class="mx-1">·</span>
                            {{ $payrollRun->payrollGroup?->name }}
                        </p>
                    </div>
                    @if ($payrollRun->status === 'draft')
                        <button
                            type="button"
                            wire:click="generatePayouts"
                            wire:loading.attr="disabled"
                            wire:target="generatePayouts"
                            class="inline-flex h-9 items-center gap-2 rounded-lg bg-emerald-600 px-3 text-sm font-medium text-white transition hover:bg-emerald-700 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="generatePayouts">
                                @svg('heroicon-o-calculator', 'h-4 w-4')
                            </span>
                            <span wire:loading wire:target="generatePayouts">
                                @svg('heroicon-o-arrow-path', 'h-4 w-4 animate-spin')
                            </span>
                            <span>Generate Payouts</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Summary Stats --}}
            @if ($payoutCount > 0)
                <div class="grid grid-cols-3 gap-4 border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-white/10 dark:bg-white/5">
                    <div class="text-center">
                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalGross, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Total Gross</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($totalDeductions, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Total Deductions</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-slate-900 dark:text-white">Rp {{ number_format($totalNet, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Total Net Pay</p>
                    </div>
                </div>
            @endif

            {{-- Search --}}
            <div class="border-b border-slate-200 px-6 py-3 dark:border-white/10">
                <div class="relative w-full md:max-w-xs">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="employeeSearch"
                        placeholder="Search by name or code..."
                        class="h-9 w-full rounded-lg border border-slate-200 bg-white pl-9 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-white/40"
                    >
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/40">
                        @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                    </span>
                </div>
            </div>

            {{-- Unified Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Employee</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Position</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60 text-right">Base Salary</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60 text-right">Gross</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60 text-right">Deductions</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60 text-right">Net Pay</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60 text-center">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($employees as $employee)
                            @php
                                $payout = $payoutsByEmployee->get($employee->id);
                                $hasPayout = !is_null($payout);
                            @endphp
                            <tr wire:key="emp-{{ $employee->id }}" class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $employee->full_name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $employee->code }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">
                                    <p>{{ $employee->position?->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-400 dark:text-white/40">{{ $employee->department?->name ?? '' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-slate-700 dark:text-white/80">
                                    Rp {{ number_format($employee->base_salary ?? 0, 0, ',', '.') }}
                                </td>
                                @if ($hasPayout)
                                    <td class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($payout->gross_salary ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-rose-600 dark:text-rose-400">
                                        Rp {{ number_format($payout->total_deductions ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-slate-900 dark:text-white">
                                        Rp {{ number_format($payout->net_salary ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
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
                                    <td class="px-6 py-4 text-right">
                                        @if ($payrollRun->status === 'draft')
                                            <button
                                                type="button"
                                                wire:click="removePayout({{ $payout->id }})"
                                                wire:confirm="Remove this payout?"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                                title="Remove payout"
                                            >
                                                @svg('heroicon-o-trash', 'h-4 w-4')
                                            </button>
                                        @endif
                                    </td>
                                @else
                                    <td class="px-6 py-4 text-right text-slate-400 dark:text-white/30">—</td>
                                    <td class="px-6 py-4 text-right text-slate-400 dark:text-white/30">—</td>
                                    <td class="px-6 py-4 text-right text-slate-400 dark:text-white/30">—</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500 dark:bg-white/10 dark:text-white/50">
                                            @svg('heroicon-o-clock', 'h-3 w-3')
                                            Not Generated
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"></td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center">
                                    @svg('heroicon-o-user-group', 'mx-auto h-10 w-10 text-slate-300 dark:text-white/20')
                                    <p class="mt-2 text-sm text-slate-500 dark:text-white/50">No employees found</p>
                                    @if ($employeeSearch)
                                        <p class="text-xs text-slate-400 dark:text-white/40">Try adjusting your search</p>
                                    @else
                                        <p class="text-xs text-slate-400 dark:text-white/40">No employees in this payroll group</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($employees->hasPages())
                <x-table.pagination :paginator="$employees" :per-page-options="$perPageOptions" />
            @endif
        </div>
    </div>
</div>
