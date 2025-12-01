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
            title="Employment Record"
            description="Assign an employee to a branch, department, and position with contract terms."
        />

        @include('livewire.hr.employments._form', [
            'isEditing' => false,
            'employee' => null,
        ])
    </div>
</div>
