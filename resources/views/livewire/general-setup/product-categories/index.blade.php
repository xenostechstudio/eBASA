<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card
            label="Total Categories"
            :value="number_format($stats['total'])"
            description="Product categories"
            tone="neutral"
        >
            <x-slot:icon>
                @svg('heroicon-o-tag', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card
            label="With Products"
            :value="number_format($stats['withProducts'])"
            description="Categories in use"
            tone="success"
        >
            <x-slot:icon>
                @svg('heroicon-o-cube', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card
            label="Empty"
            :value="number_format($stats['empty'])"
            description="No products assigned"
            tone="warning"
        >
            <x-slot:icon>
                @svg('heroicon-o-archive-box', 'h-5 w-5 text-slate-400')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Categories Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Product Categories</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">Organize your product catalog</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search categories..."
                            class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>

                    {{-- Status Filter --}}
                    <x-table.dynamic-filters :filters="[
                        'status' => [
                            'label' => 'Status',
                            'options' => [
                                '' => 'All status',
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ],
                            'selected' => $statusFilter,
                            'default' => '',
                            'onSelect' => 'setStatusFilter',
                        ],
                    ]" />

                    <x-table.export-dropdown aria-label="Export categories" />

                    <a
                        href="{{ route('general-setup.product-categories.create') }}"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                    >
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Category</span>
                    </a>
                </div>
            </div>
        </div>

        @php
            $statusLabel = null;
            $statusClasses = '';

            if ($statusFilter === 'active') {
                $statusLabel = 'Status: Active';
                $statusClasses = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300';
            } elseif ($statusFilter === 'inactive') {
                $statusLabel = 'Status: Inactive';
                $statusClasses = 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/80';
            }

            $categoriesCount = $categories->total();
        @endphp

        @if ($statusLabel)
            <div
                class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 text-xs text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-white/70">
                <div class="flex flex-wrap items-center gap-2">
                    <div
                        class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-medium {{ $statusClasses }}">
                        <span>{{ $statusLabel }} ({{ $categoriesCount }})</span>
                        <button
                            type="button"
                            wire:click="setStatusFilter('')"
                            class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20"
                            aria-label="Reset status filter"
                        >
                            @svg('heroicon-o-x-mark', 'h-3 w-3')
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if ($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">NAME</th>
                            <th class="px-5 py-3">DESCRIPTION</th>
                            <th class="px-5 py-3 text-center">PRODUCTS</th>
                            <th class="px-5 py-3 text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($categories as $category)
                            <tr
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                onclick="window.location='{{ route('general-setup.product-categories.edit', $category) }}'"
                            >
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400">
                                            @svg('heroicon-o-tag', 'h-4 w-4')
                                        </div>
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ Str::limit($category->description ?? '-', 50) }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-center">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/80">
                                        {{ $category->products_count }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-center">
                                    @if ($category->is_active)
                                        <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-table.pagination :paginator="$categories" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-tag', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No categories found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Create your first category to organize products</p>
            </div>
        @endif
    </div>

    {{-- Create/Edit Category Modal --}}
    @if ($showCreateModal || $showEditModal)
        @php $isEditing = ! is_null($editingCategoryId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Background overlay --}}
            <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

            {{-- Modal panel --}}
            <div class="relative z-10 my-6 w-full max-w-lg max-h-[calc(100vh-6rem)] flex flex-col overflow-hidden rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit Category' : 'Add New Category' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        {{ $isEditing ? 'Update category information' : 'Create a new product category' }}
                    </p>
                </div>

                @include('livewire.general-setup.product-categories._form', [
                    'isEditing' => $isEditing,
                    'editingCategory' => $editingCategory ?? null,
                ])
            </div>
        </div>
    @endif
</div>
