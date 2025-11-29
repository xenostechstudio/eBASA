<?php

namespace App\Livewire\Inventory\Stock\Adjustments;

use App\Enums\StockAdjustmentStatus;
use App\Models\StockAdjustment;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Show extends Component
{
    public int $adjustmentId;

    /** @var array<string, mixed>|null */
    public ?array $adjustmentData = null;

    public function mount(int $adjustment): void
    {
        $this->adjustmentId = $adjustment;
        $this->loadAdjustment();
    }

    protected function loadAdjustment(): void
    {
        $model = StockAdjustment::with(['warehouse', 'items.product'])->findOrFail($this->adjustmentId);

        $firstItem = $model->items->first();

        $this->adjustmentData = [
            'id' => $model->id,
            'reference' => $model->reference,
            'warehouse' => $model->warehouse?->name,
            'product_name' => $firstItem?->product?->name,
            'type' => $model->type,
            'status' => $model->status instanceof StockAdjustmentStatus ? $model->status->value : $model->status,
            'quantity' => $model->items->sum('quantity'),
            'reason' => $model->reason,
            'created_at' => $model->created_at,
        ];
    }
    public function markOnProcess(): void
    {
        $this->transitionTo(StockAdjustmentStatus::OnProcess, 'info', 'Adjustment updated', 'Adjustment moved to on process');
    }

    public function markCompleted(): void
    {
        $this->transitionTo(StockAdjustmentStatus::Completed, 'success', 'Adjustment updated', 'Adjustment marked as completed.');
    }

    public function markCancelled(): void
    {
        $this->transitionTo(StockAdjustmentStatus::Cancelled, 'warning', 'Adjustment updated', 'Adjustment has been cancelled.');
    }

    protected function transitionTo(StockAdjustmentStatus $to, string $flashType, string $flashTitle, string $flashMessage): void
    {
        $model = StockAdjustment::findOrFail($this->adjustmentId);

        $current = $model->status;

        if (! $current instanceof StockAdjustmentStatus) {
            return;
        }

        if (! $current->canTransitionTo($to)) {
            return;
        }

        $model->status = $to;
        $model->save();

        session()->flash('flash', [
            'type' => $flashType,
            'title' => $flashTitle,
            'message' => $flashMessage,
        ]);
        $this->loadAdjustment();
    }

    public function render(): View
    {
        return view('livewire.inventory.stock.adjustments.show', [
            'adjustment' => $this->adjustmentData,
        ])->layoutData([
            'pageTitle' => 'Adjustment '.($this->adjustmentData['reference'] ?? ''),
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'adjustments'),
        ]);
    }
}
