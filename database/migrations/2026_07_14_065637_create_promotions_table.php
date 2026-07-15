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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); //('Mã khuyến mãi');
            $table->text('description')->nullable(); //('Mô tả chương trình khuyến mãi');

            $table->integer('type')->default(0)->comment('0: Giảm theo phần trăm, 1: Giảm theo số tiền');
            $table->integer('value')->default(0)->comment('Giá trị khuyến mãi');

            $table->integer('max_discount')->nullable()->comment('Số tiền giảm tối đa');

            $table->integer('quantity')->default(0)->comment('Số lượng mã khuyến mãi');
            $table->integer('used_quantity')->default(0)->comment('Số lượng đã sử dụng');

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->integer('status')->default(1); // 1: active, 0: inactive

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
