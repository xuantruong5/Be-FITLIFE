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
        Schema::create('member_packages', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('total_sessions');// số buổi đã tập 
            $table->unsignedInteger('used_sessions')->default(0); // điểm danh số buổi đã tập để tính total
            $table->unsignedInteger('pt_sessions')->default(0); // Số buổi PT mỗi tháng
            $table->integer('status')->default(0)->comment('0: Hết hạn, 1: Đang hoạt động, 2: Chưa kích hoạt, 3: Đã hủy');
            $table->integer('id_trainer');
            $table->integer('id_member');
            $table->integer('id_package');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_packages');
    }
};
