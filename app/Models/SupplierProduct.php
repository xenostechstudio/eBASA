<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SupplierProduct extends Pivot
{
    protected $table = 'supplier_products';

    public $incrementing = true;

    protected $fillable = [
        'supplier_id',
        'product_id',
        'supplier_price',
        'supplier_sku',
        'lead_time_days',
        'min_order_qty',
        'is_preferred',
    ];

    protected $casts = [
        'supplier_price' => 'decimal:2',
        'lead_time_days' => 'integer',
        'min_order_qty' => 'integer',
        'is_preferred' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
