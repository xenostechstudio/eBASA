@props([
    'branchShortcuts' => collect(),
    'activeBranchId' => 0,
])

@if ($branchShortcuts->isNotEmpty())
    <div
        x-data="{
            open: false,
            minimized: localStorage.getItem('branchSwitcherMinimized') === 'true',
            toggle() {
                if (this.minimized) {
                    this.minimized = false;
                    localStorage.setItem('branchSwitcherMinimized', 'false');
                } else {
                    this.open = !this.open;
                }
            },
            minimize() {
                this.open = false;
                this.minimized = true;
                localStorage.setItem('branchSwitcherMinimized', 'true');
            }
        }"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        class="fixed bottom-6 right-6 z-40"
    >
        {{-- Minimized State: Small Icon Button --}}
        <div x-show="minimized" x-cloak class="relative">
            <button
                type="button"
                @click="toggle()"
                class="group flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-600 shadow-lg transition hover:bg-slate-50 hover:shadow-xl dark:border-white/15 dark:bg-slate-900 dark:text-white/70 dark:hover:bg-slate-800"
                title="Expand branch switcher"
            >
                @svg('heroicon-s-building-storefront', 'h-5 w-5')
            </button>
            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-slate-900 text-[9px] font-bold text-white dark:bg-white dark:text-slate-900">
                {{ $activeBranchId > 0 ? Str::substr(optional($branchShortcuts->firstWhere('id', $activeBranchId))->name ?? 'A', 0, 1) : 'A' }}
            </span>
        </div>

        {{-- Expanded State: Full Button --}}
        <div x-show="!minimized" x-cloak class="relative">
            <div class="flex items-center gap-1">
                {{-- Minimize Button --}}
                <button
                    type="button"
                    @click="minimize()"
                    class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow transition hover:bg-slate-50 hover:text-slate-600 dark:border-white/10 dark:bg-slate-900 dark:text-white/40 dark:hover:text-white/70"
                    title="Minimize"
                >
                    @svg('heroicon-o-minus', 'h-4 w-4')
                </button>

                {{-- Main Branch Button --}}
                <button
                    type="button"
                    @click="toggle()"
                    class="flex items-center gap-2 rounded-full border border-slate-300 bg-white py-2 pl-3 pr-4 text-sm font-semibold text-slate-900 shadow-lg transition hover:bg-slate-50 hover:shadow-xl dark:border-white/15 dark:bg-slate-900 dark:text-white"
                >
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-900 text-white dark:bg-white/10">
                        @svg('heroicon-s-building-storefront', 'h-4 w-4')
                    </span>
                    <span class="hidden sm:inline">{{ optional($branchShortcuts->firstWhere('id', $activeBranchId))->name ?? __('All branches') }}</span>
                    <span class="sm:hidden">{{ $activeBranchId > 0 ? Str::limit(optional($branchShortcuts->firstWhere('id', $activeBranchId))->name ?? 'All', 8) : 'All' }}</span>
                    @svg('heroicon-s-chevron-up', 'h-4 w-4 text-slate-400 transition-transform duration-200', ['x-bind:class' => "open ? '' : 'rotate-180'"])
                </button>
            </div>

            {{-- Branch Panel --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute bottom-14 right-0 w-72 rounded-2xl border border-slate-200 bg-white p-4 text-slate-900 shadow-2xl dark:border-white/10 dark:bg-slate-900 dark:text-white"
            >
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-white/40">Switch Branch</p>
                    <button type="button" @click="open = false" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white">
                        @svg('heroicon-o-x-mark', 'h-4 w-4')
                    </button>
                </div>
                <p class="mt-1 text-[11px] text-slate-500 dark:text-white/60">
                    Active:
                    <span class="font-semibold text-slate-900 dark:text-white">
                        {{ optional($branchShortcuts->firstWhere('id', $activeBranchId))->name ?? 'All branches' }}
                    </span>
                </p>
                <ul class="mt-3 max-h-64 space-y-1.5 overflow-y-auto pr-1">
                    <li>
                        <form method="POST" action="{{ route('branch.switch') }}">
                            @csrf
                            <input type="hidden" name="branch_id" value="all">
                            <button type="submit"
                                class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left transition {{ $activeBranchId === 0 ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-50 text-slate-900 hover:bg-slate-100 dark:bg-white/5 dark:text-white dark:hover:bg-white/10' }}">
                                <div>
                                    <p class="text-sm font-semibold">All branches</p>
                                    <p class="text-[11px] {{ $activeBranchId === 0 ? 'text-white/70 dark:text-slate-600' : 'text-slate-500 dark:text-white/60' }}">Company-wide</p>
                                </div>
                                @if ($activeBranchId === 0)
                                    @svg('heroicon-s-check-circle', 'h-5 w-5 text-emerald-400')
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
                                    class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left transition {{ $activeBranchId === $branch->id ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-50 text-slate-900 hover:bg-slate-100 dark:bg-white/5 dark:text-white dark:hover:bg-white/10' }}">
                                    <div>
                                        <p class="text-sm font-semibold">{{ $branch->name }}</p>
                                        <p class="text-[11px] {{ $activeBranchId === $branch->id ? 'text-white/70 dark:text-slate-600' : 'text-slate-500 dark:text-white/60' }}">
                                            {{ $branch->city }}{{ $branch->province ? ', '.$branch->province : '' }}
                                        </p>
                                    </div>
                                    @if ($activeBranchId === $branch->id)
                                        @svg('heroicon-s-check-circle', 'h-5 w-5 text-emerald-400')
                                    @endif
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
