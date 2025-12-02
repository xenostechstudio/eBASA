<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_APPROVAL = 'pending_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PARTIALLY_RECEIVED = 'partially_received';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_PENDING_APPROVAL => 'Pending Approval',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_PARTIALLY_RECEIVED => 'Partially Received',
        self::STATUS_RECEIVED => 'Received',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    protected $fillable = [
        'reference',
        'supplier_id',
        'warehouse_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'payment_terms',
        'requested_by',
        'delivery_instructions',
        'notes',
        'subtotal',
        'tax_amount',
        'total',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // ─────────────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────────────

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─────────────────────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [
            self::STATUS_APPROVED,
            self::STATUS_PARTIALLY_RECEIVED,
        ]);
    }

    public function scopeReceivable($query)
    {
        return $query->whereIn('status', [
            self::STATUS_APPROVED,
            self::STATUS_PARTIALLY_RECEIVED,
        ]);
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
        if ($last && preg_match('/PO-' . $year . '-(\d+)/', $last->reference, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return 'PO-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items()->sum('subtotal');
        $this->tax_amount = $this->items()->sum('tax_amount');
        $this->total = $this->subtotal + $this->tax_amount;
        $this->saveQuietly();
    }

    public function updateReceiptStatus(): void
    {
        $totalOrdered = $this->items()->sum('quantity');
        $totalReceived = $this->items()->sum('received_quantity');

        if ($totalReceived === 0) {
            // No change if nothing received yet
            return;
        }

        if ($totalReceived >= $totalOrdered) {
            $this->status = self::STATUS_RECEIVED;
        } else {
            $this->status = self::STATUS_PARTIALLY_RECEIVED;
        }

        $this->saveQuietly();
    }

    public function canReceive(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_PARTIALLY_RECEIVED,
        ]);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'slate',
            self::STATUS_PENDING_APPROVAL => 'amber',
            self::STATUS_APPROVED => 'sky',
            self::STATUS_PARTIALLY_RECEIVED => 'violet',
            self::STATUS_RECEIVED => 'emerald',
            self::STATUS_CANCELLED => 'rose',
            default => 'slate',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
