<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="Record Attendance"
            description="Create a new attendance record for an employee."
        />

        @include('livewire.hr.attendance.attendances._form', ['isEditing' => false])
    </div>
</div>
