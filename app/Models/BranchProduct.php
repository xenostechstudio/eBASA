<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchProduct extends Model
{
    use Auditable;

    protected $fillable = [
        'branch_id',
        'product_id',
        'selling_price',
        'cost_price',
        'stock_quantity',
        'min_stock_level',
        'max_stock_level',
        'is_available',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get effective selling price (branch-specific or master)
     */
    public function getEffectiveSellingPriceAttribute(): float
    {
        return $this->selling_price ?? $this->product->selling_price ?? 0;
    }

    /**
     * Get effective cost price (branch-specific or master)
     */
    public function getEffectiveCostPriceAttribute(): float
    {
        return $this->cost_price ?? $this->product->cost_price ?? 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }
}
