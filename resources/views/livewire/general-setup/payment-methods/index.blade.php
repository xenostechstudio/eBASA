<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Payment Methods --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Payment Methods</h2>
                <p class="text-xs text-slate-500 dark:text-white/60">Configure accepted payment methods for POS</p>
            </div>
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
            >
                @svg('heroicon-o-plus', 'h-4 w-4')
                <span>Add Method</span>
            </button>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($paymentMethods as $index => $method)
                <div wire:key="payment-method-{{ $index }}-{{ $method['active'] ? 'on' : 'off' }}" class="rounded-xl border {{ $method['active'] ? 'border-slate-200 dark:border-white/10' : 'border-slate-100 bg-slate-50 dark:border-white/5 dark:bg-white/5' }} p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $method['active'] ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-400 dark:bg-white/10 dark:text-white/40' }}">
                                @svg($method['icon'], 'h-5 w-5')
                            </div>
                            <div>
                                <h3 class="font-medium {{ $method['active'] ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-white/50' }}">{{ $method['name'] }}</h3>
                                <p class="text-xs {{ $method['active'] ? 'text-slate-500 dark:text-white/60' : 'text-slate-400 dark:text-white/40' }}">{{ strtoupper($method['code']) }}</p>
                            </div>
                        </div>
                        <button
                            wire:click.prevent="togglePaymentMethod({{ $index }})"
                            type="button"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 {{ $method['active'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-white/30' }}"
                            role="switch"
                            aria-checked="{{ $method['active'] ? 'true' : 'false' }}"
                        >
                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out {{ $method['active'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Payment Settings --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Payment Settings</h2>
        <p class="text-xs text-slate-500 dark:text-white/60">Configure payment processing options</p>

        <div class="mt-6 space-y-4">
            <div wire:key="setting-allowMixedPayments-{{ $settings['allowMixedPayments'] ? 'on' : 'off' }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-4 dark:border-white/10">
                <div>
                    <h3 class="font-medium text-slate-900 dark:text-white">Allow Mixed Payments</h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">Enable customers to pay with multiple methods</p>
                </div>
                <button
                    wire:click.prevent="toggleSetting('allowMixedPayments')"
                    type="button"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 {{ $settings['allowMixedPayments'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-white/30' }}"
                    role="switch"
                    aria-checked="{{ $settings['allowMixedPayments'] ? 'true' : 'false' }}"
                >
                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out {{ $settings['allowMixedPayments'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </div>

            <div wire:key="setting-requireConfirmation-{{ $settings['requireConfirmation'] ? 'on' : 'off' }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-4 dark:border-white/10">
                <div>
                    <h3 class="font-medium text-slate-900 dark:text-white">Require Payment Confirmation</h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">Confirm payment before completing transaction</p>
                </div>
                <button
                    wire:click.prevent="toggleSetting('requireConfirmation')"
                    type="button"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 {{ $settings['requireConfirmation'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-white/30' }}"
                    role="switch"
                    aria-checked="{{ $settings['requireConfirmation'] ? 'true' : 'false' }}"
                >
                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out {{ $settings['requireConfirmation'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </div>

            <div wire:key="setting-autoSelectCash-{{ $settings['autoSelectCash'] ? 'on' : 'off' }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-4 dark:border-white/10">
                <div>
                    <h3 class="font-medium text-slate-900 dark:text-white">Auto-select Cash</h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">Default to cash payment method</p>
                </div>
                <button
                    wire:click.prevent="toggleSetting('autoSelectCash')"
                    type="button"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 {{ $settings['autoSelectCash'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-white/30' }}"
                    role="switch"
                    aria-checked="{{ $settings['autoSelectCash'] ? 'true' : 'false' }}"
                >
                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out {{ $settings['autoSelectCash'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Create/Edit Payment Method Modal --}}
    @if ($showCreateModal || $showEditModal)
        @php $isEditing = $editingIndex !== null; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Background overlay --}}
            <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

            {{-- Modal panel --}}
            <div class="relative z-10 my-6 w-full max-w-lg max-h-[calc(100vh-6rem)] flex flex-col overflow-hidden rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit Payment Method' : 'Add New Payment Method' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        {{ $isEditing ? 'Update payment method information' : 'Create a new payment method for POS' }}
                    </p>
                </div>

                @include('livewire.general-setup.payment-methods._form', ['isEditing' => $isEditing])
            </div>
        </div>
    @endif
</div>
