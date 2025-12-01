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
            description="Create a new payroll item for earnings or deductions."
        />

        @include('livewire.hr.payroll.payroll-items._form', [
            'isEditing' => false,
            'payrollItem' => null,
        ])
    </div>
</div>
