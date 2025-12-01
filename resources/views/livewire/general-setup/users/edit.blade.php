<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="User Information"
            description="Update user account details and password."
        />

        {{-- Form Card --}}
        <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="space-y-5 px-6 py-5">
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
                            :disabled="$editingUser && $editingUser->employee_id"
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

            {{-- Role --}}
            <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Role
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <div class="md:col-span-6">
                        <label for="role_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                            Primary Role <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-2">
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
                        <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                            Each user has one primary role that controls their access.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Change Password
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input
                        label="New Password"
                        type="password"
                        model="password"
                        placeholder="Minimum 8 characters"
                        col-span="6"
                        helper="Leave blank to keep the current password."
                    />

                    <x-form.input
                        label="Confirm Password"
                        type="password"
                        model="password_confirmation"
                        placeholder="Re-enter password"
                        col-span="6"
                    />
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
            @if ($editingUser)
                <div class="text-[11px] text-slate-400 dark:text-white/40">
                    <p>
                        Created {{ optional($editingUser->created_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($editingUser->createdBy)->name ?? 'System' }}
                    </p>
                    @if ($editingUser->updated_at)
                        <p>
                            Last updated {{ optional($editingUser->updated_at)->format(config('basa.datetime_format')) }}
                            by {{ optional($editingUser->updatedBy)->name ?? 'System' }}
                        </p>
                    @endif
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 md:ml-auto">
                <button
                    type="button"
                    wire:click="cancel"
                    class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
                >
                    Cancel
                </button>
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
                    <span>Save Changes</span>
                </button>
            </div>
        </div>
    </div>
</div>
