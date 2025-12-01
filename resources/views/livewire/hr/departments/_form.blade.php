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
                    <x-form.input label="Department Code" model="form.code" placeholder="e.g. OPS-01" col-span="6" :required="true" />
                    <x-form.input label="Department Name" model="form.name" placeholder="e.g. Operations" col-span="6" :required="true" />

                    <x-form.select label="Branch" model="form.branch_id" placeholder="Select branch" col-span="12" :required="true">
                        @foreach ($branches as $branch)
                            <option value="{{ $branch['id'] }}" class="bg-white dark:bg-slate-900">{{ $branch['name'] }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Parent Department" model="form.parent_id" placeholder="No parent (top-level)" col-span="12">
                        @foreach ($departments as $dept)
                            <option value="{{ $dept['id'] }}" class="bg-white dark:bg-slate-900">{{ $dept['name'] }}</option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>

            {{-- Leadership --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Leadership
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Lead Name" model="form.lead_name" placeholder="e.g. Maya Rahma" col-span="6" />
                    <x-form.input label="Lead Email" type="email" model="form.lead_email" placeholder="lead@company.com" col-span="6" />
                    <x-form.input label="Description" model="form.description" :textarea="true" rows="3" placeholder="Responsibilities, scope, focus areas." col-span="12" />
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($department) && $department)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($department->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($department->createdBy)->name ?? 'System' }}
                </p>
                @if ($department->updated_at)
                    <p>
                        Last updated {{ optional($department->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($department->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.departments') }}"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Department' }}</span>
            </button>
        </div>
    </div>
</div>
