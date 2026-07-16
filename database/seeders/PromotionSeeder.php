<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Carbon\Carbon;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::truncate();

        Promotion::insert([
            [
                'code' => 'FIT10',
                'description' => 'Giảm 10% cho tất cả các gói tập',
                'type' => 0,
                'value' => 10,
                'max_discount' => 200000,
                'quantity' => 100,
                'used_quantity' => 0,
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addMonths(1),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WELCOME50',
                'description' => 'Giảm trực tiếp 50.000đ',
                'type' => 1,
                'value' => 50000,
                'max_discount' => null,
                'quantity' => 200,
                'used_quantity' => 0,
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addMonths(2),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'VIP20',
                'description' => 'Giảm 20% tối đa 500.000đ',
                'type' => 0,
                'value' => 20,
                'max_discount' => 500000,
                'quantity' => 50,
                'used_quantity' => 0,
                'start_at' => Carbon::now(),
                'end_at' => Carbon::now()->addDays(15),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
