<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    const HOAT_DONG = 1;
    const DUNG      = 0;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'duration_days',
        'description',
        'status',
        'is_popular',
    ];

    public function scheduleMembers()
    {
        return $this->hasMany(ScheduleMember::class, 'id_package');
    }
}
