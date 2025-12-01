<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            :title="'Permissions for '.$role->name"
            description="Assign or revoke system permissions for this role."
        />

        {{-- Form Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="space-y-5 px-6 py-5">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                    Permission Groups
                </p>

                <div class="space-y-4">
                    @foreach ($permissionGroups as $group)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 dark:border-white/10 dark:bg-white/5">
                            <div class="mb-3 flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $group['name'] }}
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-white/60">
                                        Toggle which {{ strtolower($group['name']) }} permissions this role can access.
                                    </p>
                                </div>
                            </div>

                            <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-3">
                                @foreach ($group['permissions'] as $permission)
                                    <label class="flex items-start gap-2 rounded-lg border border-transparent px-2 py-1.5 text-sm text-slate-700 hover:border-slate-200 hover:bg-white dark:text-white/80 dark:hover:border-white/10 dark:hover:bg-white/10">
                                        <input
                                            type="checkbox"
                                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                                            value="{{ $permission->id }}"
                                            wire:model="selectedPermissions"
                                        >
                                        <div>
                                            <div class="font-medium text-slate-800 dark:text-white">
                                                {{ $permission->name }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 dark:text-white/40">
                                                {{ $permission->slug }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 dark:border-white/10">
                <button
                    type="button"
                    wire:click="cancel"
                    class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                >
                    <span wire:loading.remove wire:target="save">
                        @svg('heroicon-o-check', 'h-4 w-4')
                    </span>
                    <span wire:loading wire:target="save">
                        @svg('heroicon-o-arrow-path', 'h-4 w-4 animate-spin')
                    </span>
                    <span>Save Permissions</span>
                </button>
            </div>
        </div>
    </div>
</div>
