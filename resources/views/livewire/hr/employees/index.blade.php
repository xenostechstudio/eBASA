<div class="space-y-10">
    <x-module.heading
        tagline="HR · Employees"
        title="Employee Directory"
        description="Browse all people across branches with sorting, filtering, and creation wizard."
    >
        <x-slot:actions>
            <x-ui.button variant="secondary">Export view</x-ui.button>
            <x-ui.button as="a" href="{{ route('hr.employees.create') }}">New employee</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <section class="grid gap-4 md:grid-cols-4">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Total</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-white/60">Records</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-emerald-300/70">Active</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['active'] }}</p>
            <p class="text-xs text-white/60">In-good-standing employees</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-amber-300/80">On leave</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['on_leave'] }}</p>
            <p class="text-xs text-white/60">Currently offline</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
            <p class="text-xs uppercase tracking-[0.35em] text-sky-300/80">Probation</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $stats['probation'] }}</p>
            <p class="text-xs text-white/60">Under evaluation</p>
        </div>
    </section>

    <section class="rounded-[28px] border border-white/10 bg-white/5 overflow-hidden">
        <div class="px-6 pt-4 pb-4 space-y-4">
            @php($selectedCount = count($selectedEmployees))
            <div class="flex flex-wrap items-center justify-between gap-4">
                @if ($selectedCount > 0)
                    <div class="flex flex-wrap items-center gap-3 text-xs text-white/70">
                        <div class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-3 py-1.5">
                            <span>
                                <span class="font-semibold text-white">{{ $selectedCount }}</span>
                                {{ \Illuminate\Support\Str::plural('selected on this page', $selectedCount) }}
                            </span>
                            <div class="relative" data-dropdown>
                                <button
                                    type="button"
                                    data-dropdown-trigger
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-white/20 bg-white/5 text-white/70 hover:bg-white/10 hover:text-white"
                                    aria-label="Selection actions"
                                >
                                    @svg('heroicon-o-ellipsis-vertical', 'h-4 w-4')
                                </button>
                                <div
                                    data-dropdown-panel
                                    class="absolute right-0 z-40 mt-2 hidden min-w-[9rem] rounded-xl border border-white/10 bg-slate-900/95 p-1 text-xs text-white shadow-xl"
                                >
                                    <button
                                        type="button"
                                        wire:click="bulkDelete"
                                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-white/10"
                                    >
                                        @svg('heroicon-o-trash', 'h-4 w-4')
                                        <span>Delete selected</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="selectAllOnPage"
                            class="text-white/80 underline-offset-2 hover:text-white hover:underline"
                        >
                            Select all
                        </button>
                        <button
                            type="button"
                            wire:click="clearSelection"
                            class="text-white/80 underline-offset-2 hover:text-white hover:underline"
                        >
                            Deselect all
                        </button>
                    </div>
                @endif
                <div class="flex items-center gap-3 ml-auto">
                    <x-table.search wire:model.debounce.300ms="search" placeholder="Search people" />
                    <x-table.filter-dropdown
                        label="Status"
                        :options="['all' => 'All', 'active' => 'Active', 'on_leave' => 'On leave', 'probation' => 'Probation']"
                        :selected="$statusFilter"
                        on-select="setStatusFilter"
                    />
                    <x-table.column-dropdown :column-visibility="$columnVisibility" reset-action="resetColumns" />
                </div>
            </div>
        </div>

        <div class="-mx-6 -mb-6 overflow-hidden border-t border-white/10 bg-slate-900/40 pb-6">
            <x-table.list
                :employees="$employees"
                :column-visibility="$columnVisibility"
                :sort-field="$sortField"
                :sort-direction="$sortDirection"
                :select-page="$selectPage"
                :selected-employees="$selectedEmployees"
            />

            <div class="px-6">
                <x-table.pagination :paginator="$employees" :per-page-options="$perPageOptions" />
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Workflow</p>
                    <h3 class="text-lg font-semibold text-white">Multi-step onboarding</h3>
                </div>
                <div class="flex gap-1">
                    @for ($step = 1; $step <= 3; $step++)
                        <button wire:click="goToStep({{ $step }})"
                            class="h-2 w-8 rounded-full {{ $formStep >= $step ? 'bg-white' : 'bg-white/20' }}"></button>
                    @endfor
                </div>
            </div>
            <div class="mt-5 space-y-4">
                @if ($formStep === 1)
                    <div class="space-y-3">
                        <input type="text" placeholder="Legal name"
                            class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80 placeholder:text-white/40" />
                        <input type="email" placeholder="Work email"
                            class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80 placeholder:text-white/40" />
                        <input type="text" placeholder="Employee code"
                            class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80 placeholder:text-white/40" />
                    </div>
                @elseif ($formStep === 2)
                    <div class="space-y-3">
                        <select class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80">
                            <option>Choose branch</option>
                            @foreach ($branches as $branch)
                                <option>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        <select class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80">
                            <option>Choose department</option>
                            @foreach ($departments as $department)
                                <option>{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <select class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80">
                            <option>Choose position</option>
                            @foreach ($positions as $position)
                                <option>{{ $position->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="space-y-3">
                        <input type="date" class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80" />
                        <textarea placeholder="Notes or onboarding expectations"
                            class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80 placeholder:text-white/40"></textarea>
                        <div class="flex gap-3">
                            <button wire:click="previousStep" class="rounded-full border border-white/20 px-4 py-2 text-sm text-white/70">Back</button>
                            <button wire:click="saveDraft" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-900">Save draft</button>
                        </div>
                    </div>
                @endif
                <div class="flex gap-3" x-data>
                    <button wire:click="previousStep" class="rounded-full border border-white/20 px-4 py-2 text-sm text-white/70">Prev</button>
                    <button wire:click="nextStep" class="rounded-full border border-white/20 px-4 py-2 text-sm text-white/70">Next</button>
                </div>
            </div>
        </div>

        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Insights</p>
            <h3 class="mt-2 text-lg font-semibold text-white">Department mix</h3>
            <div class="mt-4 space-y-3">
                @foreach ($departments->take(6) as $department)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white/80">{{ $department->name }}</p>
                            <p class="text-xs text-white/40">{{ $department->employees_count ?? $employees->where('department_id', $department->id)->count() }} employees</p>
                        </div>
                        <div class="h-2 w-24 rounded-full bg-white/10">
                            <div class="h-2 rounded-full bg-white/70" style="width: {{ rand(20, 90) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Policy</p>
                <p class="mt-1 text-sm text-white/70">Use this directory for quick lookups and tactical decisions. For record-grade actions, deep dive in Filament once ready.</p>
            </div>
        </div>
    </section>
</div>
