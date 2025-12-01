<div>
    {{-- Flash Message --}}
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="Leave Requests"
            description="Manage employee leave requests and approvals."
        >
            <x-slot:actions>
                <a
                    href="{{ route('hr.leave-requests.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>New Request</span>
                </a>
            </x-slot:actions>
        </x-form.section-header>

        {{-- Stats Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-400/20 dark:text-amber-300">
                        @svg('heroicon-o-clock', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $pendingCount ?? 0 }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Pending</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-400/20 dark:text-emerald-300">
                        @svg('heroicon-o-check-circle', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $approvedCount ?? 0 }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Approved</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-400/20 dark:text-red-300">
                        @svg('heroicon-o-x-circle', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $rejectedCount ?? 0 }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Rejected</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60">
                        @svg('heroicon-o-calendar-days', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalCount ?? 0 }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Total</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Search & Filters --}}
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full md:max-w-xs">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search requests..."
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-white/40"
                        >
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/40">
                            @svg('heroicon-o-magnifying-glass', 'h-4 w-4')
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <x-table.export-dropdown export-pdf="exportPdf" export-excel="exportExcel" />
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Code</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Employee</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Leave Type</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Period</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Days</th>
                            <th class="px-6 py-3 font-medium text-slate-500 dark:text-white/60">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($leaveRequests as $request)
                            <tr
                                wire:key="request-{{ $request->id }}"
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('hr.leave-requests.edit', $request) }}'"
                            >
                                <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-white/70">{{ $request->code }}</td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $request->employee?->full_name ?? '—' }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $request->employee?->position?->title }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if ($request->leaveType?->color)
                                            <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $request->leaveType->color }}"></span>
                                        @endif
                                        <span class="text-slate-600 dark:text-white/70">{{ $request->leaveType?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">
                                    {{ $request->start_date?->format('d M') }} - {{ $request->end_date?->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-white/70">
                                    @php
                                        $days = $request->start_date && $request->end_date
                                            ? $request->start_date->diffInDays($request->end_date) + 1
                                            : 0;
                                    @endphp
                                    {{ $days }} {{ $days === 1 ? 'day' : 'days' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-400/20 dark:text-amber-300',
                                            'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300',
                                            'rejected' => 'bg-red-100 text-red-700 dark:bg-red-400/20 dark:text-red-300',
                                            'cancelled' => 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColors[$request->status] ?? $statusColors['pending'] }}">
                                        {{ str($request->status)->headline() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-white/50">
                                    No leave requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($leaveRequests->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
