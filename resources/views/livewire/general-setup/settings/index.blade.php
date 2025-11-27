<div class="space-y-6">
    @foreach ($settingGroups as $group)
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60">
                    @svg($group['icon'], 'h-5 w-5')
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $group['name'] }}</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">{{ $group['name'] }} configuration settings</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @foreach ($group['settings'] as $setting)
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 p-4 dark:border-white/10">
                        <div>
                            <h3 class="text-sm font-medium text-slate-900 dark:text-white">{{ $setting['label'] }}</h3>
                            <p class="text-xs text-slate-500 dark:text-white/60">{{ $setting['key'] }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-600 dark:text-white/70">{{ $setting['value'] }}</span>
                            <button class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white" title="Edit">
                                @svg('heroicon-o-pencil', 'h-4 w-4')
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Danger Zone --}}
    <div class="rounded-2xl border border-red-300 bg-red-50 p-6 dark:border-red-500/40 dark:bg-red-950/50">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-500/30 dark:text-red-400">
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5')
            </div>
            <div>
                <h2 class="text-lg font-semibold text-red-900 dark:text-red-100">Danger Zone</h2>
                <p class="text-xs text-red-600 dark:text-red-300/80">Irreversible actions</p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <div class="flex items-center justify-between rounded-xl border border-red-200 bg-white p-4 dark:border-red-500/30 dark:bg-red-950/30">
                <div>
                    <h3 class="text-sm font-medium text-slate-900 dark:text-white">Clear Cache</h3>
                    <p class="text-xs text-slate-500 dark:text-red-200/60">Clear all application cache</p>
                </div>
                <button class="inline-flex h-9 items-center gap-2 rounded-lg border border-red-300 bg-white px-3 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-500/50 dark:bg-red-500/20 dark:text-red-300 dark:hover:bg-red-500/30">
                    @svg('heroicon-o-trash', 'h-4 w-4')
                    <span>Clear</span>
                </button>
            </div>

            <div class="flex items-center justify-between rounded-xl border border-red-200 bg-white p-4 dark:border-red-500/30 dark:bg-red-950/30">
                <div>
                    <h3 class="text-sm font-medium text-slate-900 dark:text-white">Reset Settings</h3>
                    <p class="text-xs text-slate-500 dark:text-red-200/60">Reset all settings to default values</p>
                </div>
                <button class="inline-flex h-9 items-center gap-2 rounded-lg border border-red-300 bg-white px-3 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-500/50 dark:bg-red-500/20 dark:text-red-300 dark:hover:bg-red-500/30">
                    @svg('heroicon-o-arrow-path', 'h-4 w-4')
                    <span>Reset</span>
                </button>
            </div>
        </div>
    </div>
</div>
