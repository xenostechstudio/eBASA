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
            title="Payroll Item"
            :description="'Update details for ' . ($payrollItem->name ?? 'payroll item') . '.'"
        />

        @include('livewire.hr.payroll.payroll-items._form', [
            'isEditing' => true,
            'payrollItem' => $payrollItem ?? null,
        ])
    </div>
</div>
