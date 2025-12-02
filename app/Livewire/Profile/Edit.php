<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    // Profile form
    public string $name = '';
    public string $email = '';

    // Password form
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Delete account
    public string $delete_password = '';
    public bool $showDeleteModal = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
        ]);

        $user = Auth::user();
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        session()->flash('status', 'Profile updated successfully');
        $this->dispatch('notify', message: 'Profile updated');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('password_status', 'Password updated successfully');
        $this->dispatch('notify', message: 'Password updated');
    }

    public function confirmDeleteAccount(): void
    {
        $this->showDeleteModal = true;
    }

    public function deleteAccount(): void
    {
        $this->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.profile.edit')->layoutData([
            'pageTitle' => 'My Profile',
            'pageTagline' => 'Account Settings',
            'activeModule' => null,
            'navLinks' => [],
        ]);
    }
}
