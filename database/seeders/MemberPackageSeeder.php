<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MemberPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('member_packages')->truncate();

        DB::table('member_packages')->insert([
            [
                'price' => 500000,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'total_sessions' => 12,
                'used_sessions' => 4,
                'pt_sessions' => 2,
                'status' => 1,
                'id_trainer' => 1,
                'id_member' => 1,
                'id_package' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 900000,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'total_sessions' => 24,
                'used_sessions' => 10,
                'pt_sessions' => 4,
                'status' => 1,
                'id_trainer' => 2,
                'id_member' => 2,
                'id_package' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 1500000,
                'start_date' => Carbon::now()->subDays(100),
                'end_date' => Carbon::now()->addDays(265),
                'total_sessions' => 48,
                'used_sessions' => 20,
                'pt_sessions' => 8,
                'status' => 1,
                'id_trainer' => 3,
                'id_member' => 3,
                'id_package' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 900000,
                'start_date' => Carbon::now()->subDays(100),
                'end_date' => Carbon::now()->subDays(10),
                'total_sessions' => 24,
                'used_sessions' => 24,
                'pt_sessions' => 4,
                'status' => 0, // Hết hạn
                'id_trainer' => 4,
                'id_member' => 1,
                'id_package' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'price' => 1500000,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(370),
                'total_sessions' => 60,
                'used_sessions' => 0,
                'pt_sessions' => 8,
                'status' => 2, // Chưa kích hoạt
                'id_trainer' => 5,
                'id_member' => 2,
                'id_package' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
