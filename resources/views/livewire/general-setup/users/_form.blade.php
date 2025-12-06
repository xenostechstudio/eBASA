@php /** @var bool $isEditing */ @endphp

<div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="space-y-5 px-6 py-5">
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Left Column: Account Information --}}
            <div class="space-y-5 lg:border-r lg:border-dashed lg:border-slate-200 lg:pr-6 dark:lg:border-white/10">
                {{-- Link to Employee --}}
                <div class="space-y-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Link to Employee
                    </p>

                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                            Employee
                            <span class="font-normal text-slate-400 dark:text-white/40">(optional)</span>
                        </label>
                        <div class="mt-1">
                            <x-form.searchable-select
                                name="employee_id"
                                id="employee_id"
                                wire:model.live="employee_id"
                                :options="$this->employees"
                                label-key="full_name"
                                sublabel-key="code"
                                placeholder="— No employee link —"
                                :disabled="$isEditing && isset($editingUser) && $editingUser->employee_id"
                            />
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                            Linking to an employee auto-fills name and email.
                        </p>
                        @error('employee_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Account Details --}}
                <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Account Details
                    </p>

                    <div class="grid gap-4 md:grid-cols-12">
                        <x-form.input
                            label="Full Name"
                            model="name"
                            placeholder="e.g. John Doe"
                            col-span="6"
                            :required="true"
                            :disabled="(bool) $employee_id"
                        />

                        <x-form.input
                            label="Email Address"
                            type="email"
                            model="email"
                            placeholder="e.g. john@example.com"
                            col-span="6"
                            :required="true"
                            :disabled="(bool) $employee_id"
                        />
                    </div>
                </div>

                {{-- Role & Password --}}
                <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Role & Security
                    </p>

                    <div>
                        <label for="role_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                            Primary Role <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <x-form.searchable-select
                                name="role_id"
                                id="role_id"
                                wire:model.live="role_id"
                                :options="$this->roles"
                                value-key="id"
                                label-key="name"
                                placeholder="Select role"
                            />
                        </div>
                        @error('role_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-12">
                        <x-form.input
                            label="{{ $isEditing ? 'New Password' : 'Password' }}"
                            type="password"
                            model="password"
                            placeholder="Minimum 8 characters"
                            col-span="6"
                            :required="!$isEditing"
                            :helper="$isEditing ? 'Leave blank to keep current password.' : null"
                        />

                        <x-form.input
                            label="Confirm Password"
                            type="password"
                            model="password_confirmation"
                            placeholder="Re-enter password"
                            col-span="6"
                            :required="!$isEditing"
                        />
                    </div>
                </div>
            </div>

            {{-- Right Column: Branch Access --}}
            <div class="space-y-5">
                <div class="space-y-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Branch Access
                    </p>

                    {{-- Access Type Cards --}}
                    <div class="grid gap-3 sm:grid-cols-2">
                        {{-- All Branches Card --}}
                        <label
                            class="relative flex cursor-pointer rounded-xl border-2 p-4 transition-all
                                {{ $branch_access_type === 'all'
                                    ? 'border-emerald-500 bg-emerald-50 dark:border-emerald-400 dark:bg-emerald-500/10'
                                    : 'border-slate-200 bg-white hover:border-slate-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20' }}"
                        >
                            <input
                                type="radio"
                                wire:model.live="branch_access_type"
                                value="all"
                                class="sr-only"
                            >
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $branch_access_type === 'all' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-white/60' }}">
                                    @svg('heroicon-o-globe-alt', 'h-5 w-5')
                                </div>
                                <div>
                                    <p class="text-sm font-semibold {{ $branch_access_type === 'all' ? 'text-emerald-900 dark:text-emerald-100' : 'text-slate-700 dark:text-white/80' }}">
                                        All Branches
                                    </p>
                                    <p class="mt-0.5 text-xs {{ $branch_access_type === 'all' ? 'text-emerald-700 dark:text-emerald-200' : 'text-slate-500 dark:text-white/50' }}">
                                        Access to all branches in the system
                                    </p>
                                </div>
                            </div>
                            @if ($branch_access_type === 'all')
                                <div class="absolute right-3 top-3">
                                    @svg('heroicon-s-check-circle', 'h-5 w-5 text-emerald-500 dark:text-emerald-400')
                                </div>
                            @endif
                        </label>

                        {{-- Selected Branches Card --}}
                        <label
                            class="relative flex cursor-pointer rounded-xl border-2 p-4 transition-all
                                {{ $branch_access_type === 'selected'
                                    ? 'border-blue-500 bg-blue-50 dark:border-blue-400 dark:bg-blue-500/10'
                                    : 'border-slate-200 bg-white hover:border-slate-300 dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20' }}"
                        >
                            <input
                                type="radio"
                                wire:model.live="branch_access_type"
                                value="selected"
                                class="sr-only"
                            >
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $branch_access_type === 'selected' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-white/60' }}">
                                    @svg('heroicon-o-building-storefront', 'h-5 w-5')
                                </div>
                                <div>
                                    <p class="text-sm font-semibold {{ $branch_access_type === 'selected' ? 'text-blue-900 dark:text-blue-100' : 'text-slate-700 dark:text-white/80' }}">
                                        Selected Branches
                                    </p>
                                    <p class="mt-0.5 text-xs {{ $branch_access_type === 'selected' ? 'text-blue-700 dark:text-blue-200' : 'text-slate-500 dark:text-white/50' }}">
                                        Access only to specific branches
                                    </p>
                                </div>
                            </div>
                            @if ($branch_access_type === 'selected')
                                <div class="absolute right-3 top-3">
                                    @svg('heroicon-s-check-circle', 'h-5 w-5 text-blue-500 dark:text-blue-400')
                                </div>
                            @endif
                        </label>
                    </div>
                    @error('branch_access_type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Branch Selection List --}}
                @if ($branch_access_type === 'selected')
                    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                                Select Branches
                            </p>
                            <span class="text-xs text-slate-500 dark:text-white/50">
                                {{ count($selected_branch_ids) }} selected
                            </span>
                        </div>

                        <div class="max-h-64 space-y-2 overflow-y-auto rounded-xl border border-slate-200 p-3 dark:border-white/10">
                            @forelse ($this->branches as $branch)
                                @php
                                    $isSelected = in_array($branch->id, $selected_branch_ids);
                                @endphp
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-lg p-2.5 transition
                                        {{ $isSelected
                                            ? 'bg-blue-50 dark:bg-blue-500/10'
                                            : 'hover:bg-slate-50 dark:hover:bg-white/5' }}"
                                >
                                    <input
                                        type="checkbox"
                                        wire:model="selected_branch_ids"
                                        value="{{ $branch->id }}"
                                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-white/20 dark:bg-white/5"
                                    >
                                    <div class="flex flex-1 items-center justify-between">
                                        <div>
                                            <span class="text-sm font-medium {{ $isSelected ? 'text-blue-900 dark:text-blue-100' : 'text-slate-700 dark:text-white/80' }}">
                                                {{ $branch->name }}
                                            </span>
                                            <span class="ml-2 text-xs {{ $isSelected ? 'text-blue-600 dark:text-blue-300' : 'text-slate-400 dark:text-white/40' }}">
                                                {{ $branch->code }}
                                            </span>
                                        </div>
                                        @if ($branch->city)
                                            <span class="text-xs {{ $isSelected ? 'text-blue-500 dark:text-blue-300' : 'text-slate-400 dark:text-white/40' }}">
                                                {{ $branch->city }}
                                            </span>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <div class="flex flex-col items-center justify-center py-8 text-center">
                                    @svg('heroicon-o-building-storefront', 'h-10 w-10 text-slate-300 dark:text-white/20')
                                    <p class="mt-2 text-sm text-slate-500 dark:text-white/50">No branches available</p>
                                    <p class="text-xs text-slate-400 dark:text-white/30">Create branches first in General Setup</p>
                                </div>
                            @endforelse
                        </div>
                        @error('selected_branch_ids') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                @else
                    {{-- All Branches Info --}}
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-500/30 dark:bg-emerald-500/10">
                        <div class="flex gap-3">
                            @svg('heroicon-o-information-circle', 'h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400')
                            <div>
                                <p class="text-sm font-medium text-emerald-900 dark:text-emerald-100">
                                    Full System Access
                                </p>
                                <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-200">
                                    This user will have access to all branches in the system, including any new branches created in the future.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Footer with Auditing Info --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($editingUser) && $editingUser)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created
                    <span class="font-medium text-slate-500 dark:text-white/60">
                        {{ optional($editingUser->created_at)->format(config('basa.datetime_format')) }}
                    </span>
                    @if ($editingUser->createdBy)
                        by
                        <span class="font-medium text-slate-600 dark:text-white/80">
                            {{ $editingUser->createdBy->name }}
                        </span>
                    @endif
                </p>
                <p>
                    Last updated
                    <span class="font-medium text-slate-500 dark:text-white/60">
                        {{ optional($editingUser->updated_at)->format(config('basa.datetime_format')) }}
                    </span>
                    @if ($editingUser->updatedBy)
                        by
                        <span class="font-medium text-slate-600 dark:text-white/80">
                            {{ $editingUser->updatedBy->name }}
                        </span>
                    @endif
                </p>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <x-button.secondary
                type="button"
                wire:click="cancel"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create User' }}</span>
            </x-button.primary>
        </div>
    </div>
</div>
