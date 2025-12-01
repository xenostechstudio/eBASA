@php /** @var bool $isEditing */ @endphp

<div class="space-y-6">
    {{-- Main Form Card --}}
    <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="space-y-5 px-6 py-5">
            <div class="grid gap-6 md:grid-cols-2">
                {{-- Assignment --}}
                <div class="space-y-3 md:border-r md:border-dashed md:border-slate-200 md:pr-6 dark:md:border-white/10">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Assignment
                    </p>

                    <div class="grid gap-4 md:grid-cols-12">
                        <x-form.select label="Employee" model="form.employee_id" placeholder="Select employee" col-span="12" :required="true" :disabled="$isEditing">
                            @foreach ($employees as $person)
                                <option value="{{ $person['id'] }}" class="bg-white dark:bg-slate-900">
                                    {{ $person['full_name'] }} · {{ $person['code'] }}
                                </option>
                            @endforeach
                        </x-form.select>

                        <x-form.select label="Branch" model="form.branch_id" placeholder="Select branch" col-span="12" :required="true">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch['id'] }}" class="bg-white dark:bg-slate-900">{{ $branch['name'] }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select label="Department" model="form.department_id" placeholder="Select department" col-span="12">
                            @foreach ($departments as $department)
                                <option value="{{ $department['id'] }}" class="bg-white dark:bg-slate-900">{{ $department['name'] }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select label="Position" model="form.position_id" placeholder="Select position" col-span="12">
                            @foreach ($positions as $position)
                                <option value="{{ $position['id'] }}" class="bg-white dark:bg-slate-900">{{ $position['title'] }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                {{-- Contract --}}
                <div class="space-y-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Contract
                    </p>

                    <div class="grid gap-4 md:grid-cols-12">
                        <x-form.select label="Employment type" model="form.employment_type" placeholder="Choose type" col-span="6" :required="true">
                            @foreach ($employmentTypes as $type)
                                <option value="{{ $type }}" class="bg-white dark:bg-slate-900">{{ str($type)->headline() }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select label="Employment class" model="form.employment_class" placeholder="Choose class" col-span="6">
                            @foreach ($employmentClasses as $class)
                                <option value="{{ $class }}" class="bg-white dark:bg-slate-900">{{ str($class)->headline() }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select label="Work mode" model="form.work_mode" placeholder="Choose mode" col-span="6">
                            @foreach ($workModes as $mode)
                                <option value="{{ $mode }}" class="bg-white dark:bg-slate-900">{{ str($mode)->headline() }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.select label="Status" model="form.status" placeholder="Select status" col-span="6" :required="true">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" class="bg-white dark:bg-slate-900">{{ str($status)->headline() }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.input label="Start date" type="date" model="form.start_date" col-span="6" :required="true" />
                        <x-form.input label="Probation end" type="date" model="form.probation_end_date" col-span="6" />
                        <x-form.input label="Notes" model="form.notes" :textarea="true" rows="2" placeholder="Contract notes" col-span="12" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Salary Section --}}
        <div class="border-t border-slate-200 px-6 py-5 dark:border-white/10">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-3 md:border-r md:border-dashed md:border-slate-200 md:pr-6 dark:md:border-white/10">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Salary & Payroll
                    </p>

                    <div class="grid gap-4 md:grid-cols-12">
                        <x-form.input label="Base Salary (Rp)" type="number" model="form.base_salary" placeholder="0" col-span="6" :required="true" min="0" step="100000" />
                        <x-form.input label="Salary Band" model="form.salary_band" placeholder="e.g. P1, M2" col-span="6" />

                        <div class="col-span-12">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Payroll Group</label>
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
                    </div>
                </div>

                {{-- Salary Summary --}}
                <div class="space-y-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Monthly Summary
                    </p>

                    @php
                        $totalEarnings = collect($employeePayrollItems ?? [])->where('type', 'earning')->sum('amount');
                        $totalDeductions = collect($employeePayrollItems ?? [])->where('type', 'deduction')->sum('amount');
                        $netSalary = $totalEarnings - $totalDeductions;
                    @endphp

                    <div class="space-y-2">
                        <div class="flex items-center justify-between rounded-lg bg-emerald-50 px-4 py-3 dark:bg-emerald-500/10">
                            <span class="text-sm text-emerald-700 dark:text-emerald-300">Total Earnings</span>
                            <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-rose-50 px-4 py-3 dark:bg-rose-500/10">
                            <span class="text-sm text-rose-700 dark:text-rose-300">Total Deductions</span>
                            <span class="text-sm font-semibold text-rose-700 dark:text-rose-300">Rp {{ number_format($totalDeductions, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-slate-100 px-4 py-3 dark:bg-white/10">
                            <span class="text-sm font-medium text-slate-700 dark:text-white">Net Salary</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-white">Rp {{ number_format($netSalary, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
            @if ($isEditing && isset($employee) && $employee)
                <div class="text-[11px] text-slate-400 dark:text-white/40">
                    <p>
                        Created {{ optional($employee->created_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($employee->createdBy)->name ?? 'System' }}
                    </p>
                    @if ($employee->updated_at)
                        <p>
                            Last updated {{ optional($employee->updated_at)->format(config('basa.datetime_format')) }}
                            by {{ optional($employee->updatedBy)->name ?? 'System' }}
                        </p>
                    @endif
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 md:ml-auto">
                <a
                    href="{{ route('hr.employments') }}"
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
                    <span>{{ $isEditing ? 'Save Changes' : 'Create Employment' }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Payroll Items Card (only for editing) --}}
    @if ($isEditing)
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Payroll Items</h3>
                        <p class="text-xs text-slate-500 dark:text-white/60">Manage earnings and deductions for this employee</p>
                    </div>
                </div>
            </div>

            {{-- Add New Item --}}
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex flex-col gap-3 md:flex-row md:items-end">
                    <div class="flex-1">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Add Payroll Item</label>
                        <x-form.searchable-select
                            name="new_payroll_item"
                            wire:model="newPayrollItemId"
                            :options="$payrollItems"
                            value-key="id"
                            label-key="name"
                            sublabel-key="code"
                            placeholder="Select payroll item to add"
                        />
                    </div>
                    <div class="w-full md:w-48">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Amount (Rp)</label>
                        <input
                            type="number"
                            wire:model="newPayrollItemAmount"
                            placeholder="Use default"
                            min="0"
                            step="1000"
                            class="h-10 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder-white/40"
                        >
                    </div>
                    <button
                        type="button"
                        wire:click="addPayrollItem"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                    >
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add</span>
                    </button>
                </div>
            </div>

            {{-- Items List --}}
            <div class="divide-y divide-slate-200 dark:divide-white/10">
                @forelse ($employeePayrollItems as $item)
                    <div class="flex items-center justify-between px-6 py-3" wire:key="payroll-item-{{ $item['id'] }}">
                        <div class="flex items-center gap-3">
                            @if ($item['type'] === 'earning')
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                                    @svg('heroicon-o-arrow-trending-up', 'h-4 w-4')
                                </span>
                            @else
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400">
                                    @svg('heroicon-o-arrow-trending-down', 'h-4 w-4')
                                </span>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $item['name'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-white/50">{{ $item['code'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <input
                                    type="number"
                                    value="{{ $item['amount'] }}"
                                    wire:change="updatePayrollItemAmount({{ $item['id'] }}, $event.target.value)"
                                    min="0"
                                    step="1000"
                                    class="h-9 w-36 rounded-lg border border-slate-200 bg-white px-3 text-right text-sm font-medium text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white"
                                >
                            </div>
                            <button
                                type="button"
                                wire:click="removePayrollItem({{ $item['id'] }})"
                                wire:confirm="Are you sure you want to remove this payroll item?"
                                class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                title="Remove"
                            >
                                @svg('heroicon-o-trash', 'h-4 w-4')
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        @svg('heroicon-o-banknotes', 'mx-auto h-10 w-10 text-slate-300 dark:text-white/20')
                        <p class="mt-2 text-sm text-slate-500 dark:text-white/50">No payroll items assigned</p>
                        <p class="text-xs text-slate-400 dark:text-white/40">Add items using the form above</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
