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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('gender')->default(0)->comment('0: Nam, 1: Nu, 2: Khác');
            $table->string('avatar')->nullable();
            $table->string('experience')->nullable();
            $table->string('address')->nullable();
            $table->integer('is_active')->default(0); // 1: hoạt động, 0: chưa kích hoạt email
            $table->integer('is_block')->default(0);  // 1: bị khóa, 0: bình thường
            $table->string('hash_reset')->nullable(); // quên mật khẩu 
            $table->string('hash_active')->nullable();// kích hoạt
            $table->integer('status')->default(1);
            $table->integer('id_branch');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
