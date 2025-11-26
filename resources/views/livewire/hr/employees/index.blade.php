<x-module.section as="section" padding="none" rounded="none" :border="false" background-class="" gap="sm">
    <x-module.heading tagline="HR · Employees" title="Employee Directory"
        description="Browse all people across branches with sorting, filtering, and creation wizard.">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <div class="relative" data-dropdown>
                    <button type="button" data-dropdown-trigger
                        class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 dark:border-white/20 dark:bg-white/5 dark:text-white/80 dark:hover:border-white/40 dark:hover:bg-white/10 dark:hover:text-white">
                        @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                        <span>Export</span>
                        @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400 dark:text-white/60')
                    </button>
                    <div data-dropdown-panel
                        class="absolute right-0 z-40 mt-2 hidden min-w-[11rem] rounded-xl border border-slate-200 bg-white p-1 text-xs text-slate-700 shadow-xl dark:border-white/10 dark:bg-slate-900/95 dark:text-white">
                        <button type="button" wire:click="export('pdf')"
                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-white/10">
                            @svg('heroicon-o-document-text', 'h-4 w-4')
                            <span>Export as PDF</span>
                        </button>
                        <button type="button" wire:click="export('excel')"
                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-slate-100 dark:hover:bg-white/10">
                            @svg('heroicon-o-table-cells', 'h-4 w-4')
                            <span>Export as Excel</span>
                        </button>
                    </div>
                </div>
                <x-ui.button as="a" href="{{ route('hr.employees.create') }}">New employee</x-ui.button>
            </div>
        </x-slot:actions>
    </x-module.heading>

    <x-module.section as="section" padding="none" rounded="none" :border="false" background-class="" gap="none"
        class="grid gap-3 md:grid-cols-4">
        <div
            class="flex h-full flex-col justify-between rounded-3xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-xs uppercase tracking-[0.35em] text-slate-400 dark:text-white/40">Total</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-500 dark:text-white/60">Records</p>
        </div>
        <div
            class="flex h-full flex-col justify-between rounded-3xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-xs uppercase tracking-[0.35em] text-emerald-600 dark:text-emerald-300/70">Active</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['active'] }}</p>
            <p class="text-xs text-slate-500 dark:text-white/60">In-good-standing employees</p>
        </div>
        <div
            class="flex h-full flex-col justify-between rounded-3xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-xs uppercase tracking-[0.35em] text-amber-600 dark:text-amber-300/80">On leave</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['on_leave'] }}</p>
            <p class="text-xs text-slate-500 dark:text-white/60">Currently offline</p>
        </div>
        <div
            class="flex h-full flex-col justify-between rounded-3xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-xs uppercase tracking-[0.35em] text-sky-600 dark:text-sky-300/80">Probation</p>
            <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['probation'] }}</p>
            <p class="text-xs text-slate-500 dark:text-white/60">Under evaluation</p>
        </div>
    </x-module.section>

    <x-module.section as="section" padding="none" rounded="3xl" gap="sm"
        border-class="border border-slate-200 dark:border-white/10"
        background-class="bg-white overflow-hidden dark:bg-white/5">
        <div class="px-5 pt-3">
            @php($selectedCount = count($selectedEmployees))
            <div class="flex flex-wrap items-center justify-between gap-4">
                <x-table.selection-summary :count="$selectedCount" delete-action="bulkDelete"
                    select-all-action="selectAllOnPage" deselect-action="clearSelection" />
                <div class="flex items-center gap-3 ml-auto">
                    <x-table.search wire:model.debounce.300ms="search" placeholder="Search people" />
                    <x-table.filter-dropdown label="Status" :options="[
                        'all' => 'All',
                        'active' => 'Active',
                        'on_leave' => 'On leave',
                        'probation' => 'Probation',
                    ]" :selected="$statusFilter"
                        on-select="setStatusFilter" />
                    <x-table.column-dropdown :column-visibility="$columnVisibility" reset-action="resetColumns" />

                    {{-- View Type Toggle --}}
                    <button type="button" wire:click="toggleViewType"
                        class="inline-flex h-7 w-7 items-center justify-center text-slate-500 transition hover:text-slate-800 dark:text-white/70 dark:hover:text-white"
                        aria-label="Toggle view">
                        @if ($viewType === 'grid')
                            @svg('heroicon-s-list-bullet', 'h-8 w-8')
                        @else
                            @svg('heroicon-s-squares-2x2', 'h-8 w-8')
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <div
            class="-mx-5 -mb-5 overflow-hidden border-t border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-slate-900/40">
            <x-table.list :employees="$employees" :column-visibility="$columnVisibility" :sort-field="$sortField" :sort-direction="$sortDirection" :select-page="$selectPage"
                :selected-employees="$selectedEmployees" />

            <div class="px-6">
                <x-table.pagination :paginator="$employees" :per-page-options="$perPageOptions" />
            </div>
        </div>
    </x-module.section>
</x-module.section>
