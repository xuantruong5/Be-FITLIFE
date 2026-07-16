<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedule_changes')->truncate();

        DB::table('schedule_changes')->insert([

            // Member 1 xin đổi lịch từ buổi 1 sang buổi 2
            [
                'id_schedule_member' => 1,
                'id_old_schedule' => 1,
                'id_new_schedule' => 2,
                'reason' => 'Có lịch họp công ty.',
                'status' => 2, // Chờ duyệt
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Member 2 được duyệt đổi lịch
            [
                'id_schedule_member' => 2,
                'id_old_schedule' => 2,
                'id_new_schedule' => 3,
                'reason' => 'Muốn đổi sang buổi chiều.',
                'status' => 1, // Đã duyệt
                'note' => 'Admin đã duyệt.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Member 3 bị từ chối
            [
                'id_schedule_member' => 3,
                'id_old_schedule' => 3,
                'id_new_schedule' => 1,
                'reason' => 'Bận việc cá nhân.',
                'status' => 0, // Từ chối (theo comment hiện tại của bạn)
                'note' => 'Buổi học đã đủ số lượng.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
