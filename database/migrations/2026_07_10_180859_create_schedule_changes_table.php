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
        Schema::create('schedule_changes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_schedule_member');

            $table->unsignedBigInteger('id_old_schedule');

            // Buổi học muốn đổi sang
            $table->unsignedBigInteger('id_new_schedule');
            $table->text('reason')->nullable();
            $table->integer('status')->default(0)->comment('0:  hủy, 1: đã duyệt, 2: Chờ Duyệt');
            $table->text('note')->nullable(); // note của admin/hlv
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_changes');
    }
};
