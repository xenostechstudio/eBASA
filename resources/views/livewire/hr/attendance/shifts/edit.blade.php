<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="Edit Shift"
            description="Update shift schedule details."
        />

        @include('livewire.hr.attendance.shifts._form', ['isEditing' => true])
    </div>
</div>
