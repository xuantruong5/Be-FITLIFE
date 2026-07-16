<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageTrainer extends Model
{
    protected $table = 'package_trainers';

    protected $fillable = [
        'id_package',
        'id_trainer'
    ];
}
