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
        <x-stat.card label="Total Users" :value="number_format($stats['total'])" description="Registered accounts" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-users', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Active" :value="number_format($stats['active'])" description="Verified accounts" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-badge', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Pending" :value="number_format($stats['pending'])" description="Awaiting verification" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Users Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">All Users</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        Manage user accounts and access
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                    </div>

                    {{-- Status filter --}}
                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:bg-white/10"
                            aria-label="Filter status">
                            @svg('heroicon-o-funnel', 'h-4 w-4')
                        </button>

                        <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1" @click.away="open = false"
                            class="absolute right-0 z-20 mt-2 min-w-[14rem] rounded-xl border border-slate-300 bg-white py-1 px-1 text-sm shadow-lg dark:border-white/10 dark:bg-slate-900">
                            <button type="button" wire:click="$set('statusFilter', '')"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm {{ $statusFilter === '' ? 'bg-slate-100 font-medium text-slate-900 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5' }}">
                                <span>All active</span>
                                @if ($statusFilter === '')
                                    @svg('heroicon-o-check', 'h-4 w-4 text-emerald-500')
                                @endif
                            </button>

                            <button type="button" wire:click="$set('statusFilter', 'verified')"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm {{ $statusFilter === 'verified' ? 'bg-slate-100 font-medium text-slate-900 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5' }}">
                                <span>Verified</span>
                                @if ($statusFilter === 'verified')
                                    @svg('heroicon-o-check', 'h-4 w-4 text-emerald-500')
                                @endif
                            </button>

                            <button type="button" wire:click="$set('statusFilter', 'pending')"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm {{ $statusFilter === 'pending' ? 'bg-slate-100 font-medium text-slate-900 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5' }}">
                                <span>Pending</span>
                                @if ($statusFilter === 'pending')
                                    @svg('heroicon-o-check', 'h-4 w-4 text-emerald-500')
                                @endif
                            </button>

                            <button type="button" wire:click="$set('statusFilter', 'trashed')"
                                class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm {{ $statusFilter === 'trashed' ? 'bg-slate-100 font-medium text-slate-900 dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5' }}">
                                <span>Trashed</span>
                                @if ($statusFilter === 'trashed')
                                    @svg('heroicon-o-check', 'h-4 w-4 text-emerald-500')
                                @endif
                            </button>
                        </div>
                    </div>

                    {{-- Export dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-800 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:bg-white/10"
                            aria-label="Export data">
                            @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                        </button>

                        <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1" @click.away="open = false"
                            class="absolute right-0 z-20 mt-2 min-w-[14rem] rounded-xl border border-slate-300 bg-white py-1 px-1 text-sm shadow-lg dark:border-white/10 dark:bg-slate-900">
                            <button type="button" wire:click="export('excel')"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5">
                                <span>Export to Excel</span>
                            </button>
                            <button type="button" wire:click="export('pdf')"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-slate-600 hover:bg-slate-50 dark:text-white/70 dark:hover:bg-white/5">
                                <span>Export to PDF</span>
                            </button>
                        </div>
                    </div>

                    {{-- Add User --}}
                    <x-button.primary type="button" wire:click="openCreateModal" class="h-10 rounded-xl">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add User</span>
                    </x-button.primary>
                </div>
            </div>
        </div>

        @if ($statusFilter !== '')
            @php
                $label = null;
                $classes = '';
                $count = $users->total();

                if ($statusFilter === 'verified') {
                    $label = 'Verified users';
                    $classes = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300';
                } elseif ($statusFilter === 'pending') {
                    $label = 'Pending users';
                    $classes = 'bg-amber-50 text-amber-800 dark:bg-amber-500/10 dark:text-amber-300';
                } elseif ($statusFilter === 'trashed') {
                    $label = 'Deleted users';
                    $classes = 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300';
                }
            @endphp

            @if ($label)
                <div
                    class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 text-xs text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-white/70">
                    <div
                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-medium {{ $classes }}">
                        <span>Status: {{ $label }} ({{ $count }})</span>

                        <button type="button" wire:click="$set('statusFilter', '')"
                            class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20"
                            aria-label="Reset status filter">
                            @svg('heroicon-o-x-mark', 'h-3 w-3')
                        </button>
                    </div>
                </div>
            @endif
        @endif

        @if ($users->count() > 0)

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b border-slate-100 bg-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('name')"
                                    class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    NAME
                                    @if ($sortField === 'name')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('email')"
                                    class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    EMAIL
                                    @if ($sortField === 'email')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">STATUS</th>
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('created_at')"
                                    class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    REGISTERED
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
                            <tr class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                wire:click="openEditModal({{ $user->id }})">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-sm font-medium text-slate-600 dark:bg-white/10 dark:text-white/80">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $user->email }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($user->email_verified_at)
                                        <span
                                            class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            Verified
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-lg bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $user->created_at->format(config('basa.date_format')) }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($statusFilter === 'trashed')
                                        <div class="flex items-center justify-end gap-1">
                                            <button type="button" wire:click.stop="restore({{ $user->id }})"
                                                class="rounded-lg p-2 text-emerald-600 transition hover:bg-emerald-50 hover:text-emerald-700 dark:text-emerald-400 dark:hover:bg-emerald-500/10"
                                                title="Restore">
                                                @svg('heroicon-o-arrow-uturn-left', 'h-4 w-4')
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-end gap-1">
                                            <button type="button"
                                                wire:click.stop="openEditModal({{ $user->id }})"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                                title="Edit">
                                                @svg('heroicon-o-pencil', 'h-4 w-4')
                                            </button>
                                            <button type="button"
                                                wire:click.stop="confirmDelete({{ $user->id }})"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                                title="Delete">
                                                @svg('heroicon-o-trash', 'h-4 w-4')
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-table.pagination :paginator="$users" :per-page-options="$perPageOptions" />
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
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Background overlay --}}
            <div wire:click="closeModal"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

            {{-- Modal panel --}}
            <div
                class="relative z-10 w-full max-w-2xl max-h-[calc(100vh-6rem)] flex flex-col overflow-hidden rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit User' : 'Add New User' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        {{ $isEditing ? 'Update user information' : 'Create a new user account' }}
                    </p>
                </div>

                @include('livewire.general-setup.users._form', [
                    'isEditing' => $isEditing,
                    'employees' => $employees,
                    'editingUser' => $editingUser ?? null,
                ])
            </div>
        </div>
    @endif

    <x-modal.confirm-delete :show="$showDeleteConfirm" title="Delete user"
        description="This action cannot be undone. This will permanently delete the user account." :item-name="$deletingUserName"
        confirm-action="deleteUser" cancel-action="cancelDelete" confirm-text="Delete user" />
</div>
