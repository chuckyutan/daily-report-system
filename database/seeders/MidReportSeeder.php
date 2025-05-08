<?php

namespace Database\Seeders;

use App\Models\MidReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MidReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ユーザーID一覧を取得
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->error('ユーザーが存在しません。先にユーザーシーダーを実行してください。');
            return;
        }

        // 過去30日間のレポートを作成する
        $numberOfDays = 30;
        // 30日前の日付を開始日とする
        $startDate = Carbon::now()->subDays($numberOfDays);

        foreach ($userIds as $userId) {
            for ($i = 0; $i < $numberOfDays; $i++) {
                $reportDate = $startDate->copy()->addDays($i);
                $reportDateStr = $reportDate->format('Y-m-d');

                MidReport::create([
                    'user_id' => $userId,
                    'report_date' => $reportDateStr,
                    'mid_count' => rand(0, 10),
                    'mid_care_count' => rand(0, 10),
                    'remaining_visits' => rand(0, 10),
                ]);
            }
        }


        $this->command->info('過去30日間のレポートを作成しました。');
    }
}