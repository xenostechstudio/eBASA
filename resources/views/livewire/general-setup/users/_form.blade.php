@php /** @var bool $isEditing */ @endphp

<div class="space-y-5 px-6 py-5 overflow-y-auto">
    <div class="space-y-3">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Employee
        </p>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="employee_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Assign to Employee (optional)
                </label>
                <select
                    id="employee_id"
                    wire:model="employee_id"
                    class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                >
                    <option value="">— Not linked to employee —</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->code }} — {{ $employee->full_name }} ({{ $employee->email }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                    When an employee is selected, full name and email will be filled automatically and cannot be edited.
                </p>
            </div>
        </div>
    </div>

    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Account Information
        </p>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Full Name
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    autocomplete="name"
                    @if($this->employee_id) disabled @endif
                    class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40 disabled:cursor-not-allowed disabled:bg-slate-100/80 dark:disabled:bg-white/10"
                    placeholder="e.g. John Doe"
                >
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-1">
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    wire:model="email"
                    autocomplete="email"
                    @if($this->employee_id) disabled @endif
                    class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40 disabled:cursor-not-allowed disabled:bg-slate-100/80 dark:disabled:bg-white/10"
                    placeholder="name@example.com"
                >
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Role
        </p>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-1">
                <label for="role_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Primary Role <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <x-form.searchable-select
                        name="role_id"
                        id="role_id"
                        wire:model.live="role_id"
                        :options="$roles"
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

    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Security
        </p>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-1">
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Password
                    @if ($isEditing)
                        <span class="font-normal text-slate-400 dark:text-white/40">(leave blank to keep current)</span>
                    @endif
                </label>
                <input
                    type="password"
                    id="password"
                    wire:model="password"
                    autocomplete="new-password"
                    class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="Minimum 8 characters"
                >
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                    Use at least 8 characters with a mix of letters and numbers.
                </p>
            </div>

            <div class="md:col-span-1">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Confirm Password
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    class="mt-1 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="Re-type password"
                >
                <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                    Make sure this matches the password above.
                </p>
            </div>
        </div>
    </div>
</div>

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
            wire:click="closeModal"
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
