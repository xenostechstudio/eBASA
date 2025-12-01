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
            title="Product Information"
            description="Define SKU, pricing and inventory rules so this product is ready to sell."
        />

        {{-- Form Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        @include('livewire.general-setup.products._form', [
            'isEditing' => false,
            'editingProduct' => $editingProduct,
        ])
    </div>
</div>
