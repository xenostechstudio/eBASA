<div>
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6" x-data="{ activeTab: 'details' }">
        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">HR · Positions</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ $position->title }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/60">
                    <span class="font-mono">{{ $position->code }}</span>
                    @if($position->is_people_manager)
                        <span class="ml-2 inline-flex items-center rounded-full bg-violet-100 px-2 py-0.5 text-xs font-medium text-violet-700 dark:bg-violet-500/20 dark:text-violet-400">Manager Role</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hr.positions') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                    @svg('heroicon-o-arrow-left', 'h-4 w-4')
                    <span>Back</span>
                </a>
                <button wire:click="save"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-check', 'h-4 w-4')
                    <span>Save Changes</span>
                </button>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat.card label="Department" :value="$this->stats['department']" description="Assignment" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-building-office-2', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Branch" :value="$this->stats['branch']" description="Location" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-map-pin', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Employees" :value="number_format($this->stats['employeesCount'])" description="In this position" tone="info">
                <x-slot:icon>@svg('heroicon-o-users', 'h-5 w-5 text-sky-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Active" :value="number_format($this->stats['activeEmployees'])" description="Currently active" tone="success">
                <x-slot:icon>@svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')</x-slot:icon>
            </x-stat.card>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-slate-200 dark:border-white/10">
            <nav class="-mb-px flex gap-6">
                <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Position Details
                </button>
                <button @click="activeTab = 'employees'" :class="activeTab === 'employees' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Employees ({{ $this->stats['employeesCount'] }})
                </button>
            </nav>
        </div>

        {{-- Tab: Position Details --}}
        <div x-show="activeTab === 'details'" x-cloak>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Position Information</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Core details for this position.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Position Code</label>
                        <input type="text" wire:model="form.code"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('form.code') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Position Title</label>
                        <input type="text" wire:model="form.title"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('form.title') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Department</label>
                        <select wire:model="form.department_id"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select department...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                            @endforeach
                        </select>
                        @error('form.department_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Branch</label>
                        <select wire:model="form.branch_id"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select branch...</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                            @endforeach
                        </select>
                        @error('form.branch_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Level</label>
                        <input type="text" wire:model="form.level" placeholder="e.g. Senior, Junior, Lead"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Job Family</label>
                        <input type="text" wire:model="form.job_family" placeholder="e.g. Engineering, Sales"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <button type="button" wire:click="$set('form.is_people_manager', !{{ $form['is_people_manager'] ? 'true' : 'false' }})"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $form['is_people_manager'] ? 'bg-violet-500' : 'bg-slate-200 dark:bg-slate-700' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $form['is_people_manager'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                        <span class="text-sm text-slate-700 dark:text-white/70">People Manager Role</span>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Description</label>
                        <textarea wire:model="form.description" rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Employees (Relation Manager) --}}
        <div x-show="activeTab === 'employees'" x-cloak>
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Employees</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-white/50">Team members holding this position.</p>
                    </div>
                </div>

                <div class="border-b border-slate-200 px-6 py-3 dark:border-white/10">
                    <input type="text" wire:model.live.debounce.300ms="employeesSearch" placeholder="Search employees..."
                        class="w-full max-w-xs rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                            <tr>
                                <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Employee</th>
                                <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Department</th>
                                <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Branch</th>
                                <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Status</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                            @forelse ($this->employees as $emp)
                                <tr wire:click="goToEmployee({{ $emp->id }})" class="cursor-pointer hover:bg-slate-50 dark:hover:bg-white/5">
                                    <td class="px-6 py-3">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $emp->full_name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $emp->code }}</p>
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 dark:text-white/70">
                                        {{ $emp->department?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 dark:text-white/70">
                                        {{ $emp->branch?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                            {{ match($emp->status) {
                                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                                'on_leave' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                                'probation' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                                default => 'bg-slate-100 text-slate-600 dark:bg-slate-500/20 dark:text-slate-400'
                                            } }}">
                                            {{ ucfirst(str_replace('_', ' ', $emp->status ?? 'unknown')) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-400')
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            @svg('heroicon-o-users', 'h-10 w-10 text-slate-300 dark:text-white/20')
                                            <p class="mt-3 text-sm text-slate-500 dark:text-white/50">No employees in this position yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($this->employees->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                        {{ $this->employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
