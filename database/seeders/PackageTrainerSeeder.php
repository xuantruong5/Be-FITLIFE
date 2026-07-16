<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageTrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('package_trainers')->truncate();

        DB::table('package_trainers')->insert([

            // Gói Cơ bản
            [
                'id_package' => 1,
                'id_trainer' => 1,
            ],
            [
                'id_package' => 1,
                'id_trainer' => 2,
            ],
            [
                'id_package' => 1,
                'id_trainer' => 4,
            ],

            // Gói Tiêu chuẩn
            [
                'id_package' => 2,
                'id_trainer' => 2,
            ],
            [
                'id_package' => 2,
                'id_trainer' => 3,
            ],
            [
                'id_package' => 2,
                'id_trainer' => 5,
            ],

            // Gói Cao cấp
            [
                'id_package' => 3,
                'id_trainer' => 1,
            ],
            [
                'id_package' => 3,
                'id_trainer' => 3,
            ],
            [
                'id_package' => 3,
                'id_trainer' => 5,
            ],
            [
                'id_package' => 3,
                'id_trainer' => 6,
            ],

        ]);
    }
}
