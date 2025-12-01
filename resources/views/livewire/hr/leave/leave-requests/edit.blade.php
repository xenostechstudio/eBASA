<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        <x-alert :type="session('flash.type')">
            {{ session('flash.message') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        <x-form.section-header
            title="Edit Leave Request"
            description="Review and manage leave request."
        />

        @include('livewire.hr.leave.leave-requests._form', ['isEditing' => true])
    </div>
</div>
