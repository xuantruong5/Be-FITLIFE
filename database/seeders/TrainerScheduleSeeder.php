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
                'date' => Carbon::now()->addDay()->toDateString(),
                'start_time' => '08:00:00',
                'end_time' => '09:30:00',
                'room' => 'Room A',
                'id_package' => 1,
                'max_members' => 5,
                'current_members' => 3,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 1,
                'id_trainer' => 1,
                'admin_note' => null,
                'note' => 'Buổi tập dành cho người mới bắt đầu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Strength Training',
                'date' => Carbon::now()->addDays(2)->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '11:30:00',
                'room' => 'Room B',
                'id_package' => 1,
                'max_members' => 4,
                'current_members' => 2,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 1,
                'id_trainer' => 2,
                'admin_note' => null,
                'note' => 'Buổi tập sức mạnh nâng cao.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'HIIT Fat Burn',
                'date' => Carbon::now()->addDays(3)->toDateString(),
                'start_time' => '17:00:00',
                'end_time' => '18:00:00',
                'room' => 'Room C',
                'id_package' => 2,
                'max_members' => 8,
                'current_members' => 6,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 2,
                'id_trainer' => 3,
                'admin_note' => null,
                'note' => 'Cardio cường độ cao.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Fitness For Women',
                'date' => Carbon::now()->addDays(4)->toDateString(),
                'start_time' => '14:00:00',
                'end_time' => '15:30:00',
                'room' => 'Room D',
                'id_package' => 2,
                'max_members' => 6,
                'current_members' => 5,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 2,
                'id_trainer' => 4,
                'admin_note' => null,
                'note' => 'Tập luyện dành cho nữ.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bodybuilding Pro',
                'date' => Carbon::now()->addDays(5)->toDateString(),
                'start_time' => '16:00:00',
                'end_time' => '17:30:00',
                'room' => 'Room E',
                'id_package' => 3,
                'max_members' => 5,
                'current_members' => 4,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 1,
                'id_trainer' => 5,
                'admin_note' => null,
                'note' => 'Tăng cơ chuyên sâu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Yoga & Pilates',
                'date' => Carbon::now()->addDays(6)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '19:30:00',
                'room' => 'Room F',
                'id_package' => 3,
                'max_members' => 10,
                'current_members' => 8,
                'approval_status' => 1,
                'status' => 0,
                'id_branch' => 3,
                'id_trainer' => 6,
                'admin_note' => null,
                'note' => 'Yoga kết hợp Pilates giúp tăng sự dẻo dai.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
