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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Cơ Bản / Tiêu Chuẩn / Cao Cấp
            $table->string('slug')->unique(); // dùng dạng chữ basic/ standard/ permium 
            $table->unsignedInteger('price'); 
            $table->unsignedInteger('duration_days'); 
            $table->string('description')->nullable();
            $table->integer('status')->default(1)->comment('1: active, 0: dừng ');
            $table->boolean('is_popular')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
