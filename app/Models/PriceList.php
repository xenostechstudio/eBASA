<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceList extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'branch_id',
        'code',
        'name',
        'description',
        'type',
        'min_order_amount',
        'is_default',
        'is_active',
        'valid_from',
        'valid_until',
        'priority',
    ];

    protected $casts = [
        'min_order_amount' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public const TYPES = [
        'retail' => 'Retail',
        'wholesale' => 'Wholesale',
        'member' => 'Member',
        'promo' => 'Promo',
        'custom' => 'Custom',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PriceListItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getProductsCountAttribute(): int
    {
        return $this->items()->distinct('product_id')->count('product_id');
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

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
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

    public function scopeValidNow($query)
    {
        $now = now()->startOfDay();

        return $query->where(function ($q) use ($now) {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
        });
    }
}
