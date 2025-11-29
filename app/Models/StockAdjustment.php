<?php

namespace App\Models;

use App\Enums\StockAdjustmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'warehouse_id',
        'type',
        'status',
        'adjustment_date',
        'reason',
        'notes',
    ];

    protected $casts = [
        'adjustment_date' => 'date',
        'status' => StockAdjustmentStatus::class,
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }
}
