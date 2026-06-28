<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrainerScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('trainer__schedules')->truncate();
        DB::table('trainer__schedules')->insert([
            [
                'title' => 'PT Gym Beginner Class',
                'date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'room' => 'Room A',
                'max_members' => 5,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 1,
                'id_trainer' => 1,
                'admin_note' => null,
                'note' => 'Mang theo khăn và nước uống',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Strength Training Advanced',
                'date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'room' => 'Room B',
                'max_members' => 4,
                'approval_status' => 0,
                'status' => 0,
                'id_branch' => 1,
                'id_trainer' => 2,
                'admin_note' => 'Chờ admin duyệt phòng',
                'note' => 'Không tập khi chưa khởi động',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'HIIT Fat Burn',
                'date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'start_time' => '17:00:00',
                'end_time' => '18:00:00',
                'room' => 'Room C',
                'max_members' => 8,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 2,
                'id_trainer' => 3,
                'admin_note' => null,
                'note' => 'Cardio cường độ cao',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
