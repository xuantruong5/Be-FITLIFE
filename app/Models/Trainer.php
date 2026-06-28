<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trainer extends Model
{
    use HasFactory;

    protected $table = 'trainers';

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

}
