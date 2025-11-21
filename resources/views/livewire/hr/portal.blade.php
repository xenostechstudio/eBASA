<div class="space-y-10">
    @if ($activeSection === 'overview')
        @php
            $workflowQueue = [
                ['label' => 'Leave approvals', 'value' => '5 pending', 'pill' => 'Approvals'],
                ['label' => 'Offer letters', 'value' => '3 awaiting sign', 'pill' => 'Hiring'],
                ['label' => 'Payroll variance check', 'value' => '+1.2%', 'pill' => 'Finance'],
            ];
            $riskAlerts = [
                ['label' => 'Attendance policy refresh', 'detail' => 'Draft awaiting sign-off', 'severity' => 'Due in 3 days'],
                ['label' => 'Payroll audit pack', 'detail' => 'Data gathering in progress', 'severity' => 'Week 48 target'],
                ['label' => 'Leave policy localization', 'detail' => 'Bahasa ID translation pending', 'severity' => 'Needs owner'],
            ];
            $operationalCheckpoints = [
                ['label' => 'Attendance exceptions', 'value' => 6, 'descriptor' => 'target < 8'],
                ['label' => 'Payroll adjustments', 'value' => '3 drafts', 'descriptor' => 'review today'],
                ['label' => 'Leave SLA compliance', 'value' => '92%', 'descriptor' => 'goal 95%'],
            ];
            $focusAreas = [
                'Leadership mobility rollout',
                'Seasonal payroll readiness',
                'Audit & compliance sprint',
            ];
        @endphp

        <section class="overflow-hidden rounded-[36px] border border-white/10 bg-gradient-to-br from-slate-900 via-slate-900/70 to-indigo-900/40 p-8 shadow-[0_45px_120px_-60px_rgba(15,23,42,0.9)]">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
                <div class="space-y-5 lg:flex-1">
                    <p class="text-xs uppercase tracking-[0.6em] text-white/50">Human Resource</p>
                    <h1 class="text-4xl font-semibold text-white md:text-5xl">Unified people operations cockpit</h1>
                    <p class="text-base text-white/70">Monitor hiring velocity, payroll readiness, and compliance risk from a single glass panel before drilling into each HR area.</p>
                    <div class="flex flex-wrap gap-3">
                        <button class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-white/30">Add employee</button>
                        <button class="rounded-full border border-white/30 px-5 py-2 text-sm font-semibold text-white/80 hover:border-white/60">Review payroll run</button>
                        <button class="rounded-full border border-white/15 px-5 py-2 text-sm text-white/60 hover:border-white/40">Configure leave</button>
                    </div>
                </div>

                <div class="w-full max-w-md rounded-3xl border border-white/10 bg-white/5 p-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/40">Pulse metrics</p>
                    <div class="mt-4 space-y-4">
                        @foreach ($stats as $stat)
                            <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <p class="text-xs uppercase tracking-[0.35em] text-white/50">{{ $stat['label'] }}</p>
                                <p class="mt-2 text-3xl font-semibold text-white">{{ $stat['value'] }}</p>
                                <p class="text-xs text-emerald-300">{{ $stat['trend'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            @foreach ($focusAreas as $focus)
                <span class="rounded-full border border-white/20 px-4 py-1.5 text-xs uppercase tracking-[0.35em] text-white/60">{{ $focus }}</span>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 text-left">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/40">Talent</p>
                        <h3 class="text-lg font-semibold text-white">Upcoming Reviews</h3>
                    </div>
                    <button class="text-xs uppercase tracking-[0.3em] text-white/60 hover:text-white">View all</button>
                </div>
                <ul class="mt-5 space-y-4">
                    @foreach ($upcomingReviews as $review)
                        <li class="flex items-center justify-between rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3">
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
                        <p class="text-xs uppercase tracking-[0.35em] text-white/40">Growth</p>
                        <h3 class="text-lg font-semibold text-white">Recent Hires</h3>
                    </div>
                    <button class="text-xs uppercase tracking-[0.3em] text-white/60 hover:text-white">Manage</button>
                </div>
                <ul class="mt-5 space-y-4">
                    @foreach ($recentHires as $hire)
                        <li class="flex items-center justify-between rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3">
                            <div>
                                <p class="font-medium text-white">{{ $hire['name'] }}</p>
                                <p class="text-xs text-white/50">{{ $hire['role'] }} · {{ $hire['branch'] }}</p>
                            </div>
                            <span class="rounded-full border border-white/20 px-3 py-1 text-xs text-white/70">Onboarding</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 text-left">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-white/40">Queues</p>
                        <h3 class="text-lg font-semibold text-white">Workstream Monitor</h3>
                    </div>
                    <button class="text-xs uppercase tracking-[0.3em] text-white/60 hover:text-white">Prioritize</button>
                </div>
                <ul class="mt-5 space-y-4">
                    @foreach ($workflowQueue as $item)
                        <li class="flex items-start justify-between rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3">
                            <div>
                                <p class="font-medium text-white">{{ $item['label'] }}</p>
                                <p class="text-xs text-white/50">{{ $item['value'] }}</p>
                            </div>
                            <span class="rounded-full border border-white/20 px-3 py-1 text-[11px] uppercase tracking-[0.3em] text-white/60">{{ $item['pill'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Compliance</p>
                <h3 class="text-lg font-semibold text-white">Risk radar</h3>
                <div class="mt-5 space-y-4">
                    @foreach ($riskAlerts as $alert)
                        <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-4">
                            <p class="text-sm font-medium text-white">{{ $alert['label'] }}</p>
                            <p class="text-xs text-white/50">{{ $alert['detail'] }}</p>
                            <p class="mt-2 text-xs text-amber-300">{{ $alert['severity'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-slate-900/40 p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Operations</p>
                <h3 class="text-lg font-semibold text-white">Control tower</h3>
                <div class="mt-5 space-y-4">
                    @foreach ($operationalCheckpoints as $checkpoint)
                        <div class="flex items-center justify-between rounded-2xl border border-white/5 bg-slate-900/50 px-4 py-3">
                            <div>
                                <p class="text-sm text-white/70">{{ $checkpoint['label'] }}</p>
                                <p class="text-xs text-white/40">{{ $checkpoint['descriptor'] }}</p>
                            </div>
                            <p class="text-lg font-semibold text-white">{{ $checkpoint['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[32px] border border-white/10 bg-gradient-to-br from-indigo-700/40 via-slate-900 to-slate-900/80 p-6 text-left">
                <h3 class="text-lg font-semibold text-white">Filament vs Livewire?</h3>
                <p class="mt-3 text-white/70">Use this Livewire cockpit for navigating strategy and quick approvals. When deeper record control is needed, route managers into Filament modules so the experience stays consistent while CRUD screens remain powerful.</p>
                <div class="mt-6 space-y-2 text-sm text-white/60">
                    <p>• Livewire → strategic overviews & blended workflows</p>
                    <p>• Filament → bulk records, audits, and historical data</p>
                </div>
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
