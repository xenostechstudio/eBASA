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
            title="Position Information"
            description="Create a new position, set its level and where it belongs."
        />

        @include('livewire.hr.positions._form', [
            'isEditing' => false,
            'position' => null,
        ])
    </div>
</div>
