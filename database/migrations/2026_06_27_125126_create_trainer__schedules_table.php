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
        Schema::create('trainer__schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room');
            $table->string('max_members')->nullable();
            $table->integer('approval_status')->default(0)->comment('0: Chờ duyệt, 1: Đã duyệt, 2: Từ chối');
            $table->integer('status')->default(0)->comment('0: Sắp diễn ra, 1: Đang diễn ra, 2: Đã hoàn thành, 3: Đã hủy');
            $table->integer('id_branch');
            $table->integer('id_trainer');
            $table->string('admin_note')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer__schedules');
    }
};
