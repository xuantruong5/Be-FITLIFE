<?php

namespace App\Models;

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




    

    
}
