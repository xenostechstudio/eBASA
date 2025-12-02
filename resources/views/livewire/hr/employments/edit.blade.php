<div>
    @if (session()->has('status'))
        <x-alert type="success">{{ session('status') }}</x-alert>
    @endif
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'">{{ $flash['message'] ?? '' }}</x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header with Employee Info --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <x-form.section-header
                    title="Employment Record"
                    :description="'Update branch, position, and contract terms for ' . ($employee->full_name ?? 'employee') . '.'"
                />
            </div>

            {{-- Employee Quick Info --}}
            @if ($employee)
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-600 dark:bg-white/10 dark:text-white/80">
                        {{ strtoupper(substr($employee->full_name ?? 'E', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $employee->full_name }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $employee->code }} · {{ $employee->email }}</p>
                    </div>
                </div>
            @endif
        </div>

        @include('livewire.hr.employments._form', [
            'isEditing' => true,
            'employee' => $employee ?? null,
        ])
    </div>
</div>
