<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branches')->truncate();
        DB::table('branches')->insert([
            [
                'name' => 'Gym Đà Nẵng Center',
                'address' => '123 Nguyễn Văn Linh, Đà Nẵng',
                'phone' => '0236 123 456',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gym Quảng Nam Fitness',
                'address' => '45 Trần Hưng Đạo, Tam Kỳ, Quảng Nam',
                'phone' => '0235 234 567',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gym Huế Pro Fitness',
                'address' => '78 Lê Lợi, TP Huế',
                'phone' => '0234 345 678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gym Hải Châu Branch',
                'address' => '10 Hải Châu, Đà Nẵng',
                'phone' => '0236 987 654',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gym Hội An Fitness Studio',
                'address' => '22 Nguyễn Thái Học, Hội An, Quảng Nam',
                'phone' => '0235 888 999',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
