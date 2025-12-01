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
            title="Payroll Group"
            :description="'Update details for ' . ($payrollGroup->name ?? 'payroll group') . '.'"
        />

        @include('livewire.hr.payroll.payroll-groups._form', [
            'isEditing' => true,
            'payrollGroup' => $payrollGroup ?? null,
        ])

        {{-- Employees Relation Manager --}}
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Assigned Employees</h3>
                        <p class="text-xs text-slate-500 dark:text-white/60">Employees in this payroll group ({{ $employees->count() }})</p>
                    </div>
                </div>
            </div>

            {{-- Add Employee --}}
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex flex-col gap-3 md:flex-row md:items-end">
                    <div class="flex-1">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Add Employee</label>
                        <x-form.searchable-select
                            name="selected_employee"
                            wire:model="selectedEmployeeId"
                            :options="$availableEmployees"
                            value-key="id"
                            label-key="full_name"
                            sublabel-key="code"
                            placeholder="Select employee to add"
                        />
                    </div>
                    <button
                        type="button"
                        wire:click="addEmployee"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                    >
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add</span>
                    </button>
                </div>
            </div>

            {{-- Search --}}
            <div class="border-b border-slate-200 px-6 py-3 dark:border-white/10">
                <div class="relative w-full md:max-w-xs">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="employeeSearch"
                        placeholder="Search employees..."
                        class="h-9 w-full rounded-lg border border-slate-200 bg-white pl-9 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-white/40"
                    >
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/40">
                        @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                    </span>
                </div>
            </div>

            {{-- Employees Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Employee</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Position</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Department</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Base Salary</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($employees as $employee)
                            <tr wire:key="employee-{{ $employee->id }}" class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $employee->full_name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $employee->code }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $employee->position?->title ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">{{ $employee->department?->name ?? '—' }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                    Rp {{ number_format($employee->base_salary ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        type="button"
                                        wire:click="removeEmployee({{ $employee->id }})"
                                        wire:confirm="Remove this employee from the payroll group?"
                                        class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                        title="Remove"
                                    >
                                        @svg('heroicon-o-trash', 'h-4 w-4')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    @svg('heroicon-o-users', 'mx-auto h-10 w-10 text-slate-300 dark:text-white/20')
                                    <p class="mt-2 text-sm text-slate-500 dark:text-white/50">No employees assigned</p>
                                    <p class="text-xs text-slate-400 dark:text-white/40">Add employees using the form above</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
