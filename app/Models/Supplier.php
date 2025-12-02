<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'code',
        'name',
        'contact_name',
        'email',
        'phone',
        'tax_number',
        'address',
        'payment_terms',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'payment_terms' => 'integer',
        'is_active' => 'boolean',
    ];

    // ─────────────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────────────

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Products this supplier can provide (with pivot data).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'supplier_products')
            ->withPivot(['supplier_price', 'supplier_sku', 'lead_time_days', 'min_order_qty', 'is_preferred'])
            ->withTimestamps();
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
    // Scopes
    // ─────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─────────────────────────────────────────────────────────────
    // Accessors / Helpers
    // ─────────────────────────────────────────────────────────────

    public function getLifetimeSpendAttribute(): float
    {
        return $this->purchaseOrders()
            ->whereIn('status', ['approved', 'partially_received', 'received'])
            ->sum('total');
    }

    public function getOpenOrdersCountAttribute(): int
    {
        return $this->purchaseOrders()
            ->whereIn('status', ['approved', 'partially_received'])
            ->count();
    }

    public static function generateCode(): string
    {
        $last = static::withTrashed()->orderByDesc('id')->first();
        $nextNumber = $last ? ((int) substr($last->code, 4)) + 1 : 1;

        return 'SUP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
