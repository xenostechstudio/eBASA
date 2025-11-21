<div class="space-y-10">
    <x-module.heading
        tagline="HR · People"
        title="New Department"
        description="Define a department, assign its branch and leadership contacts."
    >
        <x-slot:actions>
            <x-ui.button as="a" variant="secondary" href="{{ route('hr.departments') }}">Back to departments</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <form wire:submit.prevent="save" class="space-y-10">
        <section class="rounded-[32px] border border-white/10 bg-white/5 p-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Details</p>
                    <h3 class="text-lg font-semibold text-white">Department identity</h3>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.input label="Department code" model="form.code" placeholder="e.g. OPS-01" col-span="4" />
                <x-form.input label="Name" model="form.name" placeholder="Operations" col-span="8" />
                <x-form.select label="Branch" model="form.branch_id" placeholder="Select branch" col-span="6">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch['id'] }}" class="bg-slate-900">{{ $branch['name'] }}</option>
                    @endforeach
                </x-form.select>
                <x-form.select label="Parent department" model="form.parent_id" placeholder="No parent" col-span="6">
                    @foreach ($departments as $department)
                        <option value="{{ $department['id'] }}" class="bg-slate-900">{{ $department['name'] }}</option>
                    @endforeach
                </x-form.select>
            </div>
        </section>

        <section class="rounded-[32px] border border-white/10 bg-slate-950/40 p-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Leadership</p>
                    <h3 class="text-lg font-semibold text-white">Owner & contact</h3>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-12">
                <x-form.input label="Lead name" model="form.lead_name" placeholder="e.g. Maya Rahma" col-span="6" />
                <x-form.input label="Lead email" type="email" model="form.lead_email" placeholder="lead@company.com" col-span="6" />
                <x-form.input label="Description" model="form.description" textarea rows="4" placeholder="Responsibilities, scope, focus." col-span="12" />
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-between gap-4 py-4">
            <x-ui.button as="a" variant="ghost" href="{{ route('hr.departments') }}">Cancel</x-ui.button>
            <div class="flex gap-2">
                <x-ui.button variant="secondary" type="button">Save draft</x-ui.button>
                <x-ui.button type="submit">Save department</x-ui.button>
            </div>
        </div>
    </form>
</div>
