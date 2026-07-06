<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSchedule extends Model
{
    use HasFactory;

    protected $table = 'trainer__schedules';

    // approval_status
    const CHO_DUYET     = 0;
    const DA_DUYET      = 1;
    const TU_CHOI       = 2;

    // status
    const SAP_DIEN_RA       = 0;
    const DANG_DIEN_RA      = 1;
    const DA_HOAN_THANH     = 2;
    const DA_HUY            = 3;

    protected $fillable = [
        'title',
        'date',
        'start_time',
        'end_time',
        'room',
        'max_members',
        'approval_status',
        'status',
        'id_branch',
        'id_trainer',
        'admin_note',
        'note',
    ];

    // Relations
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'id_trainer');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'id_branch');
    }

    public function scheduleMembers()
    {
        return $this->hasMany(ScheduleMember::class, 'id_schedule');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'id_schedule');
    }

    public function reschedules()
    {
        return $this->hasMany(Reschedule::class, 'id_schedule');
    }

    public function trainerNotes()
    {
        return $this->hasMany(TrainerNote::class, 'id_schedule');
    }
}
