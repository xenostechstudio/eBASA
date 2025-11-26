<div class="space-y-8">
    <x-module.heading
        tagline="HR · Employees"
        title="Edit Employee"
        description="Update {{ $employee->full_name }}'s profile and employment details."
    >
        <x-slot:actions>
            <x-ui.button as="a" variant="secondary" href="{{ route('hr.employees') }}">Back to directory</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <form wire:submit.prevent="save" class="space-y-8">
        {{-- Personal Information --}}
        <section class="rounded-[32px] border border-white/10 bg-gradient-to-b from-white/10 via-white/5 to-slate-950/40 p-8 space-y-8 shadow-[0_25px_60px_rgba(15,23,42,0.35)]">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 text-white">
                        @svg('heroicon-o-identification', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Personal information</p>
                        <h3 class="text-lg font-semibold text-white">Identity & Contact</h3>
                    </div>
                </div>
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
        </section>

        {{-- Employment Details --}}
        <section class="rounded-[32px] border border-white/10 bg-gradient-to-br from-indigo-900/30 via-slate-900/40 to-slate-900/20 p-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-300/20 text-indigo-100">
                        @svg('heroicon-o-briefcase', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Employment</p>
                        <h3 class="text-lg font-semibold text-white">Assignment & Contract</h3>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.select label="Branch" model="form.branch_id" placeholder="Select branch" col-span="6" required>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch['id'] }}" class="bg-slate-900">{{ $branch['name'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Department" model="form.department_id" placeholder="Select department" col-span="6">
                    @foreach ($departments as $department)
                        <option value="{{ $department['id'] }}" class="bg-slate-900">{{ $department['name'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Position" model="form.position_id" placeholder="Select position" col-span="6">
                    @foreach ($positions as $position)
                        <option value="{{ $position['id'] }}" class="bg-slate-900">{{ $position['title'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Reports to" model="form.manager_id" placeholder="Select manager" col-span="6">
                    @foreach ($managers as $manager)
                        <option value="{{ $manager['id'] }}" class="bg-slate-900">{{ $manager['full_name'] }} ({{ $manager['code'] }})</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Employment type" model="form.employment_type" placeholder="Choose type" col-span="4" required>
                    @foreach ($employmentTypes as $type)
                        <option value="{{ $type }}" class="bg-slate-900">{{ str($type)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Employment class" model="form.employment_class" placeholder="Choose class" col-span="4">
                    @foreach ($employmentClasses as $class)
                        <option value="{{ $class }}" class="bg-slate-900">{{ str($class)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Work mode" model="form.work_mode" placeholder="Choose mode" col-span="4">
                    @foreach ($workModes as $mode)
                        <option value="{{ $mode }}" class="bg-slate-900">{{ str($mode)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Status" model="form.status" placeholder="Select status" col-span="4" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" class="bg-slate-900">{{ str($status)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.input label="Salary band" model="form.salary_band" placeholder="Level or range" col-span="4" />
                <x-form.input label="Start date" type="date" model="form.start_date" col-span="4" required />
                <x-form.input label="Probation end date" type="date" model="form.probation_end_date" col-span="4" />
                <x-form.input label="Notes" model="form.notes" textarea rows="3" placeholder="Additional notes" col-span="12" />
            </div>
        </section>

        {{-- Emergency & Banking --}}
        <section class="rounded-[32px] border border-white/10 bg-gradient-to-br from-amber-900/20 via-slate-900/40 to-slate-900/20 p-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-300/20 text-amber-100">
                        @svg('heroicon-o-shield-check', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Support & safety</p>
                        <h3 class="text-lg font-semibold text-white">Emergency Contact & Banking</h3>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.input label="Emergency contact name" model="form.emergency_contact_name" placeholder="Person to notify" col-span="6" />
                <x-form.input label="Emergency contact WhatsApp" model="form.emergency_contact_whatsapp" placeholder="+62 ..." col-span="6" />
                <x-form.input label="Bank name" model="form.bank_name" placeholder="e.g. BCA" col-span="4" />
                <x-form.input label="Bank account number" model="form.bank_account_number" placeholder="Account digits" col-span="4" />
                <x-form.input label="Bank account name" model="form.bank_account_name" placeholder="Registered account name" col-span="4" />
            </div>
        </section>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-between gap-4 py-4">
            <x-ui.button as="a" variant="ghost" href="{{ route('hr.employees') }}">Cancel</x-ui.button>
            <x-ui.button type="submit">
                @svg('heroicon-o-check', 'h-4 w-4 mr-2')
                Update Employee
            </x-ui.button>
        </div>
    </form>
</div>
