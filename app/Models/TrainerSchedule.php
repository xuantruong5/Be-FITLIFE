<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSchedule extends Model
{
    use HasFactory;

    protected $table = 'trainer__schedules';

    protected $fillable = [
        'title',
        'date',
        'start_time',
        'end_time',
        'room',
        'id_package',
        'max_members',
        'current_members',
        'approval_status',
        'status',
        'id_branch',
        'id_trainer',
        'admin_note',
        'note',
    ];
    const CHUA_DUYET = 0;
    const DA_DUYET  = 1;
    const TU_CHOI = 2;

    const SAP_DIEN_RA = 0;
    const DANG_HOAT_DONG = 1;
    const DA_HOAN_THANH = 2;
    const DA_HUY = 3;
}
