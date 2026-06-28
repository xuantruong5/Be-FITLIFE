<?php

use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MembersController;

Route::get('/test', [TrainerController::class, 'test']);


Route::post('/login', [MembersController::class, 'login']);


Route::group(['prefix' => 'member','middleware' => 'memberMiddleware'], function () {
    Route::post('/register-schedule', [MembersController::class, 'registerSchedule']);
});



