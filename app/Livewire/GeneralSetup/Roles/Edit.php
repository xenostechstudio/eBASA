<?php

namespace App\Livewire\GeneralSetup\Roles;

use App\Models\Role;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
#[Title('Edit Role')]
class Edit extends Component
{
    public Role $role;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public string $color = 'slate';

    public bool $is_system = false;

    public function mount(Role $role): void
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->description = $role->description ?? '';
        $this->color = $role->color ?? 'slate';
        $this->is_system = $role->is_system;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', 'unique:roles,name,'.$this->role->id],
            'slug' => ['required', 'string', 'max:120', 'alpha_dash', 'unique:roles,slug,'.$this->role->id],
            'description' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
            'is_system' => ['boolean'],
        ];
    }

    public function updatedName(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        $this->role->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?: null,
            'color' => $data['color'] ?: 'slate',
            'is_system' => $data['is_system'],
        ]);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Role updated',
            'message' => 'Role updated successfully.',
        ]);

        $this->redirectRoute('general-setup.roles.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.roles.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.general-setup.roles.edit', [
            'role' => $this->role,
        ])->layoutData([
            'pageTitle' => 'Edit Role',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('roles'),
        ]);
    }
}
