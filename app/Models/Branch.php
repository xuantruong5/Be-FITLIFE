<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    // Relations
    public function trainers()
    {
        return $this->hasMany(Trainer::class, 'id_branch');
    }

    public function schedules()
    {
        return $this->hasMany(TrainerSchedule::class, 'id_branch');
    }
}
