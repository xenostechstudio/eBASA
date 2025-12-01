@php /** @var bool $isEditing */ @endphp

<div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="space-y-5 px-6 py-5">
        <div class="grid gap-6 md:grid-cols-2">
            {{-- Request Details --}}
            <div class="space-y-3 md:border-r md:border-dashed md:border-slate-200 md:pr-6 dark:md:border-white/10">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Request Details
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Code" model="form.code" placeholder="LR-001" col-span="6" :required="true" />

                    <div class="col-span-6">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Leave Type <span class="text-red-500">*</span></label>
                        <x-form.searchable-select
                            name="leave_type_id"
                            wire:model="form.leave_type_id"
                            :options="$leaveTypes"
                            value-key="id"
                            label-key="name"
                            sublabel-key="code"
                            placeholder="Select leave type"
                        />
                        @error('form.leave_type_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-12">
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-white/80">Employee <span class="text-red-500">*</span></label>
                        <x-form.searchable-select
                            name="employee_id"
                            wire:model="form.employee_id"
                            :options="$employees"
                            value-key="id"
                            label-key="full_name"
                            sublabel-key="code"
                            placeholder="Select employee"
                        />
                        @error('form.employee_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-form.input label="Reason" model="form.reason" :textarea="true" rows="3" placeholder="Reason for leave request" col-span="12" :required="true" />
                </div>
            </div>

            {{-- Period & Status --}}
            <div class="space-y-3">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Period & Status
                </p>

                <div class="grid gap-4 md:grid-cols-12">
                    <x-form.input label="Start Date" type="date" model="form.start_date" col-span="6" :required="true" />
                    <x-form.input label="End Date" type="date" model="form.end_date" col-span="6" :required="true" />

                    <x-form.select label="Status" model="form.status" placeholder="Select status" col-span="12" :required="true">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" class="bg-white dark:bg-slate-900">{{ str($status)->headline() }}</option>
                        @endforeach
                    </x-form.select>

                    @if ($isEditing && $this->form['status'] !== 'pending')
                        <div class="col-span-12 rounded-xl bg-slate-50 p-4 dark:bg-white/5">
                            <p class="text-xs font-medium text-slate-600 dark:text-white/60">Approval Information</p>
                            @if (isset($leaveRequest) && $leaveRequest->approved_by)
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">
                                    {{ $this->form['status'] === 'approved' ? 'Approved' : 'Rejected' }} by {{ $leaveRequest->approvedBy?->name }}
                                    on {{ $leaveRequest->approved_at?->format(config('basa.datetime_format')) }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <x-form.input label="Notes" model="form.notes" :textarea="true" rows="2" placeholder="Additional notes" col-span="12" />
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
        @if ($isEditing && isset($leaveRequest) && $leaveRequest)
            <div class="text-[11px] text-slate-400 dark:text-white/40">
                <p>
                    Created {{ optional($leaveRequest->created_at)->format(config('basa.datetime_format')) }}
                    by {{ optional($leaveRequest->createdBy)->name ?? 'System' }}
                </p>
                @if ($leaveRequest->updated_at)
                    <p>
                        Last updated {{ optional($leaveRequest->updated_at)->format(config('basa.datetime_format')) }}
                        by {{ optional($leaveRequest->updatedBy)->name ?? 'System' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 md:ml-auto">
            <a
                href="{{ route('hr.leave-requests') }}"
                class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
            >
                Cancel
            </a>

            @if ($isEditing && isset($leaveRequest) && $leaveRequest->status === 'pending')
                <button
                    type="button"
                    wire:click="approve"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-emerald-600 px-4 text-sm font-medium text-white transition hover:bg-emerald-700"
                >
                    @svg('heroicon-o-check', 'h-4 w-4')
                    <span>Approve</span>
                </button>

                <button
                    type="button"
                    wire:click="reject"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-red-600 px-4 text-sm font-medium text-white transition hover:bg-red-700"
                >
                    @svg('heroicon-o-x-mark', 'h-4 w-4')
                    <span>Reject</span>
                </button>
            @endif

            <button
                type="button"
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
            >
                <span wire:loading.remove wire:target="save">
                    @svg('heroicon-o-check', 'h-4 w-4')
                </span>
                <span wire:loading wire:target="save">
                    @svg('heroicon-o-arrow-path', 'h-4 w-4 animate-spin')
                </span>
                <span>{{ $isEditing ? 'Save Changes' : 'Submit Request' }}</span>
            </button>
        </div>
    </div>
</div>
