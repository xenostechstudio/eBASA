<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="New Payroll Run"
            description="Create a new payroll processing period."
        />

        @include('livewire.hr.payroll.payroll-runs._form', ['isEditing' => false])
    </div>
</div>
