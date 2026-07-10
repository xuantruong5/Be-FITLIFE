<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('trainer_notes')->truncate();

        DB::table('trainer_notes')->insert([
            [
                'title' => 'Điều chỉnh tư thế Squat',
                'category' => 'Kỹ thuật',
                'priority' => 'high',
                'is_sent' => 1,
                'weight' => 72.5,
                'body_fat' => 18.2,
                'muscle' => 34.6,
                'calories' => 520,
                'status' => 1,
                'note' => 'Giữ lưng thẳng, đầu gối không vượt quá mũi chân. Tăng tạ sau 2 tuần nếu kỹ thuật ổn định.',
                'id_trainer' => 1,
                'id_schedule' => 1,
                'id_member' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Chế độ ăn tuần này',
                'category' => 'Dinh dưỡng',
                'priority' => 'normal',
                'is_sent' => 1,
                'weight' => 72.0,
                'body_fat' => 18.0,
                'muscle' => 34.8,
                'calories' => 480,
                'status' => 1,
                'note' => 'Ăn 150g protein/ngày, hạn chế đồ ngọt và nước có gas.',
                'id_trainer' => 1,
                'id_schedule' => 2,
                'id_member' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Phục hồi sau buổi tập',
                'category' => 'Phục hồi',
                'priority' => 'normal',
                'is_sent' => 1,
                'weight' => 68.5,
                'body_fat' => 20.1,
                'muscle' => 30.4,
                'calories' => 350,
                'status' => 1,
                'note' => 'Foam Rolling 15 phút và ngủ đủ 8 tiếng mỗi ngày.',
                'id_trainer' => 2,
                'id_schedule' => 3,
                'id_member' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Mục tiêu tháng này',
                'category' => 'Mục tiêu',
                'priority' => 'urgent',
                'is_sent' => 0,
                'weight' => 68.0,
                'body_fat' => 19.8,
                'muscle' => 30.8,
                'calories' => 600,
                'status' => 1,
                'note' => 'Giảm 2kg mỡ và tăng 1kg cơ trong tháng này.',
                'id_trainer' => 2,
                'id_schedule' => 4,
                'id_member' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cải thiện Deadlift',
                'category' => 'Kỹ thuật',
                'priority' => 'high',
                'is_sent' => 1,
                'weight' => 75.3,
                'body_fat' => 16.5,
                'muscle' => 37.1,
                'calories' => 650,
                'status' => 1,
                'note' => 'Giữ thanh tạ sát chân và siết cơ bụng khi nâng.',
                'id_trainer' => 3,
                'id_schedule' => 5,
                'id_member' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Tăng cơ hiệu quả',
                'category' => 'Dinh dưỡng',
                'priority' => 'normal',
                'is_sent' => 0,
                'weight' => 75.0,
                'body_fat' => 16.2,
                'muscle' => 37.5,
                'calories' => 700,
                'status' => 1,
                'note' => 'Bổ sung Whey Protein sau tập và chia thành 5 bữa/ngày.',
                'id_trainer' => 3,
                'id_schedule' => 6,
                'id_member' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
