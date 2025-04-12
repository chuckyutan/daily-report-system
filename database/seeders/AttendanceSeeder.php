<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('ユーザーが存在しません。先にユーザーシーダーを実行してください。');
            return;
        }

        // Vehicle が存在する場合のID一覧
        $vehicleIds = Vehicle::pluck('id')->toArray();

        // 過去30日間の日付を生成（古い順に）
        $startDate = now()->subDays(30);

        // 各日ごとに全ユーザーの出勤報告を作成（80%の確率で作成）
        for ($day = 0; $day < 30; $day++) {
            $currentDate = (clone $startDate)->addDays($day);
            $reportDate = $currentDate->format('Y-m-d');

            foreach ($users as $user) {
                // 80%の確率で出勤報告を作成する
                if (rand(1, 100) <= 80) {
                    Attendance::create([
                        'user_id'       => $user->id,
                        'vehicle_id'    => (count($vehicleIds) > 0 && rand(0, 1)) ? fake()->randomElement($vehicleIds) : null,
                        'report_date'   => $reportDate,
                        'start_location'=> fake()->randomElement([
                            '東京都新宿区',
                            '東京都渋谷区',
                            '大阪府北区',
                            '福岡県博多区',
                            '愛知県名古屋市',
                        ]),
                        // 出発／勤務開始時間を 7:00～9:59 の間でランダム生成
                        'start_time'    => Carbon::createFromTime(rand(7,9), rand(0,59), 0)->format('H:i:s'),
                        'planned_visits'=> rand(0, 20),
                    ]);
                }
            }
        }

        $this->command->info('過去30日間の出勤報告データを作成しました。');
    }
}
