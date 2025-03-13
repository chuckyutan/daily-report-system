<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['branch_name' => '東京本社', 'manager_name' => '田中太郎'],
            ['branch_name' => '大阪支店', 'manager_name' => '佐藤次郎'],
            ['branch_name' => '名古屋支店', 'manager_name' => '鈴木三郎'],
            ['branch_name' => '福岡支店', 'manager_name' => '高橋四郎'],
            ['branch_name' => '札幌支店', 'manager_name' => '伊藤五郎'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }

        $this->command->info('5 branches created successfully.');
    }
}