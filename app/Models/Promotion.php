<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'max_discount',
        'quantity',
        'used_quantity',
        'start_at',
        'end_at',
        'status',
    ];

    const NGUNG  = 0;
    const CON_HOAT_DONG = 1;
}
