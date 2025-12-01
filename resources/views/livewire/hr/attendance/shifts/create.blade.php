<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="New Shift"
            description="Create a new work shift schedule."
        />

        @include('livewire.hr.attendance.shifts._form', ['isEditing' => false])
    </div>
</div>
