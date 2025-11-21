<div class="space-y-10">
    <x-module.heading
        tagline="HR · People"
        title="New Employment"
        description="Assign an employee to branches, positions, and contract terms."
    >
        <x-slot:actions>
            <x-ui.button as="a" variant="secondary" href="{{ route('hr.employments') }}">Back to employments</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <form wire:submit.prevent="save" class="space-y-10">
        <section class="rounded-[32px] border border-white/10 bg-white/5 p-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Assignment</p>
                    <h3 class="text-lg font-semibold text-white">Who is this employment for?</h3>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.select label="Employee" model="form.employee_id" placeholder="Select employee" col-span="6">
                    @foreach ($employees as $person)
                        <option value="{{ $person['id'] }}" class="bg-slate-900">{{ $person['full_name'] }} · {{ $person['code'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Branch" model="form.branch_id" placeholder="Select branch" col-span="6">
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
            </div>
        </section>

        <section class="rounded-[32px] border border-white/10 bg-slate-950/40 p-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p a="text-xs uppercase tracking-[0.35em] text-white/40">Contract</p>
                    <h3 class="text-lg font-semibold text-white">Employment structure</h3>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.select label="Employment type" model="form.employment_type" placeholder="Choose type" col-span="6">
                    @foreach ($employmentTypes as $type)
                        <option value="{{ $type }}" class="bg-slate-900">{{ str($type)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Employment class" model="form.employment_class" placeholder="Choose class" col-span="6">
                    @foreach ($employmentClasses as $class)
                        <option value="{{ $class }}" class="bg-slate-900">{{ str($class)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Work mode" model="form.work_mode" placeholder="Choose mode" col-span="6">
                    @foreach ($workModes as $mode)
                        <option value="{{ $mode }}" class="bg-slate-900">{{ str($mode)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Status" model="form.status" placeholder="Select status" col-span="6">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" class="bg-slate-900">{{ str($status)->headline() }}</option>
                    @endforeach
                </x-form.select>
                <x-form.input label="Salary band" model="form.salary_band" placeholder="Level or range" col-span="6" />
                <x-form.input label="Start date" type="date" model="form.start_date" col-span="3" />
                <x-form.input label="Probation end date" type="date" model="form.probation_end_date" col-span="3" />
                <x-form.input label="Notes" model="form.notes" textarea rows="4" placeholder="Contract notes or expectations" col-span="12" />
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-between gap-4 py-4">
            <x-ui.button as="a" variant="ghost" href="{{ route('hr.employments') }}">Cancel</x-ui.button>
            <div class="flex gap-2">
                <x-ui.button variant="secondary" type="button">Save draft</x-ui.button>
                <x-ui.button type="submit">Save employment</x-ui.button>
            </div>
        </div>
    </form>
</div>
