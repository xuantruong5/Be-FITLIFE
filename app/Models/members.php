<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class members extends Model
{
    use HasFacTory;
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

}
