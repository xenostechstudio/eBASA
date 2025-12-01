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
                    <x-form.input label="Code" model="form.code" placeholder="LT-001" col-span="6" :required="true" />
                    <x-form.input label="Name" model="form.name" placeholder="Annual Leave" col-span="6" :required="true" />
                    <x-form.input label="Description" model="form.description" :textarea="true" rows="2" placeholder="Leave type description" col-span="12" />

                    <div class="col-span-6">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Color</label>
                        <input
                            type="color"
                            wire:model="form.color"
                            class="h-10 w-full cursor-pointer rounded-xl border border-slate-200 bg-white p-1 dark:border-white/10 dark:bg-slate-900"
                        >
                        @error('form.color')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form.input label="Default Days" type="number" model="form.default_days" placeholder="12" col-span="6" :required="true" min="0" />
                </div>
            </div>

            {{-- Settings --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Settings
                </p>

                <div class="space-y-4">
                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">
                        <input type="checkbox" wire:model="form.is_paid" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/20 dark:bg-slate-900">
                        <div>
                            <span class="block text-sm font-medium text-slate-900 dark:text-white">Paid Leave</span>
                            <span class="text-xs text-slate-500 dark:text-white/50">Employee receives salary during this leave</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">
                        <input type="checkbox" wire:model="form.requires_approval" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/20 dark:bg-slate-900">
                        <div>
                            <span class="block text-sm font-medium text-slate-900 dark:text-white">Requires Approval</span>
                            <span class="text-xs text-slate-500 dark:text-white/50">Manager must approve this leave request</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">
                        <input type="checkbox" wire:model="form.requires_attachment" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/20 dark:bg-slate-900">
                        <div>
                            <span class="block text-sm font-medium text-slate-900 dark:text-white">Requires Attachment</span>
                            <span class="text-xs text-slate-500 dark:text-white/50">Supporting document is required</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-white/10 dark:hover:bg-white/5">
                        <input type="checkbox" wire:model="form.is_active" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/20 dark:bg-slate-900">
                        <div>
                            <span class="block text-sm font-medium text-slate-900 dark:text-white">Active</span>
                            <span class="text-xs text-slate-500 dark:text-white/50">Available for leave requests</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($leaveType) && $leaveType)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($leaveType->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($leaveType->createdBy)->name ?? 'System' }}
                </p>
                @if ($leaveType->updated_at)
                    <p>
                        Last updated {{ optional($leaveType->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($leaveType->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.leave-types') }}"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Type' }}</span>
            </button>
        </div>
    </div>
</div>
