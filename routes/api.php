<?php

use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TrainerScheduleController;
use App\Http\Controllers\ScheduleMemberController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RescheduleController;
use App\Http\Controllers\TrainerNoteController;
use App\Http\Controllers\TrainerSalaryController;

// ============================================================
// PUBLIC ROUTES (Không cần đăng nhập)
// ============================================================

// Auth - Member
Route::post('/login',         [MembersController::class, 'login']);
Route::post('/login-google',  [MembersController::class, 'loginGoogle']);
Route::post('/register',      [MembersController::class, 'register']);

// Auth - Trainer
Route::post('/trainer/login', [TrainerController::class, 'login']);

// Auth - Admin
Route::post('/admin/login',   [AdminController::class, 'adminLogin']);

// Public - Packages (hội viên xem gói tập)
Route::get('/packages',       [PackageController::class, 'index']);

// ============================================================
// MEMBER ROUTES (Cần đăng nhập với token member)
// ============================================================
Route::prefix('member')->middleware('memberMiddleware')->group(function () {
    // Auth
    Route::post('/logout',       [MembersController::class, 'logoutMember']);
    Route::post('/logout-all',   [MembersController::class, 'logoutAllMember']);

    // Profile
    Route::get('/profile',       [MembersController::class, 'getMember']);
    Route::post('/change-profile',[MembersController::class, 'changProfile']);

    // Đăng ký lịch tập
    Route::post('/register-schedule', [MembersController::class, 'registerSchedule']);

    // Xem lịch đã đăng ký
    Route::get('/my-schedules',  [ScheduleMemberController::class, 'mySchedules']);
    Route::delete('/schedule-members/{id}', [ScheduleMemberController::class, 'destroy']);

    // Lịch sử điểm danh
    Route::get('/my-attendances', [AttendanceController::class, 'myAttendances']);

    // Yêu cầu đổi lịch
    Route::get('/reschedules',    [RescheduleController::class, 'myReschedules']);
    Route::post('/reschedules',   [RescheduleController::class, 'store']);

    // Ghi chú sức khỏe từ HLV
    Route::get('/my-notes',       [TrainerNoteController::class, 'myNotes']);
});

// ============================================================
// TRAINER ROUTES (Cần đăng nhập với token trainer)
// ============================================================
Route::prefix('trainer')->middleware('trainerMiddleware')->group(function () {
    // Auth
    Route::post('/logout',       [TrainerController::class, 'logoutTrainer']);
    Route::post('/logout-all',   [TrainerController::class, 'logoutAllTrainer']);

    // Lịch tập của HLV
    Route::get('/schedules',          [TrainerScheduleController::class, 'index']);
    Route::post('/schedules',         [TrainerScheduleController::class, 'store']);
    Route::get('/schedules/{id}',     [TrainerScheduleController::class, 'show']);
    Route::put('/schedules/{id}',     [TrainerScheduleController::class, 'update']);
    Route::delete('/schedules/{id}',  [TrainerScheduleController::class, 'destroy']);

    // Danh sách hội viên trong ca học
    Route::get('/schedule-members',   [ScheduleMemberController::class, 'index']);

    // Điểm danh
    Route::get('/attendances',        [AttendanceController::class, 'index']);
    Route::post('/attendances',       [AttendanceController::class, 'store']);
    Route::get('/attendances/{id}',   [AttendanceController::class, 'show']);
    Route::put('/attendances/{id}',   [AttendanceController::class, 'update']);

    // Yêu cầu đổi lịch (từ hội viên)
    Route::get('/reschedules',                  [RescheduleController::class, 'index']);
    Route::get('/reschedules/{id}',             [RescheduleController::class, 'show']);
    Route::post('/reschedules/{id}/approve',    [RescheduleController::class, 'approve']);
    Route::post('/reschedules/{id}/reject',     [RescheduleController::class, 'reject']);

    // Ghi chú hội viên
    Route::get('/notes',              [TrainerNoteController::class, 'index']);
    Route::post('/notes',             [TrainerNoteController::class, 'store']);
    Route::get('/notes/{id}',         [TrainerNoteController::class, 'show']);
    Route::put('/notes/{id}',         [TrainerNoteController::class, 'update']);
    Route::delete('/notes/{id}',      [TrainerNoteController::class, 'destroy']);

    // Xem lương
    Route::get('/my-salary',          [TrainerSalaryController::class, 'mySalary']);
});

// ============================================================
// ADMIN ROUTES (Cần đăng nhập với token admin)
// ============================================================
Route::prefix('admin')->group(function () {
    // Auth (public)
    Route::post('/login',  [AdminController::class, 'adminLogin']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AdminController::class, 'adminLogout']);

        // Chi nhánh
        Route::get('/branches',       [BranchController::class, 'index']);
        Route::post('/branches',      [BranchController::class, 'store']);
        Route::get('/branches/{id}',  [BranchController::class, 'show']);
        Route::put('/branches/{id}',  [BranchController::class, 'update']);
        Route::delete('/branches/{id}',[BranchController::class, 'destroy']);

        // Gói tập
        Route::get('/packages',       [PackageController::class, 'indexAdmin']);
        Route::post('/packages',      [PackageController::class, 'store']);
        Route::get('/packages/{id}',  [PackageController::class, 'show']);
        Route::put('/packages/{id}',  [PackageController::class, 'update']);
        Route::delete('/packages/{id}',[PackageController::class, 'destroy']);

        // Lịch tập (admin duyệt)
        Route::get('/schedules',                   [TrainerScheduleController::class, 'indexAdmin']);
        Route::get('/schedules/{id}',              [TrainerScheduleController::class, 'show']);
        Route::post('/schedules/{id}/approve',     [TrainerScheduleController::class, 'approve']);
        Route::post('/schedules/{id}/reject',      [TrainerScheduleController::class, 'reject']);

        // Lương HLV
        Route::get('/salaries',        [TrainerSalaryController::class, 'index']);
        Route::post('/salaries',       [TrainerSalaryController::class, 'store']);
        Route::get('/salaries/{id}',   [TrainerSalaryController::class, 'show']);
        Route::put('/salaries/{id}',   [TrainerSalaryController::class, 'update']);
        Route::post('/salaries/{id}/pay', [TrainerSalaryController::class, 'pay']);
    });
});
