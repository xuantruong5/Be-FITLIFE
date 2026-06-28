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
    const ACTIVE    = 1;
    const BLOCKED   = 0;



}
