<x-module.section
    as="div"
    padding="none"
    rounded="none"
    :border="false"
    background-class=""
    gap="md"
>
    <x-module.heading
        tagline="HR · Employees"
        title="New Employee"
        description="Complete the wizard to add a new team member with all required details."
    >
        <x-slot:actions>
            <div class="flex items-center gap-3">
                <x-ui.button as="a" variant="secondary" href="{{ route('hr.employees') }}">Back to directory</x-ui.button>
                <x-ui.button type="submit" form="employee-create-form">
                    @svg('heroicon-o-check', 'h-4 w-4 mr-2')
                    Save employee
                </x-ui.button>
            </div>
        </x-slot:actions>
    </x-module.heading>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-100 flex items-center gap-3">
            @svg('heroicon-o-check-circle', 'h-5 w-5')
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[16rem_1fr]">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
            <div class="space-y-2">
                @foreach ($tabs as $tabKey => $tabLabel)
                    <button
                        type="button"
                        wire:click="setTab('{{ $tabKey }}')"
                        @class([
                            'flex w-full items-center justify-between rounded-xl border px-4 py-3 text-left text-sm font-semibold transition',
                            'border-white bg-white text-slate-900 shadow-lg dark:border-white/70 dark:bg-white/90 dark:text-slate-900' => $activeTab === $tabKey,
                            'border-white/10 text-white/60 hover:border-white/30 hover:text-white' => $activeTab !== $tabKey,
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

        <form wire:submit.prevent="save" id="employee-create-form" class="lg:pt-0">
            @if ($activeTab === 'personal')
                <x-module.section
                    shadow
                    gap="lg"
                    background-class="bg-gradient-to-b from-white/10 via-white/5 to-slate-950/40"
                >
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 text-white">
                                @svg('heroicon-o-identification', 'h-5 w-5')
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Personal Information</h3>
                            </div>
                        </div>
                        <span class="text-xs text-white/50">Identity, contact & government IDs</span>
                    </div>

                    <div class="grid gap-6 md:grid-cols-12">
                        <x-form.input label="Full name" model="form.full_name" placeholder="e.g. Andira Mahendra" col-span="8" required />
                        <x-form.input label="Preferred name" model="form.preferred_name" placeholder="Nickname (optional)" col-span="4" />
                        <x-form.input label="Employee code" model="form.code" placeholder="EMP-001" col-span="4" required />
                        <x-form.input label="Work email" type="email" model="form.email" placeholder="user@company.com" col-span="4" required />
                        <x-form.input label="Phone" model="form.phone" placeholder="+62 812 0000 0000" col-span="4" />
                        <x-form.input label="WhatsApp number" model="form.whatsapp_number" placeholder="+62 811 1111 111" col-span="4" />
                        <x-form.input label="Date of birth" type="date" model="form.date_of_birth" col-span="4" />
                        <x-form.input label="NIK" model="form.nik" placeholder="16 digit national ID" col-span="4" required />
                        <x-form.input label="NPWP" model="form.npwp" placeholder="Tax number (optional)" col-span="4" />
                        <x-form.input label="Address" model="form.address" textarea rows="3" placeholder="Street, city, province" col-span="12" />
                    </div>
                </x-module.section>
            @elseif ($activeTab === 'emergency')
                <x-module.section
                    gap="lg"
                    background-class="bg-gradient-to-br from-amber-900/20 via-slate-900/40 to-slate-900/20"
                >
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-300/20 text-amber-100">
                                @svg('heroicon-o-shield-check', 'h-5 w-5')
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Emergency Contact</h3>
                            </div>
                        </div>
                        <span class="text-xs text-white/50">Safety contact information</span>
                    </div>

                    <div class="grid gap-6 md:grid-cols-12">
                        <div class="col-span-12">
                            <p class="mb-4 text-sm font-medium text-white/70">Emergency Contact</p>
                        </div>
                        <x-form.input label="Contact name" model="form.emergency_contact_name" placeholder="Person to notify in emergency" col-span="6" />
                        <x-form.input label="Contact WhatsApp" model="form.emergency_contact_whatsapp" placeholder="+62 ..." col-span="6" />
                    </div>
                </x-module.section>
            @elseif ($activeTab === 'banking')
                <x-module.section
                    gap="lg"
                    background-class="bg-gradient-to-br from-amber-900/20 via-slate-900/40 to-slate-900/20"
                >
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-300/20 text-amber-100">
                                @svg('heroicon-o-credit-card', 'h-5 w-5')
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Banking Details</h3>
                            </div>
                        </div>
                        <span class="text-xs text-white/50">Payroll destination account</span>
                    </div>

                    <div class="grid gap-6 md:grid-cols-12">
                        <div class="col-span-12">
                            <p class="mb-4 text-sm font-medium text-white/70">Bank Account for Payroll</p>
                        </div>
                        <x-form.input label="Bank name" model="form.bank_name" placeholder="e.g. BCA, Mandiri, BNI" col-span="4" />
                        <x-form.input label="Account number" model="form.bank_account_number" placeholder="Account digits" col-span="4" />
                        <x-form.input label="Account holder name" model="form.bank_account_name" placeholder="Name as registered" col-span="4" />
                    </div>
                </x-module.section>
            @elseif ($activeTab === 'review')
                <x-module.section
                    gap="lg"
                    background-class="bg-gradient-to-br from-emerald-900/20 via-slate-900/40 to-slate-900/20"
                >
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-300/20 text-emerald-100">
                        @svg('heroicon-o-clipboard-document-check', 'h-5 w-5')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Review & Confirm</h3>
                    </div>
                </div>
                <span class="text-xs text-white/50">Verify all details before saving</span>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Personal Info Summary --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-white">Personal Information</h4>
                        <button type="button" wire:click="setTab('personal')" class="text-xs text-white/60 hover:text-white">Edit</button>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-white/60">Full name</dt>
                            <dd class="font-medium text-white">{{ $form['full_name'] ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-white/60">Employee code</dt>
                            <dd class="font-medium text-white">{{ $form['code'] ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-white/60">Email</dt>
                            <dd class="font-medium text-white">{{ $form['email'] ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-white/60">Phone</dt>
                            <dd class="font-medium text-white">{{ $form['phone'] ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-white/60">NIK</dt>
                            <dd class="font-medium text-white">{{ $form['nik'] ?: '—' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Emergency & Banking Summary --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5 lg:col-span-2">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-white">Emergency & Banking</h4>
                        <button type="button" wire:click="setTab('emergency')" class="text-xs text-white/60 hover:text-white">Edit</button>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-white/60">Emergency contact</dt>
                                <dd class="font-medium text-white">{{ $form['emergency_contact_name'] ?: '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-white/60">Emergency WhatsApp</dt>
                                <dd class="font-medium text-white">{{ $form['emergency_contact_whatsapp'] ?: '—' }}</dd>
                            </div>
                        </dl>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-white/60">Bank</dt>
                                <dd class="font-medium text-white">{{ $form['bank_name'] ?: '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-white/60">Account number</dt>
                                <dd class="font-medium text-white">{{ $form['bank_account_number'] ?: '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-white/60">Account name</dt>
                                <dd class="font-medium text-white">{{ $form['bank_account_name'] ?: '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </x-module.section>
            @endif

        </form>
    </div>
</x-module.section>
