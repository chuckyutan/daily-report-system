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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      // 誰が入力したか
            $table->unsignedBigInteger('vehicle_id')->nullable(); // 担当車両(バイク等)
            $table->date('report_date');                // 報告日
            $table->string('start_location')->nullable();// 開始現地
            $table->time('start_time')->nullable();     // 出発 or 勤務開始時間
            $table->integer('planned_visits')->default(0); // 訪問予定件数
            $table->timestamps();

            // FK
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
