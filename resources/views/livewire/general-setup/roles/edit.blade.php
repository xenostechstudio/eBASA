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
            title="Edit Role"
            description="Update role information and settings."
        />

        {{-- Form Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="space-y-5 px-6 py-5">
                {{-- Basic Details --}}
                <div class="space-y-3">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Role Details
                    </p>

                    <div class="grid gap-4 md:grid-cols-2">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                autocomplete="off"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                placeholder="e.g. Supervisor, Auditor"
                            >
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Identifier (slug) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="slug"
                                wire:model="slug"
                                autocomplete="off"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                placeholder="e.g. supervisor, auditor"
                            >
                            <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                                Used internally for permission mapping.
                            </p>
                            @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                            Description
                            <span class="font-normal text-slate-400 dark:text-white/40">(optional)</span>
                        </label>
                        <textarea
                            id="description"
                            wire:model="description"
                            rows="2"
                            class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                            placeholder="What is this role responsible for?"
                        ></textarea>
                        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Visual & System Flags --}}
                <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                        Role Settings
                    </p>

                    <div class="grid gap-4 md:grid-cols-2">
                        {{-- Color --}}
                        <div>
                            <label for="color" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Badge Color
                            </label>
                            <select
                                id="color"
                                wire:model="color"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                            >
                                <option value="red">Red (high privilege)</option>
                                <option value="amber">Amber (management)</option>
                                <option value="emerald">Emerald (operations)</option>
                                <option value="sky">Sky (inventory)</option>
                                <option value="slate">Slate (default)</option>
                            </select>
                            <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                                Controls the badge color shown in user lists.
                            </p>
                            @error('color') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- System role flag --}}
                        <div class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                id="is_system"
                                wire:model="is_system"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                            >
                            <div>
                                <label for="is_system" class="text-sm font-medium text-slate-700 dark:text-white/80">
                                    System role
                                </label>
                                <p class="text-[11px] text-slate-400 dark:text-white/40">
                                    Lock this role from deletion and use it for core system access.
                                </p>
                            </div>
                        </div>
                    </div>
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
                    <span>Update Role</span>
                </button>
            </div>
        </div>
    </div>
</div>
