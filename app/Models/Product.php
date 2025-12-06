<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'products';

    protected $fillable = [
        'sku',
        'name',
        'brand',
        'barcode',
        'description',
        'category_id',
        'cost_price',
        'selling_price',
        'unit',
        'stock_quantity',
        'min_stock_level',
        'size',
        'color',
        'tax_group',
        'is_active',
        'track_inventory',
        'image_path',
        'internal_notes',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
        'track_inventory' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Suppliers that can provide this product (with pivot data).
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products')
            ->withPivot(['supplier_price', 'supplier_sku', 'lead_time_days', 'min_order_qty', 'is_preferred'])
            ->withTimestamps();
    }

    public function branchProducts(): HasMany
    {
        return $this->hasMany(BranchProduct::class);
    }

    public function bundleItems(): HasMany
    {
        return $this->hasMany(BundleItem::class);
    }

    public function priceListItems(): HasMany
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

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
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
