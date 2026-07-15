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
        Schema::create('reschedules', function (Blueprint $table) {
            $table->id();
                    // lịch hiện tại
            $table->integer('old_schedule_id');

            // lịch mới
            $table->integer('new_schedule_id')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');


            $table->string('reason');
            $table->integer('status')->default(1)->comment('0:Đã duyệt  , 1: Chờ duyệt, 2:Từ chối ');

            // người tạo yêu cầu
            // 0: Member, 1: Admin
            $table->integer('request_by')->default(0);



            // $table->integer('id_schedule');
            $table->integer('id_member')->nullable();
            $table->integer('id_trainer')->nullable();
            $table->string('trainer_note')->nullable();



            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable(); // Thời gian duyệt yêu cầu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reschedules');
    }
};
