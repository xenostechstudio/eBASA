<?php

namespace App\Livewire\Inventory\Branches;

use App\Models\Branch;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public int $perPage = 10;
    public array $perPageOptions = [10, 25, 50];

    public bool $showCreateModal = false;
    public bool $showEditModal = false;

    public ?int $editingBranchId = null;

    public string $code = '';
    public string $name = '';
    public string $city = '';
    public string $province = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public string $manager_name = '';
    public bool $is_active = true;

    protected array $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updated($property): void
    {
        if (in_array($property, ['search', 'statusFilter', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function setStatusFilter(string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
    }

    public function updatedPerPage($value): void
    {
        $value = (int) $value;
        $this->perPage = in_array($value, $this->perPageOptions, true) ? $value : 10;
        $this->resetPage();
    }

    protected function rules(): array
    {
        $branchId = $this->editingBranchId;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('branches', 'code')->ignore($branchId),
            ],
            'name' => [
                'required',
                'string',
                'max:120',
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
            'email' => [
                'nullable',
                'email',
                'max:120',
            ],
            'manager_name' => [
                'nullable',
                'string',
                'max:120',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $branchId): void
    {
        $branch = Branch::find($branchId);

        if (! $branch) {
            return;
        }

        $this->editingBranchId = $branchId;
        $this->code = (string) ($branch->code ?? '');
        $this->name = (string) ($branch->name ?? '');
        $this->city = (string) ($branch->city ?? '');
        $this->province = (string) ($branch->province ?? '');
        $this->address = (string) ($branch->address ?? '');
        $this->phone = (string) ($branch->phone ?? '');
        $this->email = (string) ($branch->email ?? '');
        $this->manager_name = (string) ($branch->manager_name ?? '');
        $this->is_active = (bool) ($branch->is_active ?? true);

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
        $this->editingBranchId = null;
        $this->code = '';
        $this->name = '';
        $this->city = '';
        $this->province = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->manager_name = '';
        $this->is_active = true;

        $this->resetValidation();
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingBranchId) {
            $branch = Branch::find($this->editingBranchId);

            if (! $branch) {
                return;
            }

            $branch->update($data);

            $flashTitle = 'Branch updated';
            $flashMessage = 'Branch updated successfully.';
        } else {
            Branch::create($data);

            $flashTitle = 'Branch created';
            $flashMessage = 'Branch created successfully.';
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
            'title' => $label.' export',
            'message' => 'Export functionality is not implemented yet.',
        ]);
    }

    protected function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Branch::query();

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%')
                    ->orWhere('city', 'like', '%'.$this->search.'%')
                    ->orWhere('province', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        return $query->orderBy('name');
    }

    public function render(): View
    {
        $branches = $this->query()->paginate($this->perPage);
        $statsBase = Branch::query();

        $stats = [
            'total' => (clone $statsBase)->count(),
            'active' => (clone $statsBase)->where('is_active', true)->count(),
            'inactive' => (clone $statsBase)->where('is_active', false)->count(),
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.general-setup.branches.index', [
            'branches' => $branches,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Branches',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('branches'),
        ]);
    }
}
