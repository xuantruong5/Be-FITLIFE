<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedule_members')->truncate();

        DB::table('schedule_members')->insert([
            [
                'checked_in_at' => null,
                'trainer_note' => null,
                'cancel_reason' => null,
                'status' => 0, // Sắp tới
                'id_schedule' => 1,
                'id_order_detail' => 1,
                'id_member' => 1,
                'id_package' => 1,
                'id_trainer_schedule' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'checked_in_at' => Carbon::now(),
                'trainer_note' => 'Đã có mặt đúng giờ.',
                'cancel_reason' => null,
                'status' => 1, // Check-in
                'id_schedule' => 2,
                'id_member' => 2,
                'id_order_detail' => 2,
                'id_package' => 1,
                'id_trainer_schedule' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'checked_in_at' => Carbon::now()->subDays(1),
                'trainer_note' => 'Hoàn thành rất tốt.',
                'cancel_reason' => null,
                'status' => 2, // Hoàn thành
                'id_schedule' => 3,
                'id_order_detail' => 2,
                'id_member' => 3,
                'id_package' => 1,
                'id_trainer_schedule' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'checked_in_at' => null,
                'trainer_note' => null,
                'cancel_reason' => 'Bận việc gia đình.',
                'status' => 3, // Đã hủy
                'id_schedule' => 1,
                'id_member' => 2,
                'id_order_detail' => 3,
                'id_package' => 1,
                'id_trainer_schedule' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
