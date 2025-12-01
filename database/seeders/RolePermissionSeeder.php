<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolesData = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access',
                'color' => 'red',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Branch management and reports',
                'color' => 'amber',
            ],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
                'description' => 'POS and transaction access',
                'color' => 'emerald',
            ],
            [
                'name' => 'Inventory Staff',
                'slug' => 'inventory-staff',
                'description' => 'Stock management',
                'color' => 'sky',
            ],
            [
                'name' => 'HR',
                'slug' => 'hr',
                'description' => 'Human Resources and staff management',
                'color' => 'slate',
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access',
                'color' => 'slate',
            ],
        ];

        foreach ($rolesData as $data) {
            Role::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'color' => $data['color'],
                    'is_system' => true,
                ],
            );
        }

        $permissionsData = [
            // Users
            ['group' => 'users', 'name' => 'view users', 'slug' => 'users.view'],
            ['group' => 'users', 'name' => 'create users', 'slug' => 'users.create'],
            ['group' => 'users', 'name' => 'edit users', 'slug' => 'users.edit'],
            ['group' => 'users', 'name' => 'delete users', 'slug' => 'users.delete'],

            // Products
            ['group' => 'products', 'name' => 'view products', 'slug' => 'products.view'],
            ['group' => 'products', 'name' => 'create products', 'slug' => 'products.create'],
            ['group' => 'products', 'name' => 'edit products', 'slug' => 'products.edit'],
            ['group' => 'products', 'name' => 'delete products', 'slug' => 'products.delete'],

            // Transactions
            ['group' => 'transactions', 'name' => 'view transactions', 'slug' => 'transactions.view'],
            ['group' => 'transactions', 'name' => 'create transactions', 'slug' => 'transactions.create'],
            ['group' => 'transactions', 'name' => 'refund transactions', 'slug' => 'transactions.refund'],
            ['group' => 'transactions', 'name' => 'export transactions', 'slug' => 'transactions.export'],

            // Inventory
            ['group' => 'inventory', 'name' => 'view inventory', 'slug' => 'inventory.view'],
            ['group' => 'inventory', 'name' => 'adjust stock', 'slug' => 'inventory.adjust-stock'],
            ['group' => 'inventory', 'name' => 'transfer stock', 'slug' => 'inventory.transfer-stock'],
            ['group' => 'inventory', 'name' => 'manage branches', 'slug' => 'inventory.manage-branches'],

            // Reports
            ['group' => 'reports', 'name' => 'view reports', 'slug' => 'reports.view'],
            ['group' => 'reports', 'name' => 'export reports', 'slug' => 'reports.export'],
            ['group' => 'reports', 'name' => 'view analytics', 'slug' => 'reports.view-analytics'],

            // System
            ['group' => 'system', 'name' => 'manage settings', 'slug' => 'system.manage-settings'],
            ['group' => 'system', 'name' => 'access general setup', 'slug' => 'system.access-general-setup'],
        ];

        foreach ($permissionsData as $data) {
            Permission::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'group' => $data['group'],
                    'description' => $data['name'],
                ],
            );
        }

        $allPermissionSlugs = collect($permissionsData)->pluck('slug')->all();

        $rolePermissions = [
            'super-admin' => $allPermissionSlugs,
            'manager' => [
                'users.view', 'users.create', 'users.edit',
                'products.view', 'products.create', 'products.edit',
                'transactions.view', 'transactions.create', 'transactions.refund', 'transactions.export',
                'inventory.view', 'inventory.adjust-stock', 'inventory.transfer-stock', 'inventory.manage-branches',
                'reports.view', 'reports.export', 'reports.view-analytics',
                'system.access-general-setup',
            ],
            'cashier' => [
                'products.view',
                'transactions.view', 'transactions.create',
            ],
            'inventory-staff' => [
                'products.view', 'products.edit',
                'inventory.view', 'inventory.adjust-stock', 'inventory.transfer-stock', 'inventory.manage-branches',
            ],
            'hr' => [
                // HR-specific permissions will be added later
            ],
            'viewer' => [
                'users.view',
                'products.view',
                'transactions.view',
                'inventory.view',
                'reports.view', 'reports.view-analytics',
            ],
        ];

        foreach ($rolePermissions as $roleSlug => $permissionSlugs) {
            $role = Role::where('slug', $roleSlug)->first();

            if (! $role) {
                continue;
            }

            $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id');

            if ($permissionIds->isEmpty()) {
                continue;
            }

            $role->permissions()->syncWithoutDetaching($permissionIds->all());
        }

        // Attach Super Admin role to the seeded admin user, if both exist
        $admin = User::where('email', 'admin@basa.local')->first();
        $superAdminRole = Role::where('slug', 'super-admin')->first();

        if ($admin && $superAdminRole) {
            $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
