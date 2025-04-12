<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存の支店データを取得（Branch が存在することが前提）
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $this->command->info('支店データが存在しないため、車両のシーディングをスキップします。');
            return;
        }

        // 車両タイプと車両名の候補を定義
        $vehicleTypes = ['軽', 'バン', 'トラック', 'セダン', 'SUV'];
        $vehicleNames = [
            'フィット', 'ノート', 'プリウス', 'アルファード', 'クラウン',
            'ヴェゼル', 'スイフト', 'ヴィッツ', 'カローラ', 'アクア'
        ];

        // ダミーデータとして 50 台の車両を作成
        for ($i = 0; $i < 50; $i++) {
            Vehicle::create([
                'branch_id'      => $branches->random()->id,
                'name'           => fake()->randomElement($vehicleNames),
                'vehicle_type'   => fake()->randomElement($vehicleTypes),
                'vehicle_number' => strtoupper(fake()->bothify('??-###-??')),
            ]);
        }
        $this->command->info('車両のダミーデータを作成しました。');
    }
}
