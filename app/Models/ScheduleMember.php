<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleMember extends Model
{
    use HasFactory;

    protected $table = 'schedule_members';

    protected $fillable = [
        'id_schedule',
        'id_member',
        'id_package',
        'id_trainer',
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

    public function package()
    {
        return $this->belongsTo(Package::class, 'id_package');
    }
}
