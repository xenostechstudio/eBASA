<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class BranchUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and branches
        $users = User::all();
        $branches = Branch::all();

        if ($users->isEmpty() || $branches->isEmpty()) {
            $this->command->info('No users or branches found. Skipping branch_user seeding.');
            return;
        }

        // Get the super admin user (first user or user with super-admin role)
        $superAdmin = User::whereHas('roles', function ($query) {
            $query->where('slug', 'super-admin');
        })->first() ?? User::first();

        // Set super admin to have access to all branches
        if ($superAdmin) {
            $superAdmin->update(['branch_access_type' => 'all']);
            $this->command->info("Set super admin ({$superAdmin->name}) to have access to all branches.");
        }

        // For other users, assign them to random branches
        $otherUsers = $users->where('id', '!=', $superAdmin?->id);

        foreach ($otherUsers as $user) {
            // Randomly decide if user has all access or selected branches
            $hasAllAccess = fake()->boolean(20); // 20% chance of all access

            if ($hasAllAccess) {
                $user->update(['branch_access_type' => 'all']);
                $this->command->info("User {$user->name}: All branches access");
            } else {
                $user->update(['branch_access_type' => 'selected']);

                // Assign 1-3 random branches
                $randomBranches = $branches->random(min(fake()->numberBetween(1, 3), $branches->count()));
                $user->branches()->sync($randomBranches->pluck('id')->toArray());

                $branchNames = $randomBranches->pluck('name')->join(', ');
                $this->command->info("User {$user->name}: {$branchNames}");
            }
        }

        $this->command->info('Branch user relationships seeded successfully.');
    }
}
