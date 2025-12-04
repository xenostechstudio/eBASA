@props(['user' => null])

@php
    $branches = \App\Models\Branch::orderBy('name')->get();
    $activeBranchId = (int) session('active_branch_id', 0);
@endphp

@if ($user)
    <div class="relative" x-data="{ open: false, branchOpen: false }" @click.outside="open = false; branchOpen = false" @keydown.escape.window="open = false; branchOpen = false">
        <button type="button" @click="open = !open; branchOpen = false" class="flex items-center gap-3 px-1 py-1">
            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-200 text-slate-900 grid place-items-center font-semibold">
                {{ strtoupper(Str::substr($user->name, 0, 1)) }}
            </div>
        </button>
        <div x-show="open" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 mt-3 w-64 rounded-2xl bg-white/90 p-0 shadow-[0_20px_45px_rgba(15,23,42,0.2)] backdrop-blur dark:bg-slate-900/60 dark:shadow-[0_20px_45px_rgba(2,6,23,0.45)]">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white text-slate-900 dark:border-white/10 dark:bg-slate-900/95 dark:text-white">
                <div class="flex items-center gap-3 px-5 py-4">
                    <div class="h-11 w-11 rounded-full bg-gradient-to-br from-amber-400 to-amber-200 text-slate-900 grid place-items-center font-semibold">
                        {{ strtoupper(Str::substr($user->name, 0, 1)) }}
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/60">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="divide-y divide-slate-200 dark:divide-white/10">
                    <div class="bg-slate-50/60 py-3 dark:bg-white/5" data-theme-options>
                        <div class="grid grid-cols-3 gap-2 px-4">
                            <button type="button" data-theme-choice="light" data-active="false" class="flex items-center justify-center rounded-xl px-3 py-2" aria-label="Light mode">
                                @svg('heroicon-o-light-bulb', 'h-5 w-5')
                            </button>
                            <button type="button" data-theme-choice="dark" data-active="false" class="flex items-center justify-center rounded-xl px-3 py-2" aria-label="Dark mode">
                                @svg('heroicon-s-moon', 'h-5 w-5')
                            </button>
                            <button type="button" data-theme-choice="system" data-active="false" class="flex items-center justify-center rounded-xl px-3 py-2" aria-label="System mode">
                                @svg('heroicon-s-computer-desktop', 'h-5 w-5')
                            </button>
                        </div>
                    </div>

                    {{-- Branch Switcher Sub-dropdown --}}
                    @if ($branches->isNotEmpty())
                        <div class="relative">
                            <button type="button" @click="branchOpen = !branchOpen" class="flex w-full items-center justify-between px-5 py-3 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-white dark:hover:bg-white/10">
                                <span class="flex items-center gap-2">
                                    @svg('heroicon-o-building-storefront', 'h-4 w-4 text-slate-400 dark:text-white/50')
                                    <span>{{ $activeBranchId > 0 ? optional($branches->firstWhere('id', $activeBranchId))->name : 'All Branches' }}</span>
                                </span>
                                <svg class="h-4 w-4 text-slate-400 transition-transform dark:text-white/50" :class="branchOpen ? 'rotate-90' : ''" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L9.586 11H4a1 1 0 110-2h5.586L7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            {{-- Branch List --}}
                            <div x-show="branchOpen" x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="border-t border-slate-100 bg-slate-50/80 dark:border-white/5 dark:bg-white/5">
                                <div class="max-h-48 overflow-y-auto py-1">
                                    <form method="POST" action="{{ route('branch.switch') }}">
                                        @csrf
                                        <input type="hidden" name="branch_id" value="all">
                                        <button type="submit" class="flex w-full items-center justify-between px-5 py-2 text-sm transition {{ $activeBranchId === 0 ? 'bg-slate-200/80 font-medium text-slate-900 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-white/70 dark:hover:bg-white/10' }}">
                                            <span>All Branches</span>
                                            @if ($activeBranchId === 0)
                                                @svg('heroicon-s-check', 'h-4 w-4 text-emerald-500')
                                            @endif
                                        </button>
                                    </form>
                                    @foreach ($branches as $branch)
                                        <form method="POST" action="{{ route('branch.switch') }}">
                                            @csrf
                                            <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                            <button type="submit" class="flex w-full items-center justify-between px-5 py-2 text-sm transition {{ $activeBranchId === $branch->id ? 'bg-slate-200/80 font-medium text-slate-900 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-100 dark:text-white/70 dark:hover:bg-white/10' }}">
                                                <span>{{ $branch->name }}</span>
                                                @if ($activeBranchId === $branch->id)
                                                    @svg('heroicon-s-check', 'h-4 w-4 text-emerald-500')
                                                @endif
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-5 py-3 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-white dark:hover:bg-white/10">
                        <span>Account Settings</span>
                        <svg class="h-4 w-4 text-slate-500 dark:text-white/60" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L9.586 11H4a1 1 0 110-2h5.586L7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-between px-5 py-3 text-sm text-slate-700 transition hover:bg-rose-100 hover:text-rose-600 dark:text-white dark:hover:bg-rose-500/20 dark:hover:text-rose-100">
                            <span>Sign out</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5a1.5 1.5 0 010 3h-5A1.5 1.5 0 013 4.5zm0 11A1.5 1.5 0 014.5 14h5a1.5 1.5 0 010 3h-5A1.5 1.5 0 013 15.5zM15.25 6.5a.75.75 0 10-1.5 0v3.25H9.5a.75.75 0 000 1.5h4.25V14.5a.75.75 0 001.5 0V11.25H18a.75.75 0 000-1.5h-2.75V6.5z" clip-rule="evenodd" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
