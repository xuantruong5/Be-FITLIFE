<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    const CO_MAT    = 0;
    const VANG      = 1;
    const DI_TRE    = 2;

    protected $fillable = [
        'status',
        'check_in_time',
        'check_out_time',
        'id_trainer',
        'id_schedule',
        'id_member',
    ];

    // Relations
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'id_trainer');
    }

    public function schedule()
    {
        return $this->belongsTo(TrainerSchedule::class, 'id_schedule');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }
}
