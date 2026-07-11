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
        'specialization',
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

    
}
