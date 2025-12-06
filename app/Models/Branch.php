<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'code',
        'name',
        'city',
        'province',
        'address',
        'phone',
        'email',
        'manager_name',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function branchProducts(): HasMany
    {
        return $this->hasMany(BranchProduct::class);
    }

    public function bundles(): HasMany
    {
        return $this->hasMany(Bundle::class);
    }

    public function priceLists(): HasMany
    {
        return $this->hasMany(PriceList::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
