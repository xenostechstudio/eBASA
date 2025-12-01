<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="Edit Payroll Adjustment"
            description="Update payroll adjustment details."
        />

        @include('livewire.hr.payroll.payroll-adjustments._form', ['isEditing' => true])
    </div>
</div>
