@props([
    'branchShortcuts' => collect(),
    'activeBranchId' => 0,
])

@if ($branchShortcuts->isNotEmpty())
    <div class="fixed bottom-6 right-6 z-40" data-branch-switcher>
        <div class="relative">
            <button type="button" data-branch-trigger
                class="flex items-center gap-2 rounded-full border border-slate-200 bg-white/90 p-3 text-sm font-semibold text-slate-900 shadow-2xl shadow-slate-900/20 transition hover:bg-white dark:border-white/15 dark:bg-slate-900 dark:text-white">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-900 text-white dark:bg-white/10">
                    @svg('heroicon-s-building-storefront', 'h-5 w-5')
                </span>
                <span class="hidden sm:inline">{{ optional($branchShortcuts->firstWhere('id', $activeBranchId))->name ?? __('All branches') }}</span>
                <span class="sr-only">Switch branch</span>
            </button>

            <div data-branch-panel class="absolute bottom-16 right-0 hidden w-72 rounded-3xl border border-slate-200 bg-white/95 p-4 text-slate-900 shadow-2xl backdrop-blur dark:border-white/10 dark:bg-slate-900/95 dark:text-white">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/40">Branches</p>
                <p class="text-[11px] text-slate-500 dark:text-white/60">
                    Currently viewing:
                    <span class="font-semibold text-slate-900 dark:text-white">
                        {{ optional($branchShortcuts->firstWhere('id', $activeBranchId))->name ?? 'All' }}
                    </span>
                </p>
                <ul class="mt-3 max-h-96 space-y-2 overflow-y-auto pr-1">
                    <li>
                        <form method="POST" action="{{ route('branch.switch') }}">
                            @csrf
                            <input type="hidden" name="branch_id" value="all">
                            <button type="submit"
                                class="flex w-full items-center justify-between rounded-2xl border border-slate-200 px-3 py-2 text-left transition hover:border-slate-400 {{ $activeBranchId === 0 ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white text-slate-900 dark:bg-white/5 dark:text-white' }}">
                                <div>
                                    <p class="text-sm font-semibold">All branches</p>
                                    <p class="text-xs {{ $activeBranchId === 0 ? 'text-white/70 dark:text-slate-600' : 'text-slate-500 dark:text-white/60' }}">Company-wide view</p>
                                </div>
                                @if ($activeBranchId === 0)
                                    <span class="text-xs text-emerald-400" aria-label="Active branch">
                                        @svg('heroicon-s-check-circle', 'h-5 w-5')
                                    </span>
                                @endif
                            </button>
                        </form>
                    </li>
                    @foreach ($branchShortcuts as $branch)
                        <li>
                            <form method="POST" action="{{ route('branch.switch') }}">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <button type="submit"
                                    class="flex w-full items-center justify-between rounded-2xl border border-slate-200 px-3 py-2 text-left transition hover:border-slate-400 {{ $activeBranchId === $branch->id ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white text-slate-900 dark:bg-white/5 dark:text-white' }}">
                                    <div>
                                        <p class="text-sm font-semibold">{{ $branch->name }}</p>
                                        <p class="text-xs {{ $activeBranchId === $branch->id ? 'text-white/70 dark:text-slate-600' : 'text-slate-500 dark:text-white/60' }}">
                                            {{ $branch->city }}{{ $branch->province ? ', '.$branch->province : '' }}
                                        </p>
                                    </div>
                                    @if ($activeBranchId === $branch->id)
                                        <span class="text-xs text-emerald-400" aria-label="Active branch">
                                            @svg('heroicon-s-check-circle', 'h-5 w-5')
                                        </span>
                                    @endif
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-3 text-[11px] text-slate-500 dark:text-white/40">
                    Switching branches updates context for dashboards and quick stats.
                </p>
            </div>
        </div>
    </div>
@endif
