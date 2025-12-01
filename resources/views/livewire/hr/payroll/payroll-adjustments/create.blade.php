<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="New Payroll Adjustment"
            description="Create a one-time payroll adjustment for an employee."
        />

        @include('livewire.hr.payroll.payroll-adjustments._form', ['isEditing' => false])
    </div>
</div>
