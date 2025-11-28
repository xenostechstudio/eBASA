<?php

namespace App\Livewire\GeneralSetup\Warehouses;

use App\Models\Branch;
use App\Models\Warehouse;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $branchFilter = '';

    public int $perPage = 15;

    public array $perPageOptions = [15, 30, 50];

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingWarehouseId = null;

    public string $code = '';

    public string $name = '';

    public ?int $branch_id = null;

    public string $city = '';

    public string $province = '';

    public string $address = '';

    public string $phone = '';

    public string $contact_name = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        $warehouseId = $this->editingWarehouseId;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('warehouses', 'code')->ignore($warehouseId),
            ],
            'name' => [
                'required',
                'string',
                'max:120',
            ],
            'branch_id' => [
                'nullable',
                'integer',
                'exists:branches,id',
            ],
            'city' => [
                'nullable',
                'string',
                'max:120',
            ],
            'province' => [
                'nullable',
                'string',
                'max:120',
            ],
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],
            'contact_name' => [
                'nullable',
                'string',
                'max:120',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    public function updatedPerPage($value): void
    {
        $value = (int) $value;
        $this->perPage = in_array($value, $this->perPageOptions, true) ? $value : $this->perPageOptions[0];
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $value): void
    {
        $this->statusFilter = $value;
        $this->resetPage();
    }

    public function setBranchFilter(string $value): void
    {
        $this->branchFilter = $value;
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $warehouseId): void
    {
        $warehouse = Warehouse::find($warehouseId);

        if (! $warehouse) {
            return;
        }

        $this->editingWarehouseId = $warehouseId;
        $this->code = (string) ($warehouse->code ?? '');
        $this->name = (string) ($warehouse->name ?? '');
        $this->branch_id = $warehouse->branch_id;
        $this->city = (string) ($warehouse->city ?? '');
        $this->province = (string) ($warehouse->province ?? '');
        $this->address = (string) ($warehouse->address ?? '');
        $this->phone = (string) ($warehouse->phone ?? '');
        $this->contact_name = (string) ($warehouse->contact_name ?? '');
        $this->is_active = (bool) ($warehouse->is_active ?? true);

        $this->showEditModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingWarehouseId = null;
        $this->code = '';
        $this->name = '';
        $this->branch_id = null;
        $this->city = '';
        $this->province = '';
        $this->address = '';
        $this->phone = '';
        $this->contact_name = '';
        $this->is_active = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingWarehouseId) {
            $warehouse = Warehouse::find($this->editingWarehouseId);

            if (! $warehouse) {
                return;
            }

            $warehouse->update($data);

            $flashTitle = 'Warehouse updated';
            $flashMessage = 'Warehouse updated successfully.';
        } else {
            Warehouse::create($data);

            $flashTitle = 'Warehouse created';
            $flashMessage = 'Warehouse created successfully.';
        }

        $this->closeModal();
        $this->resetPage();

        session()->flash('flash', [
            'type' => 'success',
            'title' => $flashTitle,
            'message' => $flashMessage,
        ]);
    }

    public function export(string $format): void
    {
        if (! in_array($format, ['excel', 'pdf'], true)) {
            return;
        }

        $label = strtoupper($format);

        session()->flash('flash', [
            'type' => 'info',
            'title' => $label . ' export',
            'message' => 'Export functionality is not implemented yet.',
        ]);
    }

    protected function query()
    {
        return Warehouse::query()
            ->with('branch')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->branchFilter !== '', function ($query) {
                $query->where('branch_id', $this->branchFilter);
            })
            ->orderBy('name');
    }

    public function render(): View
    {
        $warehouses = $this->query()->paginate($this->perPage);

        $statsBase = Warehouse::query();
        $stats = [
            'total' => (clone $statsBase)->count(),
            'active' => (clone $statsBase)->where('is_active', true)->count(),
            'inactive' => (clone $statsBase)->where('is_active', false)->count(),
        ];

        $branches = Branch::orderBy('name')->get(['id', 'name', 'code']);

        $editingWarehouse = null;

        if ($this->editingWarehouseId) {
            $editingWarehouse = Warehouse::with('branch')->find($this->editingWarehouseId);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.general-setup.warehouses.index', [
            'warehouses' => $warehouses,
            'stats' => $stats,
            'branches' => $branches,
            'editingWarehouse' => $editingWarehouse,
        ])->layoutData([
            'pageTitle' => 'Warehouses',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('warehouses'),
        ]);
    }
}
