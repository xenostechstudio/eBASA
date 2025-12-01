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
            title="Position Information"
            :description="'Update details for ' . ($position->title ?? 'position') . '.'"
        />

        @include('livewire.hr.positions._form', [
            'isEditing' => true,
            'position' => $position ?? null,
        ])
    </div>
</div>
