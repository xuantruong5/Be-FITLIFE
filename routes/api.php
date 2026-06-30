<?php

use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\AdminController;





Route::post('/login', [MembersController::class, 'login']);
Route::post('/login-google', [MembersController::class, 'loginGoogle']);
Route::post('/register', [MembersController::class, 'register']);

// Member
Route::group(['prefix' => 'member','middleware' => 'memberMiddleware'], function () {
    Route::post('/register-schedule', [MembersController::class, 'registerSchedule']);
    Route::post('logout', [MembersController::class, 'logoutMember']);
    Route::post('logout-all', [MembersController::class, 'logoutAllMember']);
    
});






// Traners
Route::post('/trainer/login', [TrainerController::class, 'login']);
Route::group(['prefix' => 'trainer','middleware' => 'trainerMiddleware'], function () {
    Route::Post('logout', [TrainerController::class, 'logoutTrainer']);
    Route::post('logout-all', [TrainerController::class, 'logoutAllTrainer']);
});


// Admin 

Route::prefix('admin')->group(function () {
     // Public routes (không cần login)
    Route::post('/login', [AdminController::class, 'adminLogin']);


    // Protected routes (cần login)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AdminController::class, 'adminLogout']);
    });
});




