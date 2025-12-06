<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Concerns\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'branch_access_type',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class)->withTimestamps();
    }

    /**
     * Check if user has access to a specific branch.
     */
    public function hasAccessToBranch(int|Branch $branch): bool
    {
        // Super admin always has access to all branches
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // Users with 'all' access type can access all branches
        if ($this->branch_access_type === 'all') {
            return true;
        }

        $branchId = $branch instanceof Branch ? $branch->id : $branch;

        return $this->branches()->where('branches.id', $branchId)->exists();
    }

    /**
     * Check if user has access to all branches.
     */
    public function hasAllBranchAccess(): bool
    {
        return $this->hasRole('super-admin') || $this->branch_access_type === 'all';
    }

    /**
     * Get accessible branch IDs for the user.
     */
    public function getAccessibleBranchIds(): array
    {
        if ($this->hasAllBranchAccess()) {
            return Branch::pluck('id')->toArray();
        }

        return $this->branches()->pluck('branches.id')->toArray();
    }

    /**
     * Get accessible branches query for the user.
     */
    public function accessibleBranches()
    {
        if ($this->hasAllBranchAccess()) {
            return Branch::query();
        }

        return $this->branches();
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;

        return $this->roles()
            ->whereIn('slug', $roles)
            ->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function hasPermission(string|array $permissions): bool
    {
        $permissions = (array) $permissions;

        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('slug', $permissions);
            })
            ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        return $this->hasPermission($permissions);
    }
}
