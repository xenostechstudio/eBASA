<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="Create User"
            description="Add a new user account with role and branch access configuration."
        />

        {{-- Form --}}
        @include('livewire.general-setup.users._form', ['isEditing' => false])
    </div>
</div>
