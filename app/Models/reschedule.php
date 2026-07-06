<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reschedule extends Model
{
    use HasFactory;

    protected $table = 'reschedules';

    const DA_DUYET  = 0;
    const CHO_DUYET = 1;
    const TU_CHOI   = 2;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'id_schedule',
        'id_member',
        'id_trainer',
        'trainer_note',
        'approved_at',
    ];

    // Relations
    public function schedule()
    {
        return $this->belongsTo(TrainerSchedule::class, 'id_schedule');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'id_trainer');
    }
}
