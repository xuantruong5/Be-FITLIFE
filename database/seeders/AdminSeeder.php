<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branches')->truncate();
        $passwordHashed = bcrypt('123456');
        DB::table('admins')->insert([
            'name' => 'Administrator',
            'phone' => '0123456789',
            'email' => 'admin@gmail.com',
            'password' => $passwordHashed,
            'status' => 1,
            'avatar' => null,
            'date_of_birth' => '2000-01-01',
            'bio' => 'System Administrator',
            'last_login_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
