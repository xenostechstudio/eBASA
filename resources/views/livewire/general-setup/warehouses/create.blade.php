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
            title="Warehouse Information"
            description="Create a new warehouse and link it to a branch."
        />

        @include('livewire.general-setup.warehouses._form', [
            'isEditing' => false,
            'editingWarehouse' => null,
            'branches' => $branches,
        ])
    </div>
</div>
