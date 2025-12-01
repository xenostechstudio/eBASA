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
                    <x-form.input label="Code" model="form.code" placeholder="SH-001" col-span="6" :required="true" />
                    <x-form.input label="Name" model="form.name" placeholder="Morning Shift" col-span="6" :required="true" />
                    <x-form.input label="Description" model="form.description" :textarea="true" rows="2" placeholder="Shift description" col-span="12" />
                </div>
            </div>

            {{-- Schedule --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Schedule
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Start Time" type="time" model="form.start_time" col-span="6" :required="true" />
                    <x-form.input label="End Time" type="time" model="form.end_time" col-span="6" :required="true" />
                    <x-form.input label="Break Start" type="time" model="form.break_start" col-span="6" />
                    <x-form.input label="Break End" type="time" model="form.break_end" col-span="6" />
                    <x-form.input label="Break Duration (minutes)" type="number" model="form.break_duration" col-span="6" :required="true" min="0" />
                    <x-form.input label="Working Hours" type="number" model="form.working_hours" col-span="6" :required="true" min="1" max="24" />

                    <div class="col-span-12 space-y-3">
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">
                            <input type="checkbox" wire:model="form.is_overnight" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/20 dark:bg-slate-900">
                            <div>
                                <span class="block text-sm font-medium text-slate-900 dark:text-white">Overnight Shift</span>
                                <span class="text-xs text-slate-500 dark:text-white/50">Shift crosses midnight</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">
                            <input type="checkbox" wire:model="form.is_active" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/20 dark:bg-slate-900">
                            <div>
                                <span class="block text-sm font-medium text-slate-900 dark:text-white">Active</span>
                                <span class="text-xs text-slate-500 dark:text-white/50">Available for assignment</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($shift) && $shift)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($shift->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($shift->createdBy)->name ?? 'System' }}
                </p>
                @if ($shift->updated_at)
                    <p>
                        Last updated {{ optional($shift->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($shift->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.shifts') }}"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Shift' }}</span>
            </button>
        </div>
    </div>
</div>
