<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bundle extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'branch_id',
        'sku',
        'name',
        'description',
        'bundle_price',
        'original_price',
        'discount_amount',
        'discount_percent',
        'image_path',
        'is_active',
        'valid_from',
        'valid_until',
    ];

    protected $casts = [
        'bundle_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getSavingsAttribute(): float
    {
        return $this->original_price - $this->bundle_price;
    }

    public function getSavingsPercentAttribute(): float
    {
        if ($this->original_price <= 0) {
            return 0;
        }

        return round(($this->savings / $this->original_price) * 100, 2);
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now()->startOfDay();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return true;
    }

    public function recalculatePrices(): void
    {
        $this->original_price = $this->items->sum('subtotal');
        $this->discount_amount = $this->original_price - $this->bundle_price;

        if ($this->original_price > 0) {
            $this->discount_percent = round(($this->discount_amount / $this->original_price) * 100, 2);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForBranch($query, ?int $branchId)
    {
        return $query->where(function ($q) use ($branchId) {
            $q->whereNull('branch_id');
            if ($branchId) {
                $q->orWhere('branch_id', $branchId);
            }
        });
    }
}
