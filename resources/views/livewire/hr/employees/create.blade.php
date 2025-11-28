<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">HR · Employees</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">New Employee</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Complete the wizard to add a new team member.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('hr.employees') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Back to directory
            </a>
            <button type="submit" form="employee-create-form"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Save employee</span>
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-300">
            @svg('heroicon-o-check-circle', 'h-5 w-5')
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[16rem_1fr]">
        {{-- Sidebar Tabs --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="space-y-2">
                @foreach ($tabs as $tabKey => $tabLabel)
                    <button
                        type="button"
                        wire:click="setTab('{{ $tabKey }}')"
                        @class([
                            'flex w-full items-center justify-between rounded-xl border px-4 py-3 text-left text-sm font-semibold transition',
                            'border-slate-900 bg-slate-900 text-white shadow-lg dark:border-white dark:bg-white dark:text-slate-900' => $activeTab === $tabKey,
                            'border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900 dark:border-white/10 dark:text-white/60 dark:hover:border-white/30 dark:hover:text-white' => $activeTab !== $tabKey,
                        ])
                    >
                        <span>{{ $tabLabel }}</span>
                        @if ($activeTab === $tabKey)
                            @svg('heroicon-o-check', 'h-4 w-4')
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Form Content --}}
        <form wire:submit.prevent="save" id="employee-create-form" class="lg:pt-0">
            @if ($activeTab === 'personal')
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
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
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
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
                </div>
            @elseif ($activeTab === 'banking')
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
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
            @elseif ($activeTab === 'review')
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-300/20 dark:text-emerald-300">
                                @svg('heroicon-o-clipboard-document-check', 'h-5 w-5')
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Review & Confirm</h3>
                                <p class="text-xs text-slate-500 dark:text-white/50">Verify all details before saving</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        {{-- Personal Info Summary --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-white/10 dark:bg-white/5">
                            <div class="mb-4 flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Personal Information</h4>
                                <button type="button" wire:click="setTab('personal')" class="text-xs text-slate-500 hover:text-slate-700 dark:text-white/60 dark:hover:text-white">Edit</button>
                            </div>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-slate-500 dark:text-white/60">Full name</dt>
                                    <dd class="font-medium text-slate-900 dark:text-white">{{ $form['full_name'] ?: '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500 dark:text-white/60">Employee code</dt>
                                    <dd class="font-medium text-slate-900 dark:text-white">{{ $form['code'] ?: '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500 dark:text-white/60">Email</dt>
                                    <dd class="font-medium text-slate-900 dark:text-white">{{ $form['email'] ?: '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500 dark:text-white/60">Phone</dt>
                                    <dd class="font-medium text-slate-900 dark:text-white">{{ $form['phone'] ?: '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500 dark:text-white/60">NIK</dt>
                                    <dd class="font-medium text-slate-900 dark:text-white">{{ $form['nik'] ?: '—' }}</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Emergency & Banking Summary --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 lg:col-span-2 dark:border-white/10 dark:bg-white/5">
                            <div class="mb-4 flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Emergency & Banking</h4>
                                <button type="button" wire:click="setTab('emergency')" class="text-xs text-slate-500 hover:text-slate-700 dark:text-white/60 dark:hover:text-white">Edit</button>
                            </div>
                            <div class="grid gap-6 md:grid-cols-2">
                                <dl class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500 dark:text-white/60">Emergency contact</dt>
                                        <dd class="font-medium text-slate-900 dark:text-white">{{ $form['emergency_contact_name'] ?: '—' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500 dark:text-white/60">Emergency WhatsApp</dt>
                                        <dd class="font-medium text-slate-900 dark:text-white">{{ $form['emergency_contact_whatsapp'] ?: '—' }}</dd>
                                    </div>
                                </dl>
                                <dl class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500 dark:text-white/60">Bank</dt>
                                        <dd class="font-medium text-slate-900 dark:text-white">{{ $form['bank_name'] ?: '—' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500 dark:text-white/60">Account number</dt>
                                        <dd class="font-medium text-slate-900 dark:text-white">{{ $form['bank_account_number'] ?: '—' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-slate-500 dark:text-white/60">Account name</dt>
                                        <dd class="font-medium text-slate-900 dark:text-white">{{ $form['bank_account_name'] ?: '—' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>
