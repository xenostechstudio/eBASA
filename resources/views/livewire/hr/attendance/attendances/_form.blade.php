@php /** @var bool $isEditing */ @endphp

<div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="space-y-5 px-6 py-5">
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Employee & Schedule --}}
            <div class="space-y-3 md:border-r md:border-dashed md:border-slate-200 md:pr-6 dark:md:border-white/10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Employee & Schedule
                </p>

                <div class="grid gap-4 md:grid-cols-12">
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

                    <div class="col-span-6">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Shift</label>
                        <x-form.searchable-select
                            name="shift_id"
                            wire:model="form.shift_id"
                            :options="$shifts"
                            value-key="id"
                            label-key="name"
                            sublabel-key="code"
                            placeholder="Select shift"
                        />
                        @error('form.shift_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Branch</label>
                        <x-form.searchable-select
                            name="branch_id"
                            wire:model="form.branch_id"
                            :options="$branches"
                            value-key="id"
                            label-key="name"
                            placeholder="Select branch"
                        />
                        @error('form.branch_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form.input label="Date" type="date" model="form.date" col-span="12" :required="true" />
                </div>
            </div>

            {{-- Time & Status --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Time & Status
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Clock In" type="time" model="form.clock_in" col-span="6" />
                    <x-form.input label="Clock Out" type="time" model="form.clock_out" col-span="6" />

                    @if ($isEditing)
                        <x-form.input label="Late (minutes)" type="number" model="form.late_minutes" col-span="4" min="0" />
                        <x-form.input label="Early Leave (minutes)" type="number" model="form.early_leave_minutes" col-span="4" min="0" />
                        <x-form.input label="Overtime (minutes)" type="number" model="form.overtime_minutes" col-span="4" min="0" />
                    @endif

                    <x-form.select label="Status" model="form.status" placeholder="Select status" col-span="12" :required="true">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" class="bg-white dark:bg-slate-900">{{ str($status)->headline() }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.input label="Notes" model="form.notes" :textarea="true" rows="2" placeholder="Additional notes" col-span="12" />
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($attendance) && $attendance)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($attendance->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($attendance->createdBy)->name ?? 'System' }}
                </p>
                @if ($attendance->updated_at)
                    <p>
                        Last updated {{ optional($attendance->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($attendance->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.attendances') }}"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Record Attendance' }}</span>
            </button>
        </div>
    </div>
</div>
