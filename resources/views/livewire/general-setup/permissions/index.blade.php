<div class="space-y-6">
    {{-- Permissions --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Permissions</h2>
                <p class="text-xs text-slate-500 dark:text-white/60">System permissions grouped by module</p>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            @foreach ($permissionGroups as $group)
                <div class="rounded-xl border border-slate-200 dark:border-white/10">
                    <div class="border-b border-slate-100 px-4 py-3 dark:border-white/10">
                        <h3 class="font-medium text-slate-900 dark:text-white">{{ $group['name'] }}</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($group['permissions'] as $permission)
                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/80">
                                    @svg('heroicon-o-key', 'mr-1.5 h-3 w-3 text-slate-400 dark:text-white/40')
                                    {{ $permission }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Permission Matrix --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Permission Matrix</h2>
            <p class="text-xs text-slate-500 dark:text-white/60">Role-based permission overview</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                        <th class="px-5 py-3">PERMISSION</th>
                        <th class="px-5 py-3 text-center">SUPER ADMIN</th>
                        <th class="px-5 py-3 text-center">MANAGER</th>
                        <th class="px-5 py-3 text-center">CASHIER</th>
                        <th class="px-5 py-3 text-center">VIEWER</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-slate-900 dark:text-white">View Dashboard</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                    </tr>
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-slate-900 dark:text-white">Manage Users</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                    </tr>
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-slate-900 dark:text-white">Process Transactions</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                    </tr>
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-slate-900 dark:text-white">Process Refunds</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                    </tr>
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-slate-900 dark:text-white">View Reports</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                    </tr>
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-5 py-3 text-sm text-slate-900 dark:text-white">System Settings</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-check-circle', 'mx-auto h-5 w-5 text-emerald-500')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                        <td class="px-5 py-3 text-center">@svg('heroicon-s-x-circle', 'mx-auto h-5 w-5 text-slate-300 dark:text-white/20')</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
