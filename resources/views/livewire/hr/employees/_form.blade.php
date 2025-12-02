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
                                <x-form.input label="Full name" model="form.full_name" placeholder="e.g. Andira Mahendra" col-span="8" :required="true" />
                                <x-form.input label="Preferred name" model="form.preferred_name" placeholder="Nickname (optional)" col-span="4" />
                                <x-form.input label="Employee code" model="form.code" placeholder="EMP-001" col-span="4" :required="true" />
                                <x-form.input label="Work email" type="email" model="form.email" placeholder="user@company.com" col-span="4" :required="true" />
                                <x-form.input label="Phone" model="form.phone" placeholder="+62 812 0000 0000" col-span="4" />
                                <x-form.input label="WhatsApp number" model="form.whatsapp_number" placeholder="+62 811 1111 111" col-span="4" />
                                <x-form.input label="Date of birth" type="date" model="form.date_of_birth" col-span="4" />
                                <x-form.input label="NIK" model="form.nik" placeholder="16 digit national ID" col-span="4" :required="true" />
                                <x-form.input label="NPWP" model="form.npwp" placeholder="Tax number (optional)" col-span="4" />
                                <x-form.input label="Address" model="form.address" :textarea="true" rows="3" placeholder="Street, city, province" col-span="12" />
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
                                <x-form.input label="Contact name" model="form.emergency_contact_name" placeholder="Person to notify in emergency" col-span="6" />
                                <x-form.input label="Contact WhatsApp" model="form.emergency_contact_whatsapp" placeholder="+62 ..." col-span="6" />
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
                                        <x-form.input label="Bank name" model="form.bank_name" placeholder="e.g. BCA, Mandiri, BNI" col-span="4" />
                                        <x-form.input label="Account number" model="form.bank_account_number" placeholder="Account digits" col-span="4" />
                                        <x-form.input label="Account holder name" model="form.bank_account_name" placeholder="Name as registered" col-span="4" />
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer with Auditing Info --}}
        <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
            @if ($isEditing && isset($employee) && $employee)
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] text-slate-400 dark:text-white/40">
                    <div class="flex items-center gap-1.5">
                        @svg('heroicon-o-clock', 'h-3.5 w-3.5')
                        <span>Created</span>
                        <span class="font-medium text-slate-500 dark:text-white/60">
                            {{ optional($employee->created_at)->format(config('basa.datetime_format', 'd M Y H:i')) }}
                        </span>
                        @if ($employee->createdBy)
                            <span>by</span>
                            <span class="font-medium text-slate-600 dark:text-white/80">{{ $employee->createdBy->name }}</span>
                        @endif
                    </div>
                    @if ($employee->updated_at && $employee->updated_at->ne($employee->created_at))
                        <div class="flex items-center gap-1.5">
                            @svg('heroicon-o-pencil-square', 'h-3.5 w-3.5')
                            <span>Updated</span>
                            <span class="font-medium text-slate-500 dark:text-white/60">
                                {{ optional($employee->updated_at)->format(config('basa.datetime_format', 'd M Y H:i')) }}
                            </span>
                            @if ($employee->updatedBy)
                                <span>by</span>
                                <span class="font-medium text-slate-600 dark:text-white/80">{{ $employee->updatedBy->name }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            @else
                <div></div>
            @endif

            <div class="flex items-center justify-end gap-3">
                <a
                    href="{{ route('hr.employees') }}"
                    class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    <span>
                        @svg('heroicon-o-check', 'h-4 w-4')
                    </span>
                    <span>{{ $isEditing ? 'Update Employee' : 'Create Employee' }}</span>
                </button>
            </div>
        </div>
    </div>
</form>
