<div>
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    @if (session()->has('password_status'))
        <x-alert type="success">
            {{ session('password_status') }}
        </x-alert>
    @endif

    <div class="space-y-6" x-data="{ activeTab: 'profile' }">
        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Account</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">My Profile</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/60">
                    Manage your account settings and preferences
                </p>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-6 shadow-sm dark:border-white/10 dark:from-white/5 dark:to-white/10">
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                {{-- Avatar --}}
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-2xl font-bold text-white shadow-lg dark:from-white/20 dark:to-white/10">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-slate-500 dark:text-white/60">{{ auth()->user()->email }}</p>
                    <div class="mt-3 flex flex-wrap justify-center gap-2 sm:justify-start">
                        @if(auth()->user()->email_verified_at)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                @svg('heroicon-o-check-badge', 'h-3.5 w-3.5')
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                @svg('heroicon-o-exclamation-triangle', 'h-3.5 w-3.5')
                                Unverified
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                            @svg('heroicon-o-calendar', 'h-3.5 w-3.5')
                            Joined {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-slate-200 dark:border-white/10">
            <nav class="-mb-px flex gap-6">
                <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Profile Information
                </button>
                <button @click="activeTab = 'password'" :class="activeTab === 'password' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Password
                </button>
                <button @click="activeTab = 'danger'" :class="activeTab === 'danger' ? 'border-rose-500 text-rose-600 dark:border-rose-400 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                    class="border-b-2 pb-3 text-sm font-medium transition">
                    Danger Zone
                </button>
            </nav>
        </div>

        {{-- Tab: Profile Information --}}
        <div x-show="activeTab === 'profile'" x-cloak>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white">
                        @svg('heroicon-o-user', 'h-5 w-5')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Profile Information</h3>
                        <p class="text-sm text-slate-500 dark:text-white/50">Update your account's profile information and email address.</p>
                    </div>
                </div>

                <form wire:submit="updateProfile" class="mt-6 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Full Name</label>
                            <input type="text" wire:model="name"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Email Address</label>
                            <input type="email" wire:model="email"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror

                            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                                <div class="mt-2 rounded-lg bg-amber-50 p-3 dark:bg-amber-500/10">
                                    <p class="text-sm text-amber-700 dark:text-amber-400">
                                        Your email address is unverified.
                                        <button type="button" wire:click.prevent="$dispatch('verification-link-sent')" class="font-medium underline hover:no-underline">
                                            Click here to re-send the verification email.
                                        </button>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                            @svg('heroicon-o-check', 'h-4 w-4')
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab: Password --}}
        <div x-show="activeTab === 'password'" x-cloak>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-300/20 dark:text-sky-300">
                        @svg('heroicon-o-key', 'h-5 w-5')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Update Password</h3>
                        <p class="text-sm text-slate-500 dark:text-white/50">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                </div>

                <form wire:submit="updatePassword" class="mt-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Current Password</label>
                        <input type="password" wire:model="current_password" autocomplete="current-password"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('current_password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">New Password</label>
                            <input type="password" wire:model="password" autocomplete="new-password"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            @error('password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" autocomplete="new-password"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                            @svg('heroicon-o-key', 'h-4 w-4')
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab: Danger Zone --}}
        <div x-show="activeTab === 'danger'" x-cloak>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400">
                        @svg('heroicon-o-exclamation-triangle', 'h-5 w-5')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-rose-900 dark:text-rose-300">Delete Account</h3>
                        <p class="text-sm text-rose-700 dark:text-rose-400/80">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                    </div>
                </div>

                <div class="mt-6">
                    <button
                        type="button"
                        wire:click="confirmDeleteAccount"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-rose-600 px-4 text-sm font-medium text-white transition hover:bg-rose-700 dark:bg-rose-500 dark:hover:bg-rose-600"
                    >
                        @svg('heroicon-o-trash', 'h-4 w-4')
                        <span>Delete Account</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" x-data @keydown.escape.window="$wire.set('showDeleteModal', false)">
            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-white/10 dark:bg-slate-900">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400">
                        @svg('heroicon-o-exclamation-triangle', 'h-6 w-6')
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Are you sure?</h3>
                        <p class="text-sm text-slate-500 dark:text-white/60">This action cannot be undone.</p>
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-600 dark:text-white/70">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <form wire:submit="deleteAccount" class="mt-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Password</label>
                        <input type="password" wire:model="delete_password" placeholder="Enter your password to confirm"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('delete_password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            wire:click="$set('showDeleteModal', false)"
                            class="inline-flex h-10 items-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-rose-600 px-4 text-sm font-medium text-white transition hover:bg-rose-700"
                        >
                            @svg('heroicon-o-trash', 'h-4 w-4')
                            <span>Delete Account</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
