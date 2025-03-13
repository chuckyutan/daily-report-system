<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all branch IDs
        $branchIds = Branch::pluck('id')->toArray();

        if (empty($branchIds)) {
            $this->command->error('No branches found. Please run branch seeder first.');
            return;
        }

        // Admin user
        User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => $branchIds[0],
            'is_active' => true,
        ]);

        // Create managers for each branch
        foreach ($branchIds as $branchId) {
            User::create([
                'name' => "支店長-{$branchId}",
                'email' => "manager{$branchId}@example.com",
                'password' => Hash::make('password'),
                'role' => 'manager',
                'branch_id' => $branchId,
                'is_active' => true,
            ]);
        }

        // Create multiple staff members for each branch
        foreach ($branchIds as $branchId) {
            for ($i = 1; $i <= 3; $i++) {
                User::create([
                    'name' => "スタッフ-{$branchId}-{$i}",
                    'email' => "staff{$branchId}{$i}@example.com",
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                    'branch_id' => $branchId,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Users created successfully.');
    }
}