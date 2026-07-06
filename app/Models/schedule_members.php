<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedule_members extends Model
{
    use HasFactory;

    protected $table = 'schedule_members';

    protected $fillable = [
        'id_schedule',
        'id_member',
        'id_package',
        'id_trainer'
    ];
}
