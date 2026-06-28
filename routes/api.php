<?php

use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MembersController;

Route::get('/test', [TrainerController::class, 'test']);
Route::post('/member/register-schedule', [MembersController::class, 'registerSchedule']);

