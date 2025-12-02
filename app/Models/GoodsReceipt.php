<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceipt extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    protected $fillable = [
        'reference',
        'purchase_order_id',
        'warehouse_id',
        'received_date',
        'status',
        'received_by_name',
        'delivery_note_number',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    // ─────────────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────────────

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ─────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────

    public static function generateReference(): string
    {
        $year = now()->format('Y');
        $last = static::withTrashed()
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($last && preg_match('/GR-' . $year . '-(\d+)/', $last->reference, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return 'GR-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'amber',
            self::STATUS_COMPLETED => 'emerald',
            self::STATUS_CANCELLED => 'rose',
            default => 'slate',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Complete the goods receipt: update PO item received quantities and stock.
     */
    public function complete(): void
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return;
        }

        foreach ($this->items as $grItem) {
            // Update PO item received quantity
            $poItem = $grItem->purchaseOrderItem;
            $poItem->received_quantity += $grItem->quantity_accepted;
            $poItem->save();

            // TODO: Update product stock in warehouse
            // This would integrate with your inventory system
        }

        $this->status = self::STATUS_COMPLETED;
        $this->save();

        // Update PO status
        $this->purchaseOrder->updateReceiptStatus();
    }
}
