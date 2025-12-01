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
            title="Department Information"
            :description="'Update details for ' . ($department->name ?? 'department') . '.'"
        />

        @include('livewire.hr.departments._form', [
            'isEditing' => true,
            'department' => $department ?? null,
        ])
    </div>
</div>
