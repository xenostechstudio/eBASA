<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@basa.local'],
            [
                'name' => 'BASA Admin',
                'password' => Hash::make('basa1234'),
                'email_verified_at' => now(),
            ],
        );
    }
}
