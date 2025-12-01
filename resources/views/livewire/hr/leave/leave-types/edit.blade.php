<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="Edit Leave Type"
            description="Update leave type configuration."
        />

        @include('livewire.hr.leave.leave-types._form', ['isEditing' => true])
    </div>
</div>
