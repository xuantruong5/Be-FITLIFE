<?php

namespace App\Models;
use App\Models\Member;
use App\Models\Attendance;
use App\Models\OrderDetail;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleMember extends Model
{
    use HasFactory;
    protected $table = 'schedule_members';

    protected $fillable = [
        'checked_in_at',
        'trainer_note',
        'cancel_reason',
        'status',
        'id_schedule',
        'id_member',
        'id_package',
        'id_trainer_schedule',
        'id_order_detail',
    ];

    const SAP_TOI = 0;
    const CHECK_IN = 1;
    const HOAN_THANH = 2;
    const DA_HUY = 3;



    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member', 'id');
    }
    public function attendance()
    {
        return $this->hasOne(Attendance::class,'id_schedule_member','id');
    }
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'id_order_detail', 'id');
    }

    






    

    
}
