<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_details')->truncate();

        DB::table('order_details')->insert([
            [
                'id_don_hang' => 1,
                'id_member' => 1,

                'id_package' => 1,
                'id_trainer' => 1,
                'id_schedule' => 1,
                'id_branch' => 1,

                'package_name' => 'Gói PT 1 Tháng',
                'package_price' => 1500000,

                'trainer_name' => 'Nguyễn Văn A',
                'trainer_avatar' => 'trainer1.jpg',
                'trainer_experience' => '5 Years',
                'status' => 0,
                'branch_name' => 'FitLife Đà Nẵng',

                'schedule_title' => 'Buổi tập giảm cân',
                'schedule_date' => '2026-07-20',
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'duration' => 90,
                'room' => 'Room A',

                'subtotal' => 1500000,
                'discount' => 100000,
                'total_amount' => 1400000,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_don_hang' => 2,
                'id_member' => 2,
                'id_package' => 2,
                'id_trainer' => 2,
                'id_schedule' => 2,
                'id_branch' => 1,

                'package_name' => 'Gói PT 3 Tháng',
                'package_price' => 3900000,

                'trainer_name' => 'Trần Thị B',
                'trainer_avatar' => 'trainer2.jpg',
                'trainer_experience' => '3 Years',

                'branch_name' => 'FitLife Đà Nẵng',
                'status' => 2,
                'schedule_title' => 'Buổi tập tăng cơ',
                'schedule_date' => '2026-07-21',
                'start_time' => '18:00:00',
                'end_time' => '19:30:00',
                'duration' => 90,
                'room' => 'Room B',

                'subtotal' => 3900000,
                'discount' => 0,
                'total_amount' => 3900000,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_don_hang' => 3,
                'id_member' => 3,

                'id_package' => 3,
                'id_trainer' => 3,
                'id_schedule' => 3,
                'id_branch' => 2,

                'package_name' => 'Gói PT 6 Tháng',
                'package_price' => 6500000,

                'trainer_name' => 'Lê Văn C',
                'trainer_avatar' => 'trainer3.jpg',
                'trainer_experience' => '7 Years',

                'branch_name' => 'FitLife Hồ Chí Minh',
                'status' => 1,
                'schedule_title' => 'Buổi tập sức mạnh',
                'schedule_date' => '2026-07-22',
                'start_time' => '15:00:00',
                'end_time' => '16:30:00',
                'duration' => 90,
                'room' => 'Room C',

                'subtotal' => 6500000,
                'discount' => 500000,
                'total_amount' => 6000000,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
