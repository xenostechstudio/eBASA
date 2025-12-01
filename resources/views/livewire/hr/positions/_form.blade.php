@php /** @var bool $isEditing */ @endphp

<div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="space-y-5 px-6 py-5">
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Position Identity --}}
            <div class="space-y-3 md:border-r md:border-dashed md:border-slate-200 md:pr-6 dark:md:border-white/10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Position Identity
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Position Code" model="form.code" placeholder="e.g. POS-01" col-span="6" :required="true" />
                    <x-form.input label="Title" model="form.title" placeholder="e.g. Store Manager" col-span="6" :required="true" />
                    <x-form.input label="Level" model="form.level" placeholder="e.g. M3, P2" col-span="6" />
                    <x-form.input label="Job Family" model="form.job_family" placeholder="e.g. Operations" col-span="6" />

                    <div class="col-span-12 flex items-center gap-2">
                        <input
                            id="is_people_manager"
                            type="checkbox"
                            wire:model="form.is_people_manager"
                            class="h-4 w-4 rounded border-slate-300 bg-white text-slate-900 focus:ring-0 dark:border-white/30 dark:bg-slate-900 dark:text-white"
                        >
                        <label for="is_people_manager" class="text-sm text-slate-600 dark:text-white/80">This is a people manager role</label>
                    </div>
                </div>
            </div>

            {{-- Placement --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Placement
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.select label="Branch" model="form.branch_id" placeholder="Select branch" col-span="12" :required="true">
                        @foreach ($branches as $branch)
                            <option value="{{ $branch['id'] }}" class="bg-white dark:bg-slate-900">{{ $branch['name'] }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Department" model="form.department_id" placeholder="Select department" col-span="12">
                        @foreach ($departments as $department)
                            <option value="{{ $department['id'] }}" class="bg-white dark:bg-slate-900">{{ $department['name'] }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.input label="Description" model="form.description" :textarea="true" rows="3" placeholder="Responsibilities, scope, and reporting line." col-span="12" />
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($position) && $position)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($position->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($position->createdBy)->name ?? 'System' }}
                </p>
                @if ($position->updated_at)
                    <p>
                        Last updated {{ optional($position->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($position->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.positions') }}"
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
                <span>{{ $isEditing ? 'Save Changes' : 'Create Position' }}</span>
            </button>
        </div>
    </div>
</div>
