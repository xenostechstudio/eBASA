@props([
    'showCreateModal' => false,
    'showEditModal' => false,
])

@if ($showCreateModal || $showEditModal)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Background overlay --}}
        <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

        {{-- Modal panel --}}
        <div class="relative z-10 w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-xl transition-all dark:bg-slate-900">
            <form wire:submit="{{ $showCreateModal ? 'createUser' : 'updateUser' }}">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $showCreateModal ? 'Add New User' : 'Edit User' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        {{ $showCreateModal ? 'Create a new user account' : 'Update user information' }}
                    </p>
                </div>

                <div class="space-y-4 px-6 py-4">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-white/80">Name</label>
                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                            placeholder="Enter full name"
                        >
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-white/80">Email</label>
                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                            placeholder="Enter email address"
                        >
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                            Password
                            @if ($showEditModal)
                                <span class="font-normal text-slate-400 dark:text-white/40">(leave blank to keep current)</span>
                            @endif
                        </label>
                        <input
                            type="password"
                            id="password"
                            wire:model="password"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                            placeholder="Enter password"
                        >
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-white/80">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            wire:model="password_confirmation"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                            placeholder="Confirm password"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    <button type="button" wire:click="closeModal" class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                        @svg('heroicon-o-check', 'h-4 w-4')
                        <span>{{ $showCreateModal ? 'Create User' : 'Save Changes' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
