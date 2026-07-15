<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       $this->call([
            BranchSeeder::class,
            TrainerSeeder::class,
            TrainerScheduleSeeder::class,
            MemberSeeder::class,
            PackageSeeder::class,
            // MemberPackageSeeder::class,
            TrainerNoteSeeder::class,
            ScheduleChangeSeeder::class,
            // ScheduleMemberSeeder::class,
            PackageTrainerSeeder::class,
            DonHangSeeder::class,
            PromotionSeeder::class,
            OrderDetailSeeder::class,
       ]);
    }
}
