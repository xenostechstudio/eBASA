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
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">HR · Employees</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ $employee->full_name }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/60">
                    <span class="font-mono">{{ $employee->code }}</span>
                    @php $status = $this->stats['status']; @endphp
                    <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                        {{ match($status) {
                            'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                            'on_leave' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                            'probation' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                            default => 'bg-slate-100 text-slate-600 dark:bg-slate-500/20 dark:text-slate-400'
                        } }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hr.employees') }}"
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
            <x-stat.card label="Department" :value="$this->stats['department']" description="Current assignment" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-building-office-2', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Position" :value="$this->stats['position']" description="Job title" tone="info">
                <x-slot:icon>@svg('heroicon-o-briefcase', 'h-5 w-5 text-sky-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Branch" :value="$this->stats['branch']" description="Work location" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-map-pin', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Tenure" :value="$this->stats['tenure']" description="Time with company" tone="success">
                <x-slot:icon>@svg('heroicon-o-clock', 'h-5 w-5 text-emerald-500')</x-slot:icon>
            </x-stat.card>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-slate-200 dark:border-white/10">
            <nav class="-mb-px flex gap-6">
                <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Personal Details
                </button>
                <button @click="activeTab = 'emergency'" :class="activeTab === 'emergency' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Emergency & Banking
                </button>
                <button @click="activeTab = 'employment'" :class="activeTab === 'employment' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Employment & Payroll
                </button>
            </nav>
        </div>

        {{-- Tab: Personal Details --}}
        <div x-show="activeTab === 'details'" x-cloak>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Personal Information</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Identity, contact & government IDs.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Full Name</label>
                        <input type="text" wire:model="form.full_name"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('form.full_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Preferred Name</label>
                        <input type="text" wire:model="form.preferred_name"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Employee Code</label>
                        <input type="text" wire:model="form.code"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('form.code') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Work Email</label>
                        <input type="email" wire:model="form.email"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('form.email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Phone</label>
                        <input type="text" wire:model="form.phone"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">WhatsApp Number</label>
                        <input type="text" wire:model="form.whatsapp_number"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Date of Birth</label>
                        <input type="date" wire:model="form.date_of_birth"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">NIK (National ID)</label>
                        <input type="text" wire:model="form.nik"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('form.nik') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">NPWP (Tax Number)</label>
                        <input type="text" wire:model="form.npwp"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Address</label>
                        <textarea wire:model="form.address" rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Emergency & Banking --}}
        <div x-show="activeTab === 'emergency'" x-cloak>
            <div class="space-y-6">
                {{-- Emergency Contact --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-300/20 dark:text-amber-300">
                            @svg('heroicon-o-shield-check', 'h-5 w-5')
                        </span>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Emergency Contact</h3>
                            <p class="text-sm text-slate-500 dark:text-white/50">Person to notify in case of emergency.</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Contact Name</label>
                            <input type="text" wire:model="form.emergency_contact_name"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Contact WhatsApp</label>
                            <input type="text" wire:model="form.emergency_contact_whatsapp"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Banking Details --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-300/20 dark:text-sky-300">
                            @svg('heroicon-o-credit-card', 'h-5 w-5')
                        </span>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Banking Details</h3>
                            <p class="text-sm text-slate-500 dark:text-white/50">Payroll destination account.</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Bank Name</label>
                            <input type="text" wire:model="form.bank_name"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Account Number</label>
                            <input type="text" wire:model="form.bank_account_number"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Account Holder Name</label>
                            <input type="text" wire:model="form.bank_account_name"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Employment & Payroll (Relation Manager) --}}
        <div x-show="activeTab === 'employment'" x-cloak>
            <div class="space-y-6">
                {{-- Employment Summary --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-300/20 dark:text-violet-300">
                                @svg('heroicon-o-briefcase', 'h-5 w-5')
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Employment Details</h3>
                                <p class="text-sm text-slate-500 dark:text-white/50">Current assignment and contract terms.</p>
                            </div>
                        </div>
                        <button wire:click="goToEmployment"
                            class="inline-flex h-9 items-center gap-2 rounded-lg bg-slate-900 px-3 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                            @svg('heroicon-o-pencil-square', 'h-4 w-4')
                            <span>Edit Employment</span>
                        </button>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Branch</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->branch?->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Department</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->department?->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Position</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->position?->title ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Employment Type</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $employee->employment_type ?? '-')) }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Start Date</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->start_date?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Work Mode</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ ucfirst($employee->work_mode ?? '-') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Base Salary</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">Rp {{ number_format($this->stats['baseSalary'], 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Payroll Group</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->payrollGroup?->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Payroll Items --}}
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-300/20 dark:text-emerald-300">
                                @svg('heroicon-o-banknotes', 'h-5 w-5')
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Payroll Items</h3>
                                <p class="text-sm text-slate-500 dark:text-white/50">Earnings and deductions assigned to this employee.</p>
                            </div>
                        </div>
                        <button wire:click="goToEmployment"
                            class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                            @svg('heroicon-o-cog-6-tooth', 'h-4 w-4')
                            <span>Manage</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Item</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Type</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-right">Amount</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                                @forelse ($this->payrollItems as $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                        <td class="px-6 py-3">
                                            <p class="font-medium text-slate-900 dark:text-white">{{ $item->payrollItem?->name ?? '-' }}</p>
                                            <p class="text-xs text-slate-500 dark:text-white/50">{{ $item->payrollItem?->code ?? '-' }}</p>
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                                {{ ($item->payrollItem?->type ?? 'earning') === 'earning' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' }}">
                                                {{ ucfirst($item->payrollItem?->type ?? 'earning') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-right font-medium text-slate-900 dark:text-white">
                                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            @if($item->is_active)
                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">Active</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-500/20 dark:text-slate-400">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                @svg('heroicon-o-banknotes', 'h-10 w-10 text-slate-300 dark:text-white/20')
                                                <p class="mt-3 text-sm text-slate-500 dark:text-white/50">No payroll items assigned yet.</p>
                                                <button wire:click="goToEmployment" class="mt-3 text-sm font-medium text-slate-900 hover:underline dark:text-white">
                                                    Add payroll items
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
