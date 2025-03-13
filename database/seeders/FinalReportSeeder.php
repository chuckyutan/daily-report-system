<?php

namespace Database\Seeders;

use App\Models\FinalReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FinalReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user IDs to assign reports to
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        // Create 50 final reports over the last 3 months
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        for ($i = 0; $i < 50; $i++) {
            $reportDate = Carbon::parse($startDate)->addDays(rand(0, $endDate->diffInDays($startDate)));

            FinalReport::create([
                'user_id' => $userIds[array_rand($userIds)],
                'report_date' => $reportDate->format('Y-m-d'),

                // R新規
                'r_new_count' => rand(0, 10),
                'r_new_volume' => rand(0, 50) / 10,
                'r_new_amount' => rand(0, 20) * 10000,

                // R継続
                'r_continue_count' => rand(0, 15),
                'r_continue_volume' => rand(0, 75) / 10,
                'r_continue_amount' => rand(0, 30) * 10000,

                // RGH施工
                'rgh_count' => rand(0, 8),
                'rgh_amount' => rand(0, 15) * 10000,

                // Rケア
                'r_care_volume' => rand(0, 30) / 10,

                // フィルター
                'filter_volume' => rand(0, 40) / 10,
                'filter_amount' => rand(0, 12) * 10000,

                // ハウスグッズ
                'house_goods_volume' => rand(0, 25) / 10,
                'house_goods_amount' => rand(0, 10) * 10000,

                // ケア
                'care_count' => rand(0, 12),
                'care_volume' => rand(0, 60) / 10,
                'care_amount' => rand(0, 25) * 10000,

                // テレ配
                'tele_distribution_count' => rand(0, 20),
                'tele_distribution_volume' => rand(0, 100) / 10,

                // テレ訪
                'tele_visit_count' => rand(0, 15),
                'tele_visit_volume' => rand(0, 75) / 10,

                // 追加金額
                'additional_amount' => rand(0, 5) * 10000,

                // 清掃
                'cleaning_new_count' => rand(0, 5),
                'cleaning_continue_count' => rand(0, 10),

                // チラシ
                'flyer_m_count' => rand(0, 50) * 10,
                'flyer_house_count' => rand(0, 30) * 10,
                'flyer_shop_count' => rand(0, 10) * 10,

                // 枠付
                'frame_new_count' => rand(0, 3),

                // AF販売パック
                'af_pack_count' => rand(0, 5),

                // 最終情報
                'final_time' => sprintf('%02d:%02d:00', rand(9, 19), rand(0, 59)),
                'final_location' => '東京都' . ['新宿区', '渋谷区', '千代田区', '港区', '中央区'][rand(0, 4)],
            ]);
        }

        $this->command->info('50 final reports created successfully.');
    }
}