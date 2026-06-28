<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('packages')->truncate();
        DB::table('packages')->insert([
            [
                'name' => 'Cơ Bản',
                'slug' => 'basic',
                'price' => 500000,
                'duration_days' => 30,
                'description' => 'Gói tập cơ bản dành cho người mới bắt đầu',
                'status' => 1,
                'is_popular' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tiêu Chuẩn',
                'slug' => 'standard',
                'price' => 900000,
                'duration_days' => 90,
                'description' => 'Gói phổ biến, phù hợp đa số hội viên',
                'status' => 1,
                'is_popular' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cao Cấp',
                'slug' => 'premium',
                'price' => 1500000,
                'duration_days' => 365,
                'description' => 'Gói cao cấp đầy đủ dịch vụ huấn luyện',
                'status' => 1,
                'is_popular' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
