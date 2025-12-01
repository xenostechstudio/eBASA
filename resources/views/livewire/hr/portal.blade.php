<div class="space-y-10">
    @if ($activeSection === 'overview')
        {{-- Stats Cards --}}
        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('hr.employees') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
                <div class="flex items-center gap-3">
                    @svg('heroicon-o-user-group', 'h-5 w-5 text-sky-500')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Employees</p>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['employees'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">People in directory</p>
            </a>

            <a href="{{ route('hr.departments') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
                <div class="flex items-center gap-3">
                    @svg('heroicon-o-building-office-2', 'h-5 w-5 text-emerald-500')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Departments</p>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['departments'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Functional units</p>
            </a>

            <a href="{{ route('hr.positions') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
                <div class="flex items-center gap-3">
                    @svg('heroicon-o-rectangle-group', 'h-5 w-5 text-amber-500')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Positions</p>
                </div>
                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['positions'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Job definitions</p>
            </a>
        </div>

        {{-- Quick Access --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Quick Access</h2>
            <p class="text-xs text-slate-500 dark:text-white/60">Jump into the most-used HR screens.</p>

            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('hr.employees') }}" class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-300 hover:shadow-sm dark:border-white/10 dark:hover:border-sky-500/30">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600 transition group-hover:scale-105 dark:bg-sky-500/20 dark:text-sky-400">
                            @svg('heroicon-o-user-group', 'h-5 w-5')
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-slate-900 dark:text-white">Employees</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Directory & profiles</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hr.departments') }}" class="group rounded-xl border border-slate-200 p-4 transition hover:border-emerald-300 hover:shadow-sm dark:border-white/10 dark:hover:border-emerald-500/30">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 transition group-hover:scale-105 dark:bg-emerald-500/20 dark:text-emerald-400">
                            @svg('heroicon-o-building-office-2', 'h-5 w-5')
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-slate-900 dark:text-white">Departments</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Org structure</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hr.positions') }}" class="group rounded-xl border border-slate-200 p-4 transition hover:border-amber-300 hover:shadow-sm dark:border-white/10 dark:hover:border-amber-500/30">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 transition group-hover:scale-105 dark:bg-amber-500/20 dark:text-amber-400">
                            @svg('heroicon-o-rectangle-group', 'h-5 w-5')
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-slate-900 dark:text-white">Positions</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Job templates</p>
                        </div>
                    </div>
                </a>

                <a href="#attendance" class="group rounded-xl border border-slate-200 p-4 transition hover:border-purple-300 hover:shadow-sm dark:border-white/10 dark:hover:border-purple-500/30">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600 transition group-hover:scale-105 dark:bg-purple-500/20 dark:text-purple-400">
                            @svg('heroicon-o-clock', 'h-5 w-5')
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-slate-900 dark:text-white">Attendance & Leave</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Daily operations</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @else
        <div class="text-left space-y-2">
            <p class="text-xs uppercase tracking-[0.5em] text-white/40">Human Resources</p>
            <div class="flex flex-wrap items-center gap-3 text-sm text-white/60">
                <span class="rounded-full border border-white/15 px-3 py-1 text-white/70">{{ ucfirst($activeSection) }} focus</span>
                <span>Centralized hub for branch staffing, payroll, and compliance.</span>
            </div>
            <h2 class="text-3xl font-semibold text-white">{{ $sections[$activeSection] ?? 'Overview' }}</h2>
        </div>
    @endif

    @if ($activeSection === 'people')
        @php
            $employees = [
                [
                    'name' => 'Aria Saputra',
                    'role' => 'Store Manager',
                    'branch' => 'Jakarta HQ',
                    'status' => 'Active',
                    'code' => 'EMP-204',
                    'start_date' => 'Apr 12, 2018',
                    'email' => 'aria.saputra@basa.id',
                    'band' => 'M3',
                ],
                [
                    'name' => 'Rani Maulida',
                    'role' => 'HR Specialist',
                    'branch' => 'Bandung',
                    'status' => 'On Leave',
                    'code' => 'EMP-311',
                    'start_date' => 'Aug 3, 2020',
                    'email' => 'rani.maulida@basa.id',
                    'band' => 'P2',
                ],
                [
                    'name' => 'Kevin Putra',
                    'role' => 'Warehouse Lead',
                    'branch' => 'Surabaya',
                    'status' => 'Probation',
                    'code' => 'EMP-429',
                    'start_date' => 'Oct 15, 2025',
                    'email' => 'kevin.putra@basa.id',
                    'band' => 'P1',
                ],
                [
                    'name' => 'Maya Rahma',
                    'role' => 'HRBP Jakarta',
                    'branch' => 'Jakarta HQ',
                    'status' => 'Active',
                    'code' => 'EMP-118',
                    'start_date' => 'Jan 7, 2016',
                    'email' => 'maya.rahma@basa.id',
                    'band' => 'M4',
                ],
                [
                    'name' => 'Samuel Wira',
                    'role' => 'Compensation Lead',
                    'branch' => 'HQ',
                    'status' => 'Active',
                    'code' => 'EMP-275',
                    'start_date' => 'May 28, 2019',
                    'email' => 'samuel.wira@basa.id',
                    'band' => 'M2',
                ],
            ];
            $teams = [
                ['name' => 'Retail Ops', 'lead' => 'Aria Saputra', 'members' => 24],
                ['name' => 'People Operations', 'lead' => 'Rani Maulida', 'members' => 12],
                ['name' => 'Procurement', 'lead' => 'Kevin Putra', 'members' => 9],
            ];
            $orgHighlights = [
                ['title' => 'Branches reporting to HQ', 'value' => 12],
                ['title' => 'Open leadership seats', 'value' => 2],
                ['title' => 'Avg. span of control', 'value' => '1:6'],
            ];
            $statusColors = [
                'Active' => 'bg-emerald-300/15 text-emerald-300',
                'On Leave' => 'bg-amber-300/15 text-amber-200',
                'Probation' => 'bg-sky-300/15 text-sky-200',
                'Inactive' => 'bg-white/10 text-white/60',
            ];
            $featuredEmployee = $employees[0];
            $spotlightTimeline = [
                ['label' => 'Latest Check-in', 'value' => 'Nov 18', 'detail' => 'Leadership review completed'],
                ['label' => 'Branch assignment', 'value' => 'Jakarta HQ', 'detail' => 'Rotated Q1 2024'],
                ['label' => 'Next touchpoint', 'value' => 'Feb 02', 'detail' => 'Career conversation'],
            ];
            $quickActions = [
                ['label' => 'Log movement', 'description' => 'Transfers, promotions, demotions'],
                ['label' => 'Request documents', 'description' => 'IDs, contracts, BPJS'],
                ['label' => 'Assign mentor', 'description' => 'Pair with leadership program'],
            ];
        @endphp

        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.4em] text-white/40">People sub-nav</p>
                @foreach ($peopleTabs as $key => $label)
                    <button type="button"
                        wire:click="setPeopleTab('{{ $key }}')"
                        class="rounded-full px-4 py-2 text-sm transition {{ $activePeopleTab === $key ? 'bg-white text-slate-900' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="mt-6 space-y-4">
                @if ($activePeopleTab === 'employees')
                    <div class="space-y-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Directory</p>
                                <h3 class="text-2xl font-semibold text-white">Employee roster</h3>
                                <p class="text-sm text-white/60">182 active · 12 contracts due for renewal</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                @if (!empty($employeeDirectoryRoute))
                                    <a href="{{ $employeeDirectoryRoute }}"
                                        class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-white/20">Open full directory</a>
                                @endif
                                <button class="rounded-full border border-white/30 px-4 py-2 text-sm text-white/80 hover:border-white/60">Add employee</button>
                                <button class="rounded-full border border-white/15 px-4 py-2 text-sm text-white/60 hover:border-white/40">Share snapshot</button>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <span class="rounded-full bg-white text-xs font-semibold uppercase tracking-[0.3em] text-slate-900 px-4 py-1.5">All</span>
                            <span class="rounded-full border border-white/20 px-4 py-1.5 text-xs uppercase tracking-[0.3em] text-white/60">HQ</span>
                            <span class="rounded-full border border-white/20 px-4 py-1.5 text-xs uppercase tracking-[0.3em] text-white/60">Bandung</span>
                            <span class="rounded-full border border-white/20 px-4 py-1.5 text-xs uppercase tracking-[0.3em] text-white/60">Surabaya</span>
                            <span class="rounded-full border border-white/20 px-4 py-1.5 text-xs uppercase tracking-[0.3em] text-white/60">Retail Floor</span>
                        </div>

                        <div class="grid gap-6 lg:grid-cols-3">
                            <div class="lg:col-span-2 space-y-4">
                                <div class="rounded-[28px] border border-white/10 bg-slate-900/30 p-4">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <input type="text" placeholder="Search name, role, branch"
                                            class="w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-2 text-sm text-white/80 placeholder:text-white/40 focus:border-white/40 focus:outline-none focus:ring-0 sm:w-auto sm:flex-1" />
                                        <button class="rounded-2xl border border-white/20 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/70">Filters</button>
                                        <button class="rounded-2xl border border-white/20 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/70">Saved view</button>
                                    </div>
                                </div>

                                <div class="overflow-hidden rounded-[28px] border border-white/10 bg-slate-900/40">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr class="text-left text-white/50 text-xs uppercase tracking-[0.3em]">
                                                <th class="px-5 py-4">Employee</th>
                                                <th class="px-5 py-4">Location</th>
                                                <th class="px-5 py-4">Band</th>
                                                <th class="px-5 py-4">Start</th>
                                                <th class="px-5 py-4 text-right">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-white/5">
                                            @foreach ($employees as $employee)
                                                <tr class="hover:bg-white/5">
                                                    <td class="px-5 py-4">
                                                        <p class="font-semibold text-white">{{ $employee['name'] }}</p>
                                                        <p class="text-xs text-white/50">{{ $employee['role'] }}</p>
                                                    </td>
                                                    <td class="px-5 py-4 text-white/80">{{ $employee['branch'] }}</td>
                                                    <td class="px-5 py-4 text-white/80">{{ $employee['band'] }}</td>
                                                    <td class="px-5 py-4 text-white/60">{{ $employee['start_date'] }}</td>
                                                    <td class="px-5 py-4 text-right">
                                                        <span class="rounded-full px-3 py-1 text-xs {{ $statusColors[$employee['status']] ?? 'bg-white/10 text-white/70' }}">{{ $employee['status'] }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Spotlight</p>
                                    <div class="mt-3 space-y-1">
                                        <h4 class="text-xl font-semibold text-white">{{ $featuredEmployee['name'] }}</h4>
                                        <p class="text-sm text-white/60">{{ $featuredEmployee['role'] }} · {{ $featuredEmployee['branch'] }}</p>
                                        <p class="text-xs text-white/40">ID {{ $featuredEmployee['code'] }} · {{ $featuredEmployee['email'] }}</p>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        @foreach ($spotlightTimeline as $event)
                                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-3">
                                                <p class="text-xs uppercase tracking-[0.35em] text-white/40">{{ $event['label'] }}</p>
                                                <p class="text-sm font-semibold text-white">{{ $event['value'] }}</p>
                                                <p class="text-xs text-white/60">{{ $event['detail'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="rounded-[28px] border border-white/10 bg-slate-900/40 p-5">
                                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Actions</p>
                                    <div class="mt-4 space-y-3">
                                        @foreach ($quickActions as $action)
                                            <button class="w-full text-left rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 transition hover:border-white/30">
                                                <p class="text-sm font-semibold text-white">{{ $action['label'] }}</p>
                                                <p class="text-xs text-white/60">{{ $action['description'] }}</p>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($activePeopleTab === 'teams')
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ($teams as $team)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-lg font-semibold text-white">{{ $team['name'] }}</p>
                                <p class="text-sm text-white/60">Lead: {{ $team['lead'] }}</p>
                                <p class="text-xs text-white/50 mt-2">{{ $team['members'] }} members</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ($orgHighlights as $highlight)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4 text-center">
                                <p class="text-sm uppercase tracking-[0.3em] text-white/50">{{ $highlight['title'] }}</p>
                                <p class="mt-3 text-4xl font-semibold text-white">{{ $highlight['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @elseif ($activeSection === 'attendance')
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.4em] text-white/40">Attendance sub-nav</p>
                @foreach ($attendanceTabs as $key => $label)
                    <button type="button" wire:click="setAttendanceTab('{{ $key }}')"
                        class="rounded-full px-4 py-2 text-sm transition {{ $activeAttendanceTab === $key ? 'bg-white text-slate-900' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @switch($activeAttendanceTab)
                    @case('timesheets')
                        @foreach ([['label' => 'Jakarta HQ', 'value' => '98% submitted'], ['label' => 'Bandung', 'value' => '94% submitted'], ['label' => 'Surabaya', 'value' => '100% submitted']] as $item)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-sm text-white/60">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $item['value'] }}</p>
                                <p class="text-xs text-white/50">Timesheet coverage</p>
                            </div>
                        @endforeach
                        @break
                    @case('exceptions')
                        @foreach ([['label' => 'Late arrivals', 'value' => 12], ['label' => 'Missing punches', 'value' => 6], ['label' => 'Overtime approvals', 'value' => 4]] as $item)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-sm text-white/60">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $item['value'] }}</p>
                                <p class="text-xs text-white/50">Items awaiting review</p>
                            </div>
                        @endforeach
                        @break
                    @default
                        @foreach ([['label' => 'Branch audits passed', 'value' => '11/12'], ['label' => 'Policy updates', 'value' => '2 pending'], ['label' => 'Training completion', 'value' => '87%']] as $item)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-sm text-white/60">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $item['value'] }}</p>
                                <p class="text-xs text-white/50">Compliance snapshot</p>
                            </div>
                        @endforeach
                @endswitch
            </div>
        </div>
    @elseif ($activeSection === 'payroll')
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.4em] text-white/40">Payroll sub-nav</p>
                @foreach ($payrollTabs as $key => $label)
                    <button type="button" wire:click="setPayrollTab('{{ $key }}')"
                        class="rounded-full px-4 py-2 text-sm transition {{ $activePayrollTab === $key ? 'bg-white text-slate-900' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="mt-6 space-y-4">
                @switch($activePayrollTab)
                    @case('runs')
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ([['title' => 'November Payroll', 'status' => 'Processing', 'eta' => '2 days'], ['title' => 'Bonus Cycle', 'status' => 'Draft', 'eta' => 'Due Dec 5']] as $run)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-5">
                                    <p class="text-lg font-semibold text-white">{{ $run['title'] }}</p>
                                    <p class="text-sm text-white/60">Status: {{ $run['status'] }}</p>
                                    <p class="text-xs text-white/50">ETA {{ $run['eta'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        @break
                    @case('adjustments')
                        <div class="space-y-3">
                            @foreach ([['label' => 'Overtime approvals', 'value' => '6 pending'], ['label' => 'Allowance updates', 'value' => '3 drafts']] as $adj)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-white/60">{{ $adj['label'] }}</p>
                                        <p class="text-xl text-white">{{ $adj['value'] }}</p>
                                    </div>
                                    <button class="rounded-full border border-white/20 px-3 py-1 text-xs text-white/70">Review</button>
                                </div>
                            @endforeach
                        </div>
                        @break
                    @default
                        <div class="grid gap-4 md:grid-cols-3">
                            @foreach ([['label' => 'Bank Processing', 'value' => 'BCA'], ['label' => 'Next payout', 'value' => 'Dec 1'], ['label' => 'Variance', 'value' => '+1.2%']] as $item)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                    <p class="text-sm text-white/60">{{ $item['label'] }}</p>
                                    <p class="text-2xl text-white">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                @endswitch
            </div>
        </div>
    @elseif ($activeSection === 'leave')
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.4em] text-white/40">Leave sub-nav</p>
                @foreach ($leaveTabs as $key => $label)
                    <button type="button" wire:click="setLeaveTab('{{ $key }}')"
                        class="rounded-full px-4 py-2 text-sm transition {{ $activeLeaveTab === $key ? 'bg-white text-slate-900' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="mt-6 space-y-4">
                @switch($activeLeaveTab)
                    @case('requests')
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ([['name' => 'Maya Rahma', 'type' => 'Annual Leave', 'dates' => 'Dec 1-5'], ['name' => 'Samuel Wira', 'type' => 'Sick Leave', 'dates' => 'Nov 22']] as $leave)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                    <p class="text-lg font-semibold text-white">{{ $leave['name'] }}</p>
                                    <p class="text-sm text-white/60">{{ $leave['type'] }}</p>
                                    <p class="text-xs text-white/50">{{ $leave['dates'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        @break
                    @case('types')
                        <div class="grid gap-4 md:grid-cols-3">
                            @foreach ([['label' => 'Annual Leave', 'value' => '12 days'], ['label' => 'Sick Leave', 'value' => 'Unlimited'], ['label' => 'Maternity', 'value' => '90 days']] as $type)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4 text-center">
                                    <p class="text-sm text-white/60">{{ $type['label'] }}</p>
                                    <p class="text-2xl text-white">{{ $type['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        @break
                @endswitch
            </div>
        </div>
    @endif

    @if ($activeSection !== 'overview')
        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($stats as $stat)
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5 text-left shadow-inner shadow-white/5">
                    <p class="text-sm text-white/60">{{ $stat['label'] }}</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-xs text-emerald-300">{{ $stat['trend'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 text-left">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Upcoming Reviews</h3>
                        <p class="text-sm text-white/60">Performance checkpoints happening soon.</p>
                    </div>
                    <button class="text-xs uppercase tracking-[0.3em] text-white/60 hover:text-white">View All</button>
                </div>
                <ul class="mt-5 space-y-4">
                    @foreach ($upcomingReviews as $review)
                        <li class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                            <div>
                                <p class="font-medium text-white">{{ $review['name'] }}</p>
                                <p class="text-xs text-white/50">{{ $review['role'] }}</p>
                            </div>
                            <span class="text-sm text-white/70">{{ $review['date'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 text-left">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Recent Hires</h3>
                        <p class="text-sm text-white/60">Latest team members across branches.</p>
                    </div>
                    <button class="text-xs uppercase tracking-[0.3em] text-white/60 hover:text-white">Manage</button>
                </div>
                <ul class="mt-5 space-y-4">
                    @foreach ($recentHires as $hire)
                        <li class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                            <div>
                                <p class="font-medium text-white">{{ $hire['name'] }}</p>
                                <p class="text-xs text-white/50">{{ $hire['role'] }} · {{ $hire['branch'] }}</p>
                            </div>
                            <span class="text-sm text-white/70">Onboarding</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="rounded-[32px] border border-white/10 bg-gradient-to-br from-slate-900/80 to-slate-900/30 p-6 text-left">
            <h3 class="text-lg font-semibold">Filament vs Livewire?</h3>
            <p class="mt-2 text-white/70">Use this Livewire portal for high-level navigation and quick actions. When deeper record management is needed, hand off to Filament panels such as Inventory or future HR CRUD screens. This keeps the UX cohesive while still leveraging Filament's admin power.</p>
        </div>
    @endif
</div>
