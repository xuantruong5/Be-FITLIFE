<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('members')->truncate();
         DB::table('members')->insert([
            [
                'name' => 'Nguyễn Văn An',
                'email' => 'an@gmail.com',
                'password' => bcrypt('123456'),
                'avatar' => null,
                'phone' => '0901234567',
                'date_of_birth' => '2000-05-10',
                'gender' => 0,
                'address' => 'Quảng Nam',
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trần Thị Bích',
                'email' => 'bich@gmail.com',
                'password' => bcrypt('123456'),
                'avatar' => null,
                'phone' => '0912345678',
                'date_of_birth' => '1998-09-21',
                'gender' => 1,
                'address' => 'Đà Nẵng',
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lê Hoàng Nam',
                'email' => 'nam@gmail.com',
                'password' => bcrypt('123456'),
                'avatar' => null,
                'phone' => '0933456789',
                'date_of_birth' => '1995-12-01',
                'gender' => 0,
                'address' => 'Huế',
                'hash_reset' => null,
                'hash_active' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
