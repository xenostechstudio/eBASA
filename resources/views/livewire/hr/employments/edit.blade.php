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
            title="Employment Record"
            :description="'Update branch, position, and contract terms for ' . ($employee->full_name ?? 'employee') . '.'"
        />

        @include('livewire.hr.employments._form', [
            'isEditing' => true,
            'employee' => $employee ?? null,
        ])
    </div>
</div>
