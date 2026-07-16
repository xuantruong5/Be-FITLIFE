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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('id_don_hang');
            $table->integer('id_member');
            // Thông tin liên kết
            $table->integer('id_package');
            $table->integer('id_trainer');
            $table->integer('id_schedule');
            $table->integer('id_branch');
            $table->string('package_name');
            $table->decimal('package_price', 12, 2);
            $table->string('trainer_name');
            $table->string('trainer_avatar')->nullable();
            $table->string('trainer_experience')->nullable();
            $table->string('branch_name');
            $table->string('schedule_title');
            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration');
            $table->string('room')->nullable();
            $table->decimal('subtotal', 12, 2);      // Giá gói
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->integer('status')->default(0)->comment('0: Chờ duyệt, 1: Đã duyệt, 2: Từ chối, 3: Đã hủy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
