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
                    <x-form.input label="Code" model="form.code" placeholder="PR-001" col-span="6" :required="true" />
                    <x-form.input label="Name" model="form.name" placeholder="Payroll January 2024" col-span="6" :required="true" />

                    <div class="col-span-12">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Payroll Group <span class="text-red-500">*</span></label>
                        <x-form.searchable-select
                            name="payroll_group_id"
                            wire:model="form.payroll_group_id"
                            :options="$payrollGroups"
                            value-key="id"
                            label-key="name"
                            sublabel-key="code"
                            placeholder="Select payroll group"
                        />
                        @error('form.payroll_group_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form.input label="Description" model="form.description" :textarea="true" rows="2" placeholder="Optional description" col-span="12" />
                </div>
            </div>

            {{-- Period & Status --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Period & Status
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Period Start" type="date" model="form.period_start" col-span="6" :required="true" />
                    <x-form.input label="Period End" type="date" model="form.period_end" col-span="6" :required="true" />
                    <x-form.input label="Payment Date" type="date" model="form.pay_date" col-span="6" :required="true" />

                    <x-form.select label="Status" model="form.status" placeholder="Select status" col-span="6" :required="true">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" class="bg-white dark:bg-slate-900">{{ str($status)->headline() }}</option>
                        @endforeach
                    </x-form.select>

                    @if ($isEditing)
                        <div class="col-span-12 rounded-xl bg-slate-50 p-4 dark:bg-white/5">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $employeeCount ?? 0 }}</p>
                                    <p class="text-xs text-slate-500 dark:text-white/50">Employees</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-white/50">Total Amount</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $processedCount ?? 0 }}</p>
                                    <p class="text-xs text-slate-500 dark:text-white/50">Processed</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($payrollRun) && $payrollRun)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($payrollRun->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($payrollRun->createdBy)->name ?? 'System' }}
                </p>
                @if ($payrollRun->updated_at)
                    <p>
                        Last updated {{ optional($payrollRun->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($payrollRun->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.payroll-runs') }}"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Run' }}</span>
            </button>
        </div>
    </div>
</div>
