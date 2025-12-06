<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListItem extends Model
{
    protected $fillable = [
        'price_list_id',
        'product_id',
        'price',
        'discount_percent',
        'discount_amount',
        'min_qty',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get final price after discount
     */
    public function getFinalPriceAttribute(): float
    {
        $price = $this->price;

        if ($this->discount_amount > 0) {
            $price -= $this->discount_amount;
        } elseif ($this->discount_percent > 0) {
            $price -= ($price * $this->discount_percent / 100);
        }

        return max(0, $price);
    }
}
