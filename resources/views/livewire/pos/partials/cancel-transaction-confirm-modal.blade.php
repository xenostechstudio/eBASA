<x-modal name="pos-cancel-transaction" maxWidth="sm">
    <div class="flex flex-col self-center rounded-3xl bg-white/95 p-6 text-slate-900 shadow-2xl shadow-black/20 dark:bg-slate-950/95 dark:text-white dark:shadow-black/50">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-200">
                {{ svg('heroicon-o-exclamation-triangle', 'w-6 h-6') }}
            </div>
            <div class="flex-1">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-500 dark:text-rose-300">Peringatan</p>
                <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Batalkan transaksi ini?</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-white/70">
                    Semua item di keranjang akan dihapus dan transaksi ini tidak akan disimpan.
                    Tindakan ini hanya mengosongkan tampilan keranjang saat ini.
                </p>
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button type="button" x-data
                class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/10 sm:flex-none sm:px-5"
                @click="$dispatch('close-modal', 'pos-cancel-transaction')">
                Kembali
            </button>
            <button type="button" x-data
                class="inline-flex flex-1 items-center justify-center rounded-2xl border border-rose-500 bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-600 hover:border-rose-600 dark:border-rose-400 dark:bg-rose-500 dark:hover:bg-rose-400 sm:flex-none sm:px-5"
                @click="$dispatch('pos-clear-cart'); $dispatch('pos-reset-summary'); $dispatch('close-modal', 'pos-cancel-transaction')">
                Ya, batalkan transaksi
            </button>
        </div>
    </div>
</x-modal>
