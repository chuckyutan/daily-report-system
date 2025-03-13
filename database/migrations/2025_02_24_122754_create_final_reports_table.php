<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('final_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('report_date');

            // ■ R新規
            $table->unsignedInteger('r_new_count')->default(0);      // R新規件数
            $table->decimal('r_new_volume', 5, 1)->default(0); // R新規本数
            $table->unsignedInteger('r_new_amount')->default(0); // R新規金額

            // ■ R継続
            $table->unsignedInteger('r_continue_count')->default(0);      // R継続件数
            $table->decimal('r_continue_volume', 5, 1)->default(0); // R継続本数
            $table->unsignedInteger('r_continue_amount')->default(0); // R継続金額

            // ■ RGH施工
            $table->unsignedInteger('rgh_count')->default(0);         // RGH施工件数
            $table->unsignedInteger('rgh_amount')->default(0); // RGH施工金額

            // ■ Rケア
            $table->decimal('r_care_volume', 5, 1)->default(0); // Rケア本数

            // ■ フィルター
            $table->decimal('filter_volume', 5, 1)->default(0); // フィルター本数
            $table->unsignedInteger('filter_amount')->default(0); // フィルター金額

            // ■ ハウスグッズ
            $table->decimal('house_goods_volume', 5, 1)->default(0); // ハウスグッズ本数
            $table->unsignedInteger('house_goods_amount')->default(0); // ハウスグッズ金額

            // ■ ケア
            $table->unsignedInteger('care_count')->default(0);      // ケア件数
            $table->decimal('care_volume', 5, 1)->default(0); // ケア本数
            $table->unsignedInteger('care_amount')->default(0); // ケア金額

            // ■ テレ配
            $table->unsignedInteger('tele_distribution_count')->default(0);       // テレ配件数
            $table->decimal('tele_distribution_volume', 5, 1)->default(0); // テレ配本数

            // ■ テレ訪
            $table->unsignedInteger('tele_visit_count')->default(0);       // テレ訪件数
            $table->decimal('tele_visit_volume', 5, 1)->default(0); // テレ訪本数

            // ■ 追加金額
            $table->unsignedInteger('additional_amount')->default(0);       // 追加金額

            // ■ 清掃
            $table->unsignedInteger('cleaning_new_count')->default(0);       // 新規清掃件数
            $table->unsignedInteger('cleaning_continue_count')->default(0);       // 継続清掃訪件数

            // ■ チラシ
            $table->unsignedInteger('flyer_m_count')->default(0);       // チラシM
            $table->unsignedInteger('flyer_house_count')->default(0);       // チラシ戸建
            $table->unsignedInteger('flyer_shop_count')->default(0);       // チラシ店舗

            // ■ 枠付
            $table->unsignedInteger('frame_new_count')->default(0);       // 新規枠付

            // ■ AF販売パック
            $table->unsignedInteger('af_pack_count')->default(0); // AF販売パック数

//            $table->decimal('total_amount', 10, 2)->default(0);     // 金額合計

            $table->time('final_time')->nullable(); // 最終終了時間
            $table->string('final_location')->nullable(); // 最終現地

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_reports');
    }
};
