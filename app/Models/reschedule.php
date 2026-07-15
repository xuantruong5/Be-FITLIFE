<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reschedule extends Model
{
    use HasFactory;

    protected $table = 'reschedules';
    protected $fillable = [
        'old_schedule_id', 
        'new_schedule_id',
        'date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'request_by',
        // 'id_schedule',
        'id_member',
        'id_trainer',
        'trainer_note',
        'approved_at',
    ];
    const DA_DUYET  = 0;
    const CHO_DUYET = 1;
    const TU_CHOI   = 2;
    const MEMBER = 0;
    const ADMIN = 1;
}
