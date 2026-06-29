<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, HasFactory, HasApiTokens;

    protected $table = 'admins';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'status',
        'avatar',
        'date_of_birth',
        'bio',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
}
