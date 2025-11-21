<div class="space-y-10">
    <x-module.heading
        tagline="HR · Employees"
        title="New Employee"
        description="Capture personal, employment, and assignment details for a new team member."
    >
        <x-slot:actions>
            <x-ui.button as="a" variant="secondary" href="{{ route('hr.employees') }}">Back to directory</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <form wire:submit.prevent="save" class="space-y-10">
        <section class="rounded-[32px] border border-white/10 bg-gradient-to-b from-white/10 via-white/5 to-slate-950/40 p-8 space-y-8 shadow-[0_25px_60px_rgba(15,23,42,0.35)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 text-white">
                        @svg('heroicon-o-identification', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Personal information</p>
                        <h3 class="text-lg font-semibold text-white">Identity & contact</h3>
                    </div>
                </div>
                <span class="text-xs text-white/50">Basic profile + government compliance</span>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.input label="Full name" model="form.full_name" placeholder="e.g. Andira Mahendra" col-span="8" />
                <x-form.input label="Preferred name" model="form.preferred_name" placeholder="Nickname (optional)" col-span="4" />
                <x-form.input label="Employee code" model="form.code" placeholder="EMP-001" col-span="4" />
                <x-form.input label="Work email" type="email" model="form.email" placeholder="user@company.com" col-span="4" />
                <x-form.input label="Phone" model="form.phone" placeholder="+62 812 0000 0000" col-span="4" />
                <x-form.input label="WhatsApp number" model="form.whatsapp_number" placeholder="+62 811 1111 111" col-span="4" />
                <x-form.input label="Date of birth" type="date" model="form.date_of_birth" col-span="4" />
                <x-form.input label="NIK" model="form.nik" placeholder="16 digit national ID" col-span="4" />
                <x-form.input label="NPWP" model="form.npwp" placeholder="Tax number (optional)" col-span="4" />
                <x-form.input label="Address" model="form.address" textarea rows="3" placeholder="Street, city, province" col-span="12" />
            </div>
        </section>


        <section class="rounded-[32px] border border-white/10 bg-gradient-to-br from-slate-900/70 via-slate-900/40 to-slate-900/20 p-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-300/20 text-indigo-100">
                        @svg('heroicon-o-shield-check', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Support & safety</p>
                        <h3 class="text-lg font-semibold text-white">Emergency contact & banking</h3>
                    </div>
                </div>
                <span class="text-xs text-white/50">Who to notify + payout destination</span>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.input label="Emergency contact name" model="form.emergency_contact_name" placeholder="Person to notify" col-span="6" />
                <x-form.input label="Emergency contact WhatsApp" model="form.emergency_contact_whatsapp" placeholder="+62 ..." col-span="6" />
                <x-form.input label="Bank name" model="form.bank_name" placeholder="e.g. BCA" col-span="4" />
                <x-form.input label="Bank account number" model="form.bank_account_number" placeholder="Account digits" col-span="4" />
                <x-form.input label="Bank account name" model="form.bank_account_name" placeholder="Registered account name" col-span="4" />
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-between gap-4 py-4">
            <x-ui.button as="a" variant="ghost" href="{{ route('hr.employees') }}">Cancel</x-ui.button>
            <div class="flex gap-2">
                <x-ui.button variant="secondary" type="button">Save draft</x-ui.button>
                <x-ui.button type="submit">Save employee</x-ui.button>
            </div>
        </div>
    </form>
</div>
