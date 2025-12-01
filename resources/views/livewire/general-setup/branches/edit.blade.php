<div>
    {{-- Flash Message (fixed position, doesn't affect layout) --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="Branch Information"
            description="Update branch information and contact details."
        />

        @include('livewire.general-setup.branches._form', [
            'isEditing' => true,
            'editingBranch' => $editingBranch ?? null,
        ])
    </div>
</div>
