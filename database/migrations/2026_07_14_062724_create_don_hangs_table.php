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
        Schema::create('don_hangs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_member');
            $table->integer('id_promotion')->nullable();;
            $table->decimal('subtotal', 12, 2)->default(0);     // Tổng tiền trước giảm
            $table->decimal('total_amount', 12, 2);
            $table->integer('discount')->default(0)->comment('Discount amount');
            $table->integer('is_thanh_toan')->default(0)->comment('Check thanh toán');
            $table->integer('status')->default(0)->comment('Trạng thái đơn hàng');
            $table->longText('note')->nullable()->comment('Ghi chú');
            $table->string('order_code')->unique()->comment('Mã đơn hàng');
            $table->string('payment_method')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hangs');
    }
};
