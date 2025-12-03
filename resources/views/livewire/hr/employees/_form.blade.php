@php /** @var bool $isEditing */ @endphp

<form wire:submit.prevent="save" id="employee-form" class="space-y-0">
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="px-6 py-5">
            <div class="grid gap-6 lg:grid-cols-[16rem_1fr]">
                {{-- Sidebar Tabs --}}
                <div class="space-y-2 border-r border-dashed border-slate-200 pr-4 dark:border-white/10">
                    @foreach ($tabs as $tabKey => $tabLabel)
                        <button
                            type="button"
                            wire:click="setTab('{{ $tabKey }}')"
                            @class([
                                'flex w-full items-center justify-between rounded-xl px-4 py-3 text-left text-sm font-semibold transition',
                                'bg-slate-900 text-white shadow-lg dark:bg-white dark:text-slate-900' => $activeTab === $tabKey,
                                'text-slate-600 hover:bg-slate-50 dark:text-white/60 dark:hover:bg-white/5' => $activeTab !== $tabKey,
                            ])
                        >
                            <span>{{ $tabLabel }}</span>
                            @if ($activeTab === $tabKey)
                                @svg('heroicon-o-check', 'h-4 w-4')
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- Form Content --}}
                <div class="lg:pt-0">
                    @if ($activeTab === 'personal')
                        {{-- Personal Information --}}
                        <div class="space-y-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white">
                                        @svg('heroicon-o-identification', 'h-5 w-5')
                                    </span>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Personal Information</h3>
                                        <p class="text-xs text-slate-500 dark:text-white/50">Identity, contact & government IDs</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-5 md:grid-cols-12">
                                <x-form.input label="Full name" model="form.full_name" placeholder="e.g. Andira Mahendra" col-span="8" :required="true" helper="Legal name as appears on official documents" />
                                <x-form.input label="Preferred name" model="form.preferred_name" placeholder="Nickname (optional)" col-span="4" helper="Name used in daily communication" />
                                <x-form.input label="Employee code" model="form.code" placeholder="EMP-001" col-span="4" :required="true" helper="Unique identifier for this employee" />
                                <x-form.input label="Work email" type="email" model="form.email" placeholder="user@company.com" col-span="4" :required="true" helper="Primary email for work communication" />
                                <x-form.input label="Phone" model="form.phone" placeholder="+62 812 0000 0000" col-span="4" helper="Mobile number with country code" />
                                <x-form.input label="WhatsApp number" model="form.whatsapp_number" placeholder="+62 811 1111 111" col-span="4" helper="For instant messaging and notifications" />
                                <x-form.input label="Date of birth" type="date" model="form.date_of_birth" col-span="4" />
                                <x-form.input label="NIK" model="form.nik" placeholder="16 digit national ID" col-span="4" :required="true" helper="Indonesian national identity number" />
                                <x-form.input label="NPWP" model="form.npwp" placeholder="Tax number (optional)" col-span="4" helper="Tax identification number" />
                                <x-form.input label="Address" model="form.address" :textarea="true" rows="3" placeholder="Street, city, province" col-span="12" helper="Full residential address" />
                            </div>
                        </div>
                    @elseif ($activeTab === 'emergency')
                        {{-- Emergency (and Banking for edit) --}}
                        <div class="space-y-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-300/20 dark:text-amber-300">
                                        @svg('heroicon-o-shield-check', 'h-5 w-5')
                                    </span>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Emergency Contact</h3>
                                        <p class="text-xs text-slate-500 dark:text-white/50">Safety contact information</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-5 md:grid-cols-12">
                                <x-form.input label="Contact name" model="form.emergency_contact_name" placeholder="Person to notify in emergency" col-span="6" helper="Family member or close friend" />
                                <x-form.input label="Contact WhatsApp" model="form.emergency_contact_whatsapp" placeholder="+62 ..." col-span="6" helper="Reachable phone number" />
                            </div>

                            @if ($isEditing)
                                <div class="space-y-4 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                                    <div class="flex flex-wrap items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-300/20 dark:text-sky-300">
                                                @svg('heroicon-o-credit-card', 'h-5 w-5')
                                            </span>
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Banking Details</h3>
                                                <p class="text-xs text-slate-500 dark:text-white/50">Payroll destination account</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid gap-5 md:grid-cols-12">
                                        <x-form.input label="Bank name" model="form.bank_name" placeholder="e.g. BCA, Mandiri, BNI" col-span="4" helper="Bank for salary transfer" />
                                        <x-form.input label="Account number" model="form.bank_account_number" placeholder="Account digits" col-span="4" helper="Digits only, no spaces" />
                                        <x-form.input label="Account holder name" model="form.bank_account_name" placeholder="Name as registered" col-span="4" helper="Must match bank records" />
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer with Auditing Info --}}
        <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
            @if ($isEditing && isset($employee) && $employee)
                <div class="text-[11px] text-slate-400 dark:text-white/40">
                    <p>
                        Created
                        <span class="font-medium text-slate-500 dark:text-white/60">
                            {{ optional($employee->created_at)->format(config('basa.datetime_format')) }}
                        </span>
                        @if ($employee->createdBy)
                            by
                            <span class="font-medium text-slate-600 dark:text-white/80">
                                {{ $employee->createdBy->name }}
                            </span>
                        @endif
                    </p>
                    <p>
                        Last updated
                        <span class="font-medium text-slate-500 dark:text-white/60">
                            {{ optional($employee->updated_at)->format(config('basa.datetime_format')) }}
                        </span>
                        @if ($employee->updatedBy)
                            by
                            <span class="font-medium text-slate-600 dark:text-white/80">
                                {{ $employee->updatedBy->name }}
                            </span>
                        @endif
                    </p>
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 md:ml-auto">
                <x-button.secondary
                    type="button"
                    href="{{ route('hr.employees') }}"
                    tag="a"
                >
                    Cancel
                </x-button.secondary>

                <x-button.primary
                    type="submit"
                    class="h-10"
                >
                    <span>
                        @svg('heroicon-o-check', 'h-4 w-4')
                    </span>
                    <span>{{ $isEditing ? 'Save Changes' : 'Create Employee' }}</span>
                </x-button.primary>
            </div>
        </div>
    </div>
</form>
