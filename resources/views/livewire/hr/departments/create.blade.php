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
            title="Department Information"
            description="Create a new department and assign its branch and leadership."
        />

        @include('livewire.hr.departments._form', [
            'isEditing' => false,
            'department' => null,
        ])
    </div>
</div>
