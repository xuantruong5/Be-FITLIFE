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
        Schema::create('trainer_salaries', function (Blueprint $table) {
            $table->id();
            $table->integer('id_trainer');
            $table->unsignedTinyInteger('month')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->decimal('base_salary')->default(0);
            $table->decimal('bonus')->default(0);
            $table->decimal('deduction')->default(0);
            $table->decimal('total_salary')->default(0);
            $table->integer('status')->default(0)->comment('0: pending, 1: paid');
            $table->timestamp('paid_at')->nullable(); //Thời gian đã trả lương
            $table->timestamp('calculated_at')->nullable();// hệ thống tính lương bao giờ
            $table->text('note')->nullable(); // bị trừ tiền lý do gì hoặc khen 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_salaries');
    }
};
