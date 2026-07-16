<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerNote extends Model
{
    use HasFactory;
    protected $table = 'trainer_notes';
    protected $fillable = [
        'title',
        'category',
        'priority',
        'is_sent',
        'weight',
        'body_fat',
        'muscle',
        'calories',
        'status',
        'note',
        'id_trainer',
        'id_schedule',
        'id_member',
    ];
    // use HasFactory;

    // protected $table = 'trainer_notes';

    // const PRIORITY_LOW      = 'low';
    // const PRIORITY_NORMAL   = 'normal';
    // const PRIORITY_HIGH     = 'high';
    // const PRIORITY_URGENT   = 'urgent';

    // protected $fillable = [
    //     'title',
    //     'type',
    //     'priority',
    //     'content',
    //     'weight',
    //     'body_fat',
    //     'muscle',
    //     'calories',
    //     'status',
    //     'id_trainer',
    //     'id_schedule',
    //     'id_member',
    // ];

    // // Relations
    // public function trainer()
    // {
    //     return $this->belongsTo(Trainer::class, 'id_trainer');
    // }

    // public function schedule()
    // {
    //     return $this->belongsTo(TrainerSchedule::class, 'id_schedule');
    // }

    // public function member()
    // {
    //     return $this->belongsTo(Member::class, 'id_member');
    // }
}
