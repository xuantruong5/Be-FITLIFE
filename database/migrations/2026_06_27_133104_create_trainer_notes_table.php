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
        Schema::create('trainer_notes', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('category')->nullable(); // dinh dưỡng, sức khỏe .......
            $table->string('priority')->default('normal'); /// đánh giá low, normal, high, urgent
            $table->tinyInteger('is_sent')->default(0)->comment('0: Chưa gửi, 1: Đã gửi'); /// gửi đến hội viên 
            $table->decimal('weight')->nullable();      // kg
            $table->decimal('body_fat')->nullable();    // % tỷ lệ mỡ cơ thể 
            $table->decimal('muscle')->nullable();      // kg hoặc % Khối lượng cơ 
            $table->integer('calories')->nullable();          // Lượng calo tiêu hao 
            $table->string('status')->default(0)->comment(' 1: Đang hoạt động,  0: Đã hủy');
            $table->text('note');
            $table->integer('id_trainer');
            $table->integer('id_schedule');
            $table->integer('id_member');
            $table->timestamps();


            
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_notes');
    }
};
