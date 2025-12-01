@php /** @var bool $isEditing */ @endphp

<div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="space-y-5 px-6 py-5">
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Basic Information --}}
            <div class="space-y-3 md:border-r md:border-dashed md:border-slate-200 md:pr-6 dark:md:border-white/10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Basic Information
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Code" model="form.code" placeholder="ADJ-001" col-span="6" :required="true" />

                    <x-form.select label="Type" model="form.type" placeholder="Select type" col-span="6" :required="true">
                        <option value="addition" class="bg-white dark:bg-slate-900">Addition (Bonus/Allowance)</option>
                        <option value="deduction" class="bg-white dark:bg-slate-900">Deduction (Penalty/Loan)</option>
                    </x-form.select>

                    <div class="col-span-12">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Employee <span class="text-red-500">*</span></label>
                        <x-form.searchable-select
                            name="employee_id"
                            wire:model="form.employee_id"
                            :options="$employees"
                            value-key="id"
                            label-key="full_name"
                            sublabel-key="code"
                            placeholder="Select employee"
                        />
                        @error('form.employee_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form.input label="Description" model="form.description" placeholder="Reason for adjustment" col-span="12" :required="true" />
                </div>
            </div>

            {{-- Amount & Date --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Amount & Schedule
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Amount (Rp)" type="number" model="form.amount" placeholder="0" col-span="6" :required="true" min="0" step="1000" />
                    <x-form.input label="Effective Date" type="date" model="form.effective_date" col-span="6" :required="true" />

                    <div class="col-span-12">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Payroll Run (Optional)</label>
                        <x-form.searchable-select
                            name="payroll_run_id"
                            wire:model="form.payroll_run_id"
                            :options="$payrollRuns"
                            value-key="id"
                            label-key="name"
                            sublabel-key="code"
                            placeholder="Link to payroll run"
                        />
                        @error('form.payroll_run_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form.input label="Notes" model="form.notes" :textarea="true" rows="2" placeholder="Additional notes" col-span="12" />

                    <div class="col-span-12">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" wire:model="form.is_processed" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/20 dark:bg-slate-900">
                            <span class="text-sm text-slate-700 dark:text-white/80">Mark as processed</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($payrollAdjustment) && $payrollAdjustment)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($payrollAdjustment->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($payrollAdjustment->createdBy)->name ?? 'System' }}
                </p>
                @if ($payrollAdjustment->updated_at)
                    <p>
                        Last updated {{ optional($payrollAdjustment->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($payrollAdjustment->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.payroll-adjustments') }}"
                class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
            >
                Cancel
            </a>

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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Adjustment' }}</span>
            </button>
        </div>
    </div>
</div>
