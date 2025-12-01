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
#[Title('Add User')]
class Create extends Component
{
    public ?int $employee_id = null;
    public ?int $role_id = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'employee_id' => 'nullable|exists:employees,id',
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
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
                $this->email = $employee->email ?? '';
            }
        }

        $this->validate();

        $user = User::create([
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->roles()->sync($this->role_id ? [$this->role_id] : []);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'User created',
            'message' => 'User created successfully.',
        ]);

        $this->redirectRoute('general-setup.users.edit', ['user' => $user->id], navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.users.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.general-setup.users.create')->layoutData([
            'pageTitle' => 'Add User',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('users'),
        ]);
    }
}
