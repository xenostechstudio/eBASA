<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Branch|null $branch
 * @property-read Department|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Department> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Position> $positions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Employee> $employees
 */

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'branch_id',
        'parent_id',
        'lead_name',
        'lead_email',
        'description',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
