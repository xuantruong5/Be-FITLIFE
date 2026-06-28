<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('trainers')->truncate();
        DB::table('trainers')->insert([
            [
                'name' => 'Nguyễn Văn Hùng',
                'email' => 'hung.pt@gym.com',
                'password' => bcrypt('123456'),
                'phone' => '0901234567',
                'date_of_birth' => '1990-05-12',
                'gender' => 0,
                'avatar' => null,
                'experience' => '5 năm huấn luyện thể hình và giảm cân',
                'address' => 'Đà Nẵng',
                'is_active' => 1,
                'is_block' => 0,
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'id_branch' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Thị Mai',
                'email' => 'mai.pt@gym.com',
                'password' => bcrypt('123456'),
                'phone' => '0912345678',
                'date_of_birth' => '1995-09-20',
                'gender' => 1,
                'avatar' => null,
                'experience' => '3 năm PT chuyên giảm mỡ và yoga',
                'address' => 'Quảng Nam',
                'is_active' => 1,
                'is_block' => 0,
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'id_branch' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Hoàng Nam',
                'email' => 'nam.pt@gym.com',
                'password' => bcrypt('123456'),
                'phone' => '0923456789',
                'date_of_birth' => '1988-03-15',
                'gender' => 0,
                'avatar' => null,
                'experience' => 'Chuyên tăng cơ, luyện sức mạnh 7 năm kinh nghiệm',
                'address' => 'Huế',
                'is_active' => 1,
                'is_block' => 0,
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'id_branch' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phạm Thị Lan',
                'email' => 'lan.pt@gym.com',
                'password' => bcrypt('123456'),
                'phone' => '0934567890',
                'date_of_birth' => '1992-11-05',
                'gender' => 1,
                'avatar' => null,
                'experience' => 'PT giảm cân và fitness nữ',
                'address' => 'Đà Nẵng',
                'is_active' => 1,
                'is_block' => 0,
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'id_branch' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Đặng Quốc Bảo',
                'email' => 'bao.pt@gym.com',
                'password' => bcrypt('123456'),
                'phone' => '0945678901',
                'date_of_birth' => '1991-07-08',
                'gender' => 0,
                'avatar' => null,
                'experience' => 'Huấn luyện viên thể hình chuyên nghiệp 6 năm',
                'address' => 'Quảng Nam',
                'is_active' => 1,
                'is_block' => 0,
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'id_branch' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Võ Thị Hạnh',
                'email' => 'hanh.pt@gym.com',
                'password' => bcrypt('123456'),
                'phone' => '0956789012',
                'date_of_birth' => '1996-02-28',
                'gender' => 1,
                'avatar' => null,
                'experience' => 'PT yoga, pilates và phục hồi chức năng',
                'address' => 'Đà Nẵng',
                'is_active' => 1,
                'is_block' => 0,
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'id_branch' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
