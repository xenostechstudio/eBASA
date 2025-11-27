<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'barcode',
        'description',
        'category_id',
        'cost_price',
        'selling_price',
        'unit',
        'stock_quantity',
        'min_stock_level',
        'is_active',
        'track_inventory',
        'image_path',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
        'track_inventory' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RetailProductCategory::class, 'category_id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->track_inventory && $this->stock_quantity <= $this->min_stock_level;
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->cost_price <= 0) {
            return 0;
        }

        return round((($this->selling_price - $this->cost_price) / $this->cost_price) * 100, 2);
    }
}
