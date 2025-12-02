<div>
    @if (session('status'))
        <x-alert type="success">{{ session('status') }}</x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="New Employee"
            description="Complete the wizard to add a new team member."
        />

        @include('livewire.hr.employees._form', [
            'isEditing' => false,
            'employee' => null,
        ])
    </div>
</div>
