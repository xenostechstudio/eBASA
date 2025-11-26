<div x-show="showValidation" x-transition.opacity x-cloak class="absolute inset-0 z-20 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 text-slate-900 shadow-lg dark:border-white/10 dark:bg-slate-900 dark:text-white">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-200" :class="validationMessage.includes('belum') ? 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-200' : ''">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path x-show="validationMessage.includes('belum')" d="M12 9v4" />
                    <path x-show="validationMessage.includes('belum')" d="M12 17h.01" />
                    <path x-show="!validationMessage.includes('belum')" d="m5 13 4 4L19 7" />
                    <circle cx="12" cy="12" r="9" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-white/60">Validasi Pembayaran</p>
                <p class="mt-2 text-base font-medium" x-text="validationMessage"></p>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between gap-3">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400 dark:text-white/50">Tekan Enter untuk menutup</p>
            <div class="relative">
                <button type="button" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-white/20 dark:text-white/70 dark:hover:bg-white/5" @click="closeValidation()">
                    Tutup
                </button>
                <span class="pointer-events-none absolute -top-3 left-1/2 -translate-x-1/2 rounded-full border border-slate-200 bg-white px-2 py-0.5 text-[9px] font-semibold uppercase tracking-[0.25em] text-slate-600 shadow dark:border-white/30 dark:bg-slate-900 dark:text-white">Enter</span>
            </div>
        </div>
    </div>
</div>
