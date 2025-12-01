<div>
    {{-- Flash Message --}}
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="Payroll Group"
            description="Create a new payroll group for organizing employee salary schedules."
        />

        @include('livewire.hr.payroll.payroll-groups._form', [
            'isEditing' => false,
            'payrollGroup' => null,
        ])
    </div>
</div>
