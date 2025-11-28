<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <a href="{{ route('general-setup.users.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-users', 'h-5 w-5 text-sky-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Users</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['users']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Registered accounts</p>
        </a>
        <a href="{{ route('general-setup.products.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-cube', 'h-5 w-5 text-emerald-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Products</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['products']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Products</p>
        </a>
        <a href="{{ route('general-setup.product-categories.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-tag', 'h-5 w-5 text-amber-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Categories</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['categories']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Product categories</p>
        </a>
    </div>

    {{-- Quick Links --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Quick Access</h2>
        <p class="text-xs text-slate-500 dark:text-white/60">Manage your system configuration</p>

        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('general-setup.users.index') }}" class="group rounded-xl border border-slate-200 p-4 transition hover:border-sky-300 hover:shadow-sm dark:border-white/10 dark:hover:border-sky-500/30">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600 transition group-hover:scale-105 dark:bg-sky-500/20 dark:text-sky-400">
                        @svg('heroicon-o-users', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Users</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Manage user accounts</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('general-setup.products.index') }}" class="group rounded-xl border border-slate-200 p-4 transition hover:border-emerald-300 hover:shadow-sm dark:border-white/10 dark:hover:border-emerald-500/30">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 transition group-hover:scale-105 dark:bg-emerald-500/20 dark:text-emerald-400">
                        @svg('heroicon-o-cube', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Products</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Product catalog</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('general-setup.roles.index') }}" class="group rounded-xl border border-slate-200 p-4 transition hover:border-amber-300 hover:shadow-sm dark:border-white/10 dark:hover:border-amber-500/30">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 transition group-hover:scale-105 dark:bg-amber-500/20 dark:text-amber-400">
                        @svg('heroicon-o-shield-check', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Roles</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Access control roles</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('general-setup.settings.index') }}" class="group rounded-xl border border-slate-200 p-4 transition hover:border-purple-300 hover:shadow-sm dark:border-white/10 dark:hover:border-purple-500/30">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600 transition group-hover:scale-105 dark:bg-purple-500/20 dark:text-purple-400">
                        @svg('heroicon-o-cog-6-tooth', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Settings</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">System configuration</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Activity</h2>
                <p class="text-xs text-slate-500 dark:text-white/60">Latest system changes</p>
            </div>
            <a href="{{ route('general-setup.activity-logs.index') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-white/60 dark:hover:text-white">View all →</a>
        </div>

        <div class="mt-4 space-y-3">
            <div class="flex items-center gap-4 rounded-xl bg-slate-50 p-3 dark:bg-white/5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-900 dark:text-white">New user registered</p>
                    <p class="text-xs text-slate-500 dark:text-white/50">2 minutes ago</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-xl bg-slate-50 p-3 dark:bg-white/5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-500/20 dark:text-sky-400">
                    @svg('heroicon-o-pencil', 'h-4 w-4')
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-900 dark:text-white">Product updated</p>
                    <p class="text-xs text-slate-500 dark:text-white/50">15 minutes ago</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-xl bg-slate-50 p-3 dark:bg-white/5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400">
                    @svg('heroicon-o-cog-6-tooth', 'h-4 w-4')
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-900 dark:text-white">Settings changed</p>
                    <p class="text-xs text-slate-500 dark:text-white/50">1 hour ago</p>
                </div>
            </div>
        </div>
    </div>
</div>
