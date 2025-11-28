@props([
    'show' => false,
    'title' => 'Delete item',
    'description' => 'This action cannot be undone.',
    'itemName' => null,
    'confirmAction' => 'delete',
    'cancelAction' => 'cancel',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
])

@if ($show)
    <div class="fixed inset-0 z-40 flex items-center justify-center overflow-y-auto p-4" aria-labelledby="confirm-delete-title" role="dialog" aria-modal="true">
        <div wire:click="{{ $cancelAction }}" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

        <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-slate-300 bg-white text-slate-900 shadow-xl dark:border-white/10 dark:bg-slate-900 dark:text-white">
            <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <h3 id="confirm-delete-title" class="text-base font-semibold text-slate-900 dark:text-white">
                    {{ $title }}
                </h3>
                @if ($description)
                    <p class="mt-1 text-xs text-slate-500 dark:text-white/60">
                        {{ $description }}
                    </p>
                @endif
            </div>

            @if ($itemName)
                <div class="px-6 py-5 text-sm text-slate-600 dark:text-white/70">
                    <p>
                        Are you sure you want to delete
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $itemName }}</span>
                        from the system?
                    </p>
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 dark:border-white/10">
                <x-button.secondary
                    type="button"
                    wire:click="{{ $cancelAction }}"
                >
                    {{ $cancelText }}
                </x-button.secondary>

                <x-button.danger
                    type="button"
                    wire:click="{{ $confirmAction }}"
                    wire:loading.attr="disabled"
                    wire:target="{{ $confirmAction }}"
                >
                    {{ $confirmText }}
                </x-button.danger>
            </div>
        </div>
    </div>
@endif
