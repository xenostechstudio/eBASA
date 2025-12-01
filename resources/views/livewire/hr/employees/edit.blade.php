<div class="space-y-6">
    {{-- Header --}}
    <x-form.section-header
        title="Edit Employee"
        :description="'Update ' . $employee->full_name . "'s profile and employment details.""
    />

    @include('livewire.hr.employees._form', [
        'isEditing' => true,
        'employee' => $employee,
    ])
</div>
