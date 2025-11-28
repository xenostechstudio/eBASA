<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">HR · Employees</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">Edit Employee</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Update {{ $employee->full_name }}'s profile and employment details.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('hr.employees') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Back to directory
            </a>
            <button type="submit" form="employee-edit-form"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Update Employee</span>
            </button>
        </div>
    </div>

    <form wire:submit.prevent="save" id="employee-edit-form" class="space-y-6">
        {{-- Personal Information --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white">
                        @svg('heroicon-o-identification', 'h-5 w-5')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Personal Information</h3>
                        <p class="text-xs text-slate-500 dark:text-white/50">Identity & contact details</p>
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

        {{-- Employment Details --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-300/20 dark:text-indigo-300">
                        @svg('heroicon-o-briefcase', 'h-5 w-5')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Employment Details</h3>
                        <p class="text-xs text-slate-500 dark:text-white/50">Assignment & contract information</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-12">
                <x-form.select label="Branch" model="form.branch_id" placeholder="Select branch" col-span="6" :required="true">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch['id'] }}" class="bg-white dark:bg-slate-900">{{ $branch['name'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Department" model="form.department_id" placeholder="Select department" col-span="6">
                    @foreach ($departments as $department)
                        <option value="{{ $department['id'] }}" class="bg-white dark:bg-slate-900">{{ $department['name'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Position" model="form.position_id" placeholder="Select position" col-span="6">
                    @foreach ($positions as $position)
                        <option value="{{ $position['id'] }}" class="bg-white dark:bg-slate-900">{{ $position['title'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Reports to" model="form.manager_id" placeholder="Select manager" col-span="6">
                    @foreach ($managers as $manager)
                        <option value="{{ $manager['id'] }}" class="bg-white dark:bg-slate-900">{{ $manager['full_name'] }} ({{ $manager['code'] }})</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Employment type" model="form.employment_type" placeholder="Choose type" col-span="4" :required="true">
                    @foreach ($employmentTypes as $type)
                        <option value="{{ $type }}" class="bg-white dark:bg-slate-900">{{ str($type)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Employment class" model="form.employment_class" placeholder="Choose class" col-span="4">
                    @foreach ($employmentClasses as $class)
                        <option value="{{ $class }}" class="bg-white dark:bg-slate-900">{{ str($class)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Work mode" model="form.work_mode" placeholder="Choose mode" col-span="4">
                    @foreach ($workModes as $mode)
                        <option value="{{ $mode }}" class="bg-white dark:bg-slate-900">{{ str($mode)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Status" model="form.status" placeholder="Select status" col-span="4" :required="true">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" class="bg-white dark:bg-slate-900">{{ str($status)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.input label="Salary band" model="form.salary_band" placeholder="Level or range" col-span="4" />
                <x-form.input label="Start date" type="date" model="form.start_date" col-span="4" :required="true" />
                <x-form.input label="Probation end date" type="date" model="form.probation_end_date" col-span="4" />
                <x-form.input label="Notes" model="form.notes" :textarea="true" rows="3" placeholder="Additional notes" col-span="12" />
            </div>
        </div>

        {{-- Emergency & Banking --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-300/20 dark:text-amber-300">
                        @svg('heroicon-o-shield-check', 'h-5 w-5')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Emergency Contact & Banking</h3>
                        <p class="text-xs text-slate-500 dark:text-white/50">Support & safety information</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-12">
                <x-form.input label="Emergency contact name" model="form.emergency_contact_name" placeholder="Person to notify" col-span="6" />
                <x-form.input label="Emergency contact WhatsApp" model="form.emergency_contact_whatsapp" placeholder="+62 ..." col-span="6" />
                <x-form.input label="Bank name" model="form.bank_name" placeholder="e.g. BCA" col-span="4" />
                <x-form.input label="Bank account number" model="form.bank_account_number" placeholder="Account digits" col-span="4" />
                <x-form.input label="Bank account name" model="form.bank_account_name" placeholder="Registered account name" col-span="4" />
            </div>
        </div>
    </form>
</div>
