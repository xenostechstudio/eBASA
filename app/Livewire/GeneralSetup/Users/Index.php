<?php

namespace App\Livewire\GeneralSetup\Users;

use App\Models\Employee;
use App\Models\User;
use App\Support\GeneralSetupNavigation;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    public string $sortField = 'name';

    public string $sortDirection = 'asc';

    // Modal state
    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingUserId = null;

    // Form fields
    public ?int $employee_id = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $userId): void
    {
        $user = User::find($userId);
        if ($user) {
            $this->editingUserId = $userId;
            $this->employee_id = $user->employee_id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = '';
            $this->password_confirmation = '';
            $this->showEditModal = true;
        }
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingUserId = null;
        $this->employee_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
    }

    public function updatedEmployeeId($value): void
    {
        if ($value) {
            $employee = Employee::find($value);
            if ($employee) {
                $this->name = $employee->full_name;
                $this->email = $employee->email;
            }
        } else {
            $this->name = '';
            $this->email = '';
        }
    }

    public function save(): void
    {
        if ($this->employee_id) {
            $employee = Employee::find($this->employee_id);
            if ($employee) {
                $this->name = $employee->full_name;
                $this->email = $employee->email;
            }
        }

        $isEditing = ! is_null($this->editingUserId);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($isEditing ? ',' . $this->editingUserId : ''),
            'password' => $isEditing ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
        ];

        $this->validate($rules);

        if ($isEditing) {
            $user = User::find($this->editingUserId);
            if ($user) {
                $user->employee_id = $this->employee_id;
                $user->name = $this->name;
                $user->email = $this->email;
                if ($this->password) {
                    $user->password = Hash::make($this->password);
                }
                $user->save();
            }

            $flashMessage = 'User updated successfully.';
            $flashTitle = 'User updated';
        } else {
            User::create([
                'employee_id' => $this->employee_id,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $flashMessage = 'User created successfully.';
            $flashTitle = 'User created';
        }

        $this->closeModal();

        session()->flash('flash', [
            'type' => 'success',
            'title' => $flashTitle,
            'message' => $flashMessage,
        ]);
    }

    public function deleteUser(int $userId): void
    {
        User::destroy($userId);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'User deleted',
            'message' => 'User deleted successfully.',
        ]);
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $stats = [
            'total' => User::count(),
            'active' => User::whereNotNull('email_verified_at')->count(),
            'pending' => User::whereNull('email_verified_at')->count(),
        ];

        $employees = Employee::orderBy('full_name')
            ->get(['id', 'full_name', 'email', 'code']);

        return view('livewire.general-setup.users.index', [
            'users' => $users,
            'stats' => $stats,
            'employees' => $employees,
        ])->layoutData([
            'pageTitle' => 'Users',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('users'),
        ]);
    }
}
