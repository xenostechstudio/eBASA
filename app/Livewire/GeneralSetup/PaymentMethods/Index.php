<?php

namespace App\Livewire\GeneralSetup\PaymentMethods;

use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    public array $paymentMethods = [
        ['code' => 'cash', 'name' => 'Cash', 'icon' => 'heroicon-o-banknotes', 'active' => true],
        ['code' => 'card', 'name' => 'Debit/Credit Card', 'icon' => 'heroicon-o-credit-card', 'active' => true],
        ['code' => 'qris', 'name' => 'QRIS', 'icon' => 'heroicon-o-qr-code', 'active' => true],
        ['code' => 'transfer', 'name' => 'Bank Transfer', 'icon' => 'heroicon-o-building-library', 'active' => true],
        ['code' => 'ewallet', 'name' => 'E-Wallet', 'icon' => 'heroicon-o-device-phone-mobile', 'active' => false],
    ];

    public array $settings = [
        'allowMixedPayments' => true,
        'requireConfirmation' => true,
        'autoSelectCash' => false,
    ];

    // Modal state
    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingIndex = null;

    // Form fields
    public string $methodName = '';

    public string $methodCode = '';

    public string $methodIcon = 'heroicon-o-banknotes';

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $index): void
    {
        if (isset($this->paymentMethods[$index])) {
            $this->editingIndex = $index;
            $this->methodName = $this->paymentMethods[$index]['name'];
            $this->methodCode = $this->paymentMethods[$index]['code'];
            $this->methodIcon = $this->paymentMethods[$index]['icon'];
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
        $this->editingIndex = null;
        $this->methodName = '';
        $this->methodCode = '';
        $this->methodIcon = 'heroicon-o-banknotes';
        $this->resetValidation();
    }

    public function save(): void
    {
        $isEditing = $this->editingIndex !== null;

        $this->validate([
            'methodName' => 'required|string|max:255',
            'methodCode' => 'required|string|max:50',
            'methodIcon' => 'required|string',
        ]);

        if ($isEditing && isset($this->paymentMethods[$this->editingIndex])) {
            $methods = $this->paymentMethods;
            $methods[$this->editingIndex]['name'] = $this->methodName;
            $methods[$this->editingIndex]['code'] = $this->methodCode;
            $methods[$this->editingIndex]['icon'] = $this->methodIcon;
            $this->paymentMethods = $methods;

            $flashMessage = 'Payment method updated successfully.';
            $flashTitle = 'Payment method updated';
        } else {
            $this->paymentMethods[] = [
                'code' => $this->methodCode,
                'name' => $this->methodName,
                'icon' => $this->methodIcon,
                'active' => true,
            ];

            $flashMessage = 'Payment method created successfully.';
            $flashTitle = 'Payment method created';
        }

        $this->closeModal();

        session()->flash('flash', [
            'type' => 'success',
            'title' => $flashTitle,
            'message' => $flashMessage,
        ]);
    }

    public function togglePaymentMethod(int $index): void
    {
        if (isset($this->paymentMethods[$index])) {
            $methods = $this->paymentMethods;
            $methods[$index]['active'] = ! $methods[$index]['active'];
            $this->paymentMethods = $methods;
        }
    }

    public function toggleSetting(string $key): void
    {
        if (isset($this->settings[$key])) {
            $settings = $this->settings;
            $settings[$key] = ! $settings[$key];
            $this->settings = $settings;
        }
    }

    public function render()
    {
        return view('livewire.general-setup.payment-methods.index', [
            'paymentMethods' => $this->paymentMethods,
            'settings' => $this->settings,
        ])->layoutData([
            'pageTitle' => 'Payment Methods',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('payment-methods'),
        ]);
    }
}
