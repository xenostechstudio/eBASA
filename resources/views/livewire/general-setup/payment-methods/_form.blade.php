@php /** @var bool $isEditing */ @endphp

<div class="space-y-5 px-6 py-5 overflow-y-auto">
    <div class="space-y-3">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Payment Method Details
        </p>
        <div class="grid gap-4 md:grid-cols-2">
            {{-- Name --}}
            <div class="md:col-span-1">
                <label for="methodName" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Display Name
                </label>
                <input
                    type="text"
                    id="methodName"
                    wire:model="methodName"
                    autocomplete="off"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="e.g. Credit Card"
                >
                @error('methodName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Code --}}
            <div class="md:col-span-1">
                <label for="methodCode" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Code
                </label>
                <input
                    type="text"
                    id="methodCode"
                    wire:model="methodCode"
                    autocomplete="off"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="e.g. credit_card"
                >
                @error('methodCode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                    A unique identifier for this payment method.
                </p>
            </div>
        </div>
    </div>

    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Appearance
        </p>
        <div>
            <label for="methodIcon" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                Icon
            </label>
            <select
                id="methodIcon"
                wire:model="methodIcon"
                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
            >
                <option value="heroicon-o-banknotes">💵 Cash</option>
                <option value="heroicon-o-credit-card">💳 Credit Card</option>
                <option value="heroicon-o-qr-code">📱 QR Code</option>
                <option value="heroicon-o-building-library">🏦 Bank</option>
                <option value="heroicon-o-device-phone-mobile">📲 Mobile</option>
                <option value="heroicon-o-wallet">👛 Wallet</option>
            </select>
            @error('methodIcon') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                Choose an icon to represent this payment method in POS.
            </p>
        </div>
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 dark:border-white/10">
    <button
        type="button"
        wire:click="closeModal"
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
        <span>{{ $isEditing ? 'Save Changes' : 'Create Method' }}</span>
    </button>
</div>
