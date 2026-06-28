<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class packages extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'duration_days',
        'description',
        'status',
        'is_popular',
    ];

    const  HOAT_DONG = 1;
    const NGUNG_HOAT_DONG = 0;
    
}
