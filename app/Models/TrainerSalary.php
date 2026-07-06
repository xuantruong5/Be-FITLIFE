<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSalary extends Model
{
    use HasFactory;

    protected $table = 'trainer_salaries';

    const PENDING   = 0;
    const PAID      = 1;

    protected $fillable = [
        'id_trainer',
        'month',
        'year',
        'base_salary',
        'bonus',
        'deduction',
        'total_salary',
        'status',
        'paid_at',
        'calculated_at',
        'note',
    ];

    // Relations
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'id_trainer');
    }
}
