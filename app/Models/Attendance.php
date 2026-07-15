<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

   

    protected $fillable = [
        'status',
        'check_in_time',
        'check_out_time',
        'id_trainer',
        'id_schedule',
        'id_member',
        'id_schedule_member',
    ];
    const CHUA_DIEM_DANH = 0;
    const CO_MAT = 1;
    const VANG = 2;
    const DI_TRE = 3;
}
