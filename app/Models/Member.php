<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'members';

    const ACTIVE    = 1;
    const BLOCKED   = 0;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'hash_reset',
        'hash_active',
        'status',
    ];

    protected $hidden = [
        'password',
        'hash_reset',
        'hash_active',
    ];

    // Relations
    public function scheduleMembers()
    {
        return $this->hasMany(ScheduleMember::class, 'id_member');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'id_member');
    }

    public function reschedules()
    {
        return $this->hasMany(Reschedule::class, 'id_member');
    }

    public function trainerNotes()
    {
        return $this->hasMany(TrainerNote::class, 'id_member');
    }
}
