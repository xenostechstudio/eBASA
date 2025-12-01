<div class="space-y-6">
    {{-- Roles --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Roles</h2>
                <p class="text-xs text-slate-500 dark:text-white/60">Manage user roles and their permissions</p>
            </div>
            <a
                href="{{ route('general-setup.roles.create') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
            >
                @svg('heroicon-o-plus', 'h-4 w-4')
                <span>Add Role</span>
            </a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($roles as $role)
                @php
                    $colors = [
                        'red' => 'border-red-200 bg-red-50/50 dark:border-red-500/20 dark:bg-red-500/10',
                        'amber' => 'border-amber-200 bg-amber-50/50 dark:border-amber-500/20 dark:bg-amber-500/10',
                        'emerald' => 'border-emerald-200 bg-emerald-50/50 dark:border-emerald-500/20 dark:bg-emerald-500/10',
                        'sky' => 'border-sky-200 bg-sky-50/50 dark:border-sky-500/20 dark:bg-sky-500/10',
                        'slate' => 'border-slate-200 bg-slate-50/50 dark:border-white/10 dark:bg-white/5',
                    ];
                    $iconColors = [
                        'red' => 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400',
                        'amber' => 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400',
                        'emerald' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400',
                        'sky' => 'bg-sky-100 text-sky-600 dark:bg-sky-500/20 dark:text-sky-400',
                        'slate' => 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60',
                    ];
                @endphp
                <div class="rounded-xl border p-4 {{ $colors[$role['color']] ?? $colors['slate'] }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $iconColors[$role['color']] ?? $iconColors['slate'] }}">
                                @svg('heroicon-o-shield-check', 'h-5 w-5')
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-900 dark:text-white">{{ $role['name'] }}</h3>
                                <p class="text-xs text-slate-500 dark:text-white/60">{{ $role['description'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-white/50">{{ $role['users_count'] }} users</span>
                        <div class="flex items-center gap-1">
                            <a
                                href="{{ route('general-setup.roles.edit', ['role' => $role['id']]) }}"
                                class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white/50 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                title="Edit role"
                            >
                                @svg('heroicon-o-pencil', 'h-4 w-4')
                            </a>
                            <a
                                href="{{ route('general-setup.roles.permissions', ['role' => $role['id']]) }}"
                                class="rounded-lg p-1.5 text-slate-400 transition hover:bg-white/50 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                title="Manage permissions"
                            >
                                @svg('heroicon-o-key', 'h-4 w-4')
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Role Hierarchy --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Role Hierarchy</h2>
        <p class="text-xs text-slate-500 dark:text-white/60">Understanding role inheritance and access levels</p>

        <div class="mt-6">
            <div class="relative pl-6">
                {{-- Vertical line --}}
                <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-slate-200 dark:bg-white/10"></div>

                <div class="space-y-4">
                    <div class="relative flex items-center gap-4">
                        <div class="absolute -left-6 top-1/2 -translate-y-1/2 flex h-5 w-5 items-center justify-center rounded-full border-2 border-red-500 bg-white dark:bg-slate-900">
                            <div class="h-2 w-2 rounded-full bg-red-500"></div>
                        </div>
                        <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-white/10 dark:bg-white/5">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Super Admin</p>
                            <p class="text-xs text-slate-500 dark:text-white/60">Full system access, can manage all roles</p>
                        </div>
                    </div>
                    <div class="relative flex items-center gap-4">
                        <div class="absolute -left-6 top-1/2 -translate-y-1/2 flex h-5 w-5 items-center justify-center rounded-full border-2 border-amber-500 bg-white dark:bg-slate-900">
                            <div class="h-2 w-2 rounded-full bg-amber-500"></div>
                        </div>
                        <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-white/10 dark:bg-white/5">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Manager</p>
                            <p class="text-xs text-slate-500 dark:text-white/60">Branch management, reports, staff oversight</p>
                        </div>
                    </div>
                    <div class="relative flex items-center gap-4">
                        <div class="absolute -left-6 top-1/2 -translate-y-1/2 flex h-5 w-5 items-center justify-center rounded-full border-2 border-emerald-500 bg-white dark:bg-slate-900">
                            <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                        </div>
                        <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-white/10 dark:bg-white/5">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Cashier</p>
                            <p class="text-xs text-slate-500 dark:text-white/60">POS operations, transaction processing</p>
                        </div>
                    </div>
                    <div class="relative flex items-center gap-4">
                        <div class="absolute -left-6 top-1/2 -translate-y-1/2 flex h-5 w-5 items-center justify-center rounded-full border-2 border-slate-400 bg-white dark:bg-slate-900">
                            <div class="h-2 w-2 rounded-full bg-slate-400"></div>
                        </div>
                        <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-white/10 dark:bg-white/5">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Viewer</p>
                            <p class="text-xs text-slate-500 dark:text-white/60">Read-only access to reports and data</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
