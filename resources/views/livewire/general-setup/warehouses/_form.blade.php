@php /** @var bool $isEditing */ @endphp

<div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="space-y-5 px-6 py-5">
        <div class="space-y-3">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                Warehouse Information
            </p>

            <div class="grid gap-4 md:grid-cols-12">
                <x-form.input
                    label="Code"
                    model="code"
                    placeholder="e.g. WH-TGL-01"
                    helper="Unique identifier for this warehouse."
                    col-span="4"
                    :required="true"
                />

                <x-form.input
                    label="Name"
                    model="name"
                    placeholder="e.g. Central Warehouse Tegal"
                    col-span="8"
                    :required="true"
                />
            </div>

            <div class="grid gap-4 md:grid-cols-12">
                <x-form.input
                    label="City"
                    model="city"
                    placeholder="e.g. Tegal"
                    col-span="4"
                />

                <x-form.input
                    label="Province"
                    model="province"
                    placeholder="e.g. Jawa Tengah"
                    col-span="4"
                />

                <x-form.input
                    label="Phone"
                    model="phone"
                    placeholder="e.g. 021-123456"
                    col-span="4"
                />
            </div>

            <x-form.input
                label="Address"
                model="address"
                placeholder="Street, district, city"
                helper="Full warehouse address for documents and reporting."
                :textarea="true"
                rows="3"
            />
        </div>

        <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                Linked Branch
            </p>

            <div class="grid gap-4 md:grid-cols-12">
                <div class="col-span-12 md:col-span-6">
                    <label for="branch_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                        Linked Branch
                    </label>
                    <div class="mt-2">
                        <x-form.searchable-select
                            name="branch_id"
                            id="branch_id"
                            wire:model.live="branch_id"
                            :options="$branches"
                            value-key="id"
                            label-key="name"
                            sublabel-key="code"
                            placeholder="All branches"
                        />
                    </div>
                    @error('branch_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
            <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                Contact & Status
            </p>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="contact_employee_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                        Contact Person
                    </label>
                    <div class="mt-1">
                        <x-form.searchable-select
                            name="contact_employee_id"
                            id="contact_employee_id"
                            wire:model.live="contact_employee_id"
                            :options="$this->employees"
                            label-key="full_name"
                            sublabel-key="code"
                            placeholder="Select contact person"
                        />
                    </div>
                    @error('contact_employee_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 md:mt-7">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="is_active"
                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                        >
                        <span class="text-sm font-medium text-slate-700 dark:text-white/80">
                            Warehouse is active and accepting stock
                        </span>
                    </label>
                    @error('is_active') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Footer with Auditing Info --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($editingWarehouse) && $editingWarehouse)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created
                    <span class="font-medium text-slate-500 dark:text-white/60">
                        {{ optional($editingWarehouse->created_at)->format(config('basa.datetime_format')) }}
                    </span>
                    @if ($editingWarehouse->createdBy)
                        by
                        <span class="font-medium text-slate-600 dark:text-white/80">
                            {{ $editingWarehouse->createdBy->name }}
                        </span>
                    @endif
                </p>
                <p>
                    Last updated
                    <span class="font-medium text-slate-500 dark:text-white/60">
                        {{ optional($editingWarehouse->updated_at)->format(config('basa.datetime_format')) }}
                    </span>
                    @if ($editingWarehouse->updatedBy)
                        by
                        <span class="font-medium text-slate-600 dark:text-white/80">
                            {{ $editingWarehouse->updatedBy->name }}
                        </span>
                    @endif
                </p>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <x-button.secondary
                type="button"
                wire:click="{{ $isEditing ? 'cancel' : '' }}"
                :href="$isEditing ? null : route('general-setup.warehouses.index')"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Warehouse' }}</span>
            </x-button.primary>
        </div>
    </div>
</div>
