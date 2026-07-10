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
        Schema::create('schedule_members', function (Blueprint $table) {
            $table->id();
            $table->timestamp('checked_in_at')->nullable();
            $table->text('trainer_note')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->integer('status')->default(0)->comment('0: Sắp tới, 1: Check-in, 2: Hoàn thành, 3: Đã hủy');
            $table->integer('id_schedule');
            $table->integer('id_member');
            $table->string('id_package');
            $table->integer('id_trainer_schedule');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_members');
    }
};
