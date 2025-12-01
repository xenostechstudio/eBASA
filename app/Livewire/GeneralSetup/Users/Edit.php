<?php

namespace App\Livewire\GeneralSetup\Users;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
#[Title('Edit User')]
class Edit extends Component
{
    public ?int $userId = null;
    public ?User $editingUser = null;

    public ?int $employee_id = null;
    public ?int $role_id = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(User $user): void
    {
        $this->userId = $user->id;
        $this->editingUser = $user->load(['employee', 'createdBy', 'updatedBy', 'roles']);

        $this->employee_id = $user->employee_id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles()->first()?->id;
        $this->password = '';
        $this->password_confirmation = '';
    }

    protected function rules(): array
    {
        return [
            'employee_id' => 'nullable|exists:employees,id',
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    #[Computed]
    public function employees()
    {
        return Employee::orderBy('full_name')
            ->get(['id', 'full_name', 'email', 'code']);
    }

    #[Computed]
    public function roles()
    {
        return Role::orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    public function updatedEmployeeId($value): void
    {
        if ($value) {
            $employee = Employee::find($value);
            if ($employee) {
                $this->name = $employee->full_name;
                $this->email = $employee->email ?? '';
            }
        }
    }

    public function save(): void
    {
        $linkedToEmployee = $this->editingUser && $this->editingUser->employee_id;

        // If not yet linked, allow linking and auto-fill from selected employee
        if (! $linkedToEmployee && $this->employee_id) {
            $employee = Employee::find($this->employee_id);
            if ($employee) {
                $this->name = $employee->full_name;
                $this->email = $employee->email ?? '';
            }
        }

        // If already linked, lock name/email/employee_id to original values
        if ($linkedToEmployee) {
            $this->employee_id = $this->editingUser->employee_id;
            $this->name = $this->editingUser->name;
            $this->email = $this->editingUser->email;
        }

        $this->validate();

        if (! $this->userId) {
            return;
        }

        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        // Only allow changing link/name/email when not already linked
        if (! $linkedToEmployee) {
            $user->employee_id = $this->employee_id;
            $user->name = $this->name;
            $user->email = $this->email;
        }

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $user->roles()->sync($this->role_id ? [$this->role_id] : []);

        $this->editingUser = $user->fresh(['employee', 'createdBy', 'updatedBy', 'roles']);
        $this->password = '';
        $this->password_confirmation = '';

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'User updated',
            'message' => 'User updated successfully.',
        ]);
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.users.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.general-setup.users.edit', [
            'editingUser' => $this->editingUser,
        ])->layoutData([
            'pageTitle' => 'Edit User',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('users'),
        ]);
    }
}
