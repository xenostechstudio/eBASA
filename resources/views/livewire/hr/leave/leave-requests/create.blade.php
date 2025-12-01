<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="New Leave Request"
            description="Submit a new leave request for an employee."
        />

        @include('livewire.hr.leave.leave-requests._form', ['isEditing' => false])
    </div>
</div>
