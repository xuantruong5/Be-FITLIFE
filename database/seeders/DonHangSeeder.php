<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('don_hangs')->truncate();
        DB::table('don_hangs')->insert([
            [
                'id_member' => 1,
                'id_promotion' => 1,

                'order_code' => 'DH202607140001',

                'subtotal' => 1500000,
                'discount' => 100000,
                'total_amount' => 1400000,

                'payment_method' => 'Momo',

                'is_thanh_toan' => 1,
                'status' => 1,

                'note' => 'Thanh toán thành công',

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_member' => 2,
                'id_promotion' => 2,

                'order_code' => 'DH202607140002',

                'subtotal' => 3900000,
                'discount' => 0,
                'total_amount' => 3900000,

                'payment_method' => 'VNPay',

                'is_thanh_toan' => 0,
                'status' => 0,

                'note' => 'Chờ thanh toán',

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_member' => 3,
                'id_promotion' => 3,

                'order_code' => 'DH202607140003',

                'subtotal' => 6000000,
                'discount' => 500000,
                'total_amount' => 5500000,

                'payment_method' => 'Tiền mặt',

                'is_thanh_toan' => 1,
                'status' => 1,

                'note' => 'Đã thanh toán tại quầy',

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
