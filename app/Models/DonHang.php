<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hangs';

    protected $fillable = [
        'id_member',
        'id_promotion',
        'subtotal',
        'discount',
        'total_amount',
        'is_thanh_toan',
        'status',
        'note',
        'order_code',
        'payment_method',
    ];
    const DA_THANH_TOAN = 1;
    const CHUA_THANH_TOAN = 0;


    
    const DA_HUY = 0;
    const DANG_THANH_TOAN = 1;
    const DA_THANH_TOAN_DON = 2;


    const LOAI_THANH_TOAN_KHI_GIAO_HANG = 'COD';
    const LOAI_THANH_TOAN_ONLINE = 'MB_BANK';
}
