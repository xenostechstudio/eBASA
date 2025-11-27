<div class="space-y-6">
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-users', 'h-5 w-5 text-sky-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Total Users</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Registered accounts</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-6 dark:border-emerald-500/20 dark:bg-emerald-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-check-badge', 'h-5 w-5 text-emerald-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Active</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['active']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Verified accounts</p>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-6 dark:border-amber-500/20 dark:bg-amber-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Pending</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['pending']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Awaiting verification</p>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">All Users</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">Manage user accounts and access</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search users..."
                            class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>
                    <button
                        type="button"
                        wire:click="openCreateModal"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                    >
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add User</span>
                    </button>
                </div>
            </div>
        </div>

        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('name')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    NAME
                                    @if ($sortField === 'name')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('email')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    EMAIL
                                    @if ($sortField === 'email')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">STATUS</th>
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('created_at')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    JOINED
                                    @if ($sortField === 'created_at')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($users as $user)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-sm font-medium text-slate-600 dark:bg-white/10 dark:text-white/80">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $user->email }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($user->email_verified_at)
                                        <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <button
                                            type="button"
                                            wire:click="openEditModal({{ $user->id }})"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                            title="Edit"
                                        >
                                            @svg('heroicon-o-pencil', 'h-4 w-4')
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="deleteUser({{ $user->id }})"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                            title="Delete"
                                        >
                                            @svg('heroicon-o-trash', 'h-4 w-4')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 dark:border-white/10">
                {{ $users->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-users', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No users found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Try adjusting your search</p>
            </div>
        @endif
    </div>

    {{-- Create/Edit User Modal --}}
    @if ($showCreateModal || $showEditModal)
        @php $isEditing = ! is_null($editingUserId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Background overlay --}}
            <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

            {{-- Modal panel --}}
            <div class="relative z-10 w-full max-w-2xl max-h-[calc(100vh-6rem)] flex flex-col overflow-hidden rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit User' : 'Add New User' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        {{ $isEditing ? 'Update user information' : 'Create a new user account' }}
                    </p>
                </div>

                @include('livewire.general-setup.users._form', ['isEditing' => $isEditing, 'employees' => $employees])
            </div>
        </div>
    @endif
</div>
