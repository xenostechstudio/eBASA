<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="Edit Attendance"
            description="Update attendance record details."
        />

        @include('livewire.hr.attendance.attendances._form', ['isEditing' => true])
    </div>
</div>
