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
            description="Update warehouse information and contact details."
        />

        @include('livewire.general-setup.warehouses._form', [
            'isEditing' => true,
            'editingWarehouse' => $editingWarehouse ?? null,
            'branches' => $branches,
        ])
    </div>
</div>
