<div class="space-y-6">
    {{-- Header --}}
    <x-form.section-header
        title="New Employee"
        description="Complete the wizard to add a new team member."
    />

    @if (session('status'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-300">
            @svg('heroicon-o-check-circle', 'h-5 w-5')
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @include('livewire.hr.employees._form', [
        'isEditing' => false,
        'employee' => null,
    ])
</div>
