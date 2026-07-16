<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DonHang;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'id_don_hang',
        'id_package',
        'id_trainer',
        'id_schedule',
        'id_member',
        'id_branch',
        'package_name',
        'package_price',
        'trainer_name',
        'trainer_avatar',
        'trainer_experience',
        'branch_name',
        'schedule_title',
        'schedule_date',
        'start_time',
        'end_time',
        'duration',
        'room',
        'subtotal',
        'discount',
        'total_amount',
        'status',
    ];

    const CHO_DUYET = 0;
    const DA_DUYET  = 1;
    const TU_CHOI   = 2;
    const DA_HUY    = 3;

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'id_don_hang', 'id');
    }

}
