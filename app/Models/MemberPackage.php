<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberPackage extends Model
{
    use HasFactory;
    protected $table = 'member_packages';

    protected $fillable = [
        'price',
        'start_date',
        'end_date',
        'total_sessions',
        'used_sessions',
        'pt_sessions',
        'status',
        'id_trainer',
        'id_member',
        'id_package',
    ];
    const  HET_HAN = 0;
    const  HOAT_DONG = 1;
    const  CHUA_DUYET = 2;
    const  DA_HUY = 3;
}
