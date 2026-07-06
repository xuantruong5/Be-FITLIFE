<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Trainer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'trainers';

    const ACTIVE    = 1;
    const BLOCKED   = 0;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'avatar',
        'experience',
        'address',
        'is_active',
        'is_block',
        'hash_reset',
        'hash_active',
        'status',
        'id_branch',
    ];

    protected $hidden = [
        'password',
        'hash_reset',
        'hash_active',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'id_branch');
    }

    public function schedules()
    {
        return $this->hasMany(TrainerSchedule::class, 'id_trainer');
    }

    public function salaries()
    {
        return $this->hasMany(TrainerSalary::class, 'id_trainer');
    }

    public function notes()
    {
        return $this->hasMany(TrainerNote::class, 'id_trainer');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'id_trainer');
    }
}
