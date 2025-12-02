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
                    {{-- Code --}}
                    <x-form.input
                        label="Branch Code"
                        model="code"
                        placeholder="e.g. BR-001"
                        helper="Unique identifier for this branch."
                        col-span="6"
                        :required="true"
                    />

                    {{-- Name --}}
                    <x-form.input
                        label="Branch Name"
                        model="name"
                        placeholder="e.g. Main Store"
                        col-span="6"
                        :required="true"
                    />
                </div>

                <div class="grid gap-4 md:grid-cols-12">
                    {{-- City --}}
                    <x-form.input
                        label="City"
                        model="city"
                        placeholder="e.g. Jakarta"
                        col-span="6"
                    />

                    {{-- Province --}}
                    <x-form.input
                        label="Province"
                        model="province"
                        placeholder="e.g. DKI Jakarta"
                        col-span="6"
                    />
                </div>

                {{-- Address --}}
                <x-form.input
                    label="Full Address"
                    model="address"
                    placeholder="Street address, building, etc."
                    :textarea="true"
                    rows="2"
                />
            </div>

            {{-- Contact Information --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Contact Information
                </p>

                {{-- Manager Name --}}
                <div>
                    <label for="manager_name" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                        Manager Name
                    </label>
                    <div class="mt-1">
                        <x-form.searchable-select
                            name="manager_employee_id"
                            id="manager_name"
                            wire:model.live="manager_employee_id"
                            :options="$this->managers"
                            label-key="full_name"
                            sublabel-key="code"
                            placeholder="Select manager"
                        />
                    </div>
                    @error('manager_employee_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-12">
                    {{-- Phone --}}
                    <x-form.input
                        label="Phone"
                        model="phone"
                        placeholder="e.g. +62 21 1234567"
                        col-span="6"
                    />

                    {{-- Email --}}
                    <x-form.input
                        label="Email"
                        type="email"
                        model="email"
                        placeholder="e.g. branch@example.com"
                        col-span="6"
                    />
                </div>
            </div>
        </div>

        {{-- Status --}}
        @php
            $isActive = (bool) ($is_active ?? false);
            $statusCardClasses = $isActive
                ? 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-100'
                : 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-100';
            $statusBadgeClasses = $isActive
                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200'
                : 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200';
        @endphp

        <div class="mt-2 rounded-2xl border px-4 py-3 transition-colors {{ $statusCardClasses }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em]">
                        Status
                    </p>
                    <p class="mt-1 text-xs sm:text-sm">
                        {{ $isActive
                            ? 'This branch is active and can be used for transactions, inventory operations, and reporting.'
                            : 'This branch is inactive. It will be hidden from normal branch selection and excluded from day-to-day operations.' }}
                    </p>
                </div>

                <div class="flex flex-col items-start gap-2 sm:items-end">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium {{ $statusBadgeClasses }}">
                        {{ $isActive ? 'Active' : 'Inactive' }}
                    </span>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="is_active"
                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/30 dark:bg-white/10"
                        >
                        <span class="text-xs font-medium">
                            {{ $isActive ? 'Turn off to temporarily close this branch' : 'Turn on to reactivate this branch' }}
                        </span>
                    </label>
                </div>
            </div>

            @error('is_active') <p class="mt-2 text-xs text-rose-200 sm:text-rose-500">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Footer with Auditing Info --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($editingBranch) && $editingBranch)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created
                    <span class="font-medium text-slate-500 dark:text-white/60">
                        {{ optional($editingBranch->created_at)->format(config('basa.datetime_format')) }}
                    </span>
                    @if ($editingBranch->createdBy)
                        by
                        <span class="font-medium text-slate-600 dark:text-white/80">
                            {{ $editingBranch->createdBy->name }}
                        </span>
                    @endif
                </p>
                <p>
                    Last updated
                    <span class="font-medium text-slate-500 dark:text-white/60">
                        {{ optional($editingBranch->updated_at)->format(config('basa.datetime_format')) }}
                    </span>
                    @if ($editingBranch->updatedBy)
                        by
                        <span class="font-medium text-slate-600 dark:text-white/80">
                            {{ $editingBranch->updatedBy->name }}
                        </span>
                    @endif
                </p>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <x-button.secondary
                type="button"
                wire:click="{{ $isEditing ? 'cancel' : '' }}"
                :href="$isEditing ? null : route('general-setup.branches.index')"
                :tag="$isEditing ? 'button' : 'a'"
            >
                Cancel
            </x-button.secondary>

            <x-button.primary
                type="button"
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                class="h-10"
            >
                <span wire:loading.remove wire:target="save">
                    @svg('heroicon-o-check', 'h-4 w-4')
                </span>
                <span wire:loading wire:target="save">
                    @svg('heroicon-o-arrow-path', 'h-4 w-4 animate-spin')
                </span>
                <span>{{ $isEditing ? 'Save Changes' : 'Create Branch' }}</span>
            </x-button.primary>
        </div>
    </div>
</div>
