<div class="space-y-6">
    {{-- Activity Logs --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Activity Logs</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">Recent system activity and changes</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white dark:hover:bg-white/10"
                        >
                            @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                            <span>Export</span>
                            @svg('heroicon-s-chevron-down', 'h-4 w-4 text-slate-400')
                        </button>
                        <div
                            x-cloak
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 z-10 mt-2 w-44 origin-top-right rounded-xl border border-slate-200 bg-white p-1 shadow-lg dark:border-white/10 dark:bg-slate-900"
                        >
                            <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5">
                                @svg('heroicon-o-document-text', 'h-5 w-5 text-red-500')
                                <span>Export as PDF</span>
                            </button>
                            <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5">
                                @svg('heroicon-o-table-cells', 'h-5 w-5 text-emerald-500')
                                <span>Export as CSV</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-white/10">
            @foreach ($logs as $log)
                @php
                    $typeColors = [
                        'create' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400',
                        'update' => 'bg-sky-100 text-sky-600 dark:bg-sky-500/20 dark:text-sky-400',
                        'delete' => 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400',
                        'refund' => 'bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400',
                        'system' => 'bg-purple-100 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400',
                    ];
                    $typeIcons = [
                        'create' => 'heroicon-o-plus',
                        'update' => 'heroicon-o-pencil',
                        'delete' => 'heroicon-o-trash',
                        'refund' => 'heroicon-o-arrow-uturn-left',
                        'system' => 'heroicon-o-cog-6-tooth',
                    ];
                @endphp
                <div class="flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50 dark:hover:bg-white/5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $typeColors[$log['type']] ?? $typeColors['system'] }}">
                        @svg($typeIcons[$log['type']] ?? $typeIcons['system'], 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-900 dark:text-white">{{ $log['action'] }}</p>
                        <div class="mt-1 flex items-center gap-2 text-xs text-slate-500 dark:text-white/50">
                            <span>{{ $log['user'] }}</span>
                            <span>•</span>
                            <span>{{ $log['module'] }}</span>
                            <span>•</span>
                            <span>{{ $log['time'] }}</span>
                        </div>
                    </div>
                    <button class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white" title="View Details">
                        @svg('heroicon-o-eye', 'h-4 w-4')
                    </button>
                </div>
            @endforeach
        </div>

        <div class="border-t border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-500 dark:text-white/50">Showing 5 of 1,234 entries</p>
                <div class="flex items-center gap-2">
                    <button class="inline-flex h-8 items-center gap-1 rounded-lg border border-slate-200 px-3 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/5">
                        @svg('heroicon-o-chevron-left', 'h-3 w-3')
                        Previous
                    </button>
                    <button class="inline-flex h-8 items-center gap-1 rounded-lg border border-slate-200 px-3 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/5">
                        Next
                        @svg('heroicon-o-chevron-right', 'h-3 w-3')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
