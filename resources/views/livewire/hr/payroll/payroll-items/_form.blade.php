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
                    <x-form.input label="Code" model="form.code" placeholder="e.g. PI-001" col-span="6" :required="true" />
                    <x-form.input label="Name" model="form.name" placeholder="e.g. Tunjangan Transport" col-span="6" :required="true" />

                    <x-form.select label="Type" model="form.type" col-span="6" :required="true">
                        @foreach ($types as $type)
                            <option value="{{ $type }}" class="bg-white dark:bg-slate-900">{{ str($type)->headline() }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Category" model="form.category" col-span="6" :required="true">
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" class="bg-white dark:bg-slate-900">{{ $label }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.input label="Description" model="form.description" :textarea="true" rows="3" placeholder="Item description" col-span="12" />
                </div>
            </div>

            {{-- Calculation --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Calculation & Settings
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.select label="Calculation Type" model="form.calculation_type" col-span="6" :required="true">
                        @foreach ($calculationTypes as $calcType)
                            <option value="{{ $calcType }}" class="bg-white dark:bg-slate-900">{{ str($calcType)->headline() }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.input label="Default Amount" type="number" model="form.default_amount" placeholder="0" col-span="6" :required="true" min="0" step="1000" />

                    <x-form.input label="Percentage Base (%)" type="number" model="form.percentage_base" placeholder="0" col-span="6" min="0" max="100" step="0.01" />

                    <x-form.input label="Sort Order" type="number" model="form.sort_order" placeholder="0" col-span="6" min="0" />

                    <div class="col-span-12 space-y-3">
                        <div class="flex items-center gap-2">
                            <input
                                id="is_taxable"
                                type="checkbox"
                                wire:model="form.is_taxable"
                                class="h-4 w-4 rounded border-slate-300 bg-white text-slate-900 focus:ring-0 dark:border-white/30 dark:bg-slate-900 dark:text-white"
                            >
                            <label for="is_taxable" class="text-sm text-slate-600 dark:text-white/80">Taxable (subject to PPh 21)</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                id="is_recurring"
                                type="checkbox"
                                wire:model="form.is_recurring"
                                class="h-4 w-4 rounded border-slate-300 bg-white text-slate-900 focus:ring-0 dark:border-white/30 dark:bg-slate-900 dark:text-white"
                            >
                            <label for="is_recurring" class="text-sm text-slate-600 dark:text-white/80">Recurring (applied every payroll)</label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                id="is_active"
                                type="checkbox"
                                wire:model="form.is_active"
                                class="h-4 w-4 rounded border-slate-300 bg-white text-slate-900 focus:ring-0 dark:border-white/30 dark:bg-slate-900 dark:text-white"
                            >
                            <label for="is_active" class="text-sm text-slate-600 dark:text-white/80">Active</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($payrollItem) && $payrollItem)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($payrollItem->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($payrollItem->createdBy)->name ?? 'System' }}
                </p>
                @if ($payrollItem->updated_at)
                    <p>
                        Last updated {{ optional($payrollItem->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($payrollItem->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.payroll-items') }}"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Item' }}</span>
            </button>
        </div>
    </div>
</div>
