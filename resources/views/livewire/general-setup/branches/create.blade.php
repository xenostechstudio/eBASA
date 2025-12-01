<div>
    {{-- Flash Message (fixed position, doesn't affect layout) --}}
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="Branch Information"
            description="Create a new branch and set up its contact details."
        />

        @include('livewire.general-setup.branches._form', ['isEditing' => false])
    </div>
</div>
