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
            title="Edit User"
            description="Update user account details, role, and branch access."
        />

        {{-- Form --}}
        @include('livewire.general-setup.users._form', ['isEditing' => true])
    </div>
</div>
