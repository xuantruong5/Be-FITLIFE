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
// Route::post('/admin/login',   [AdminController::class, 'adminLogin']);

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

    // xem gói hiện tại 
    Route::get('/my-package',       [MembersController::class, 'myPackage']);

    // xem ghi trú từ hlv 
    Route::get('/my-trainer-note',  [TrainerNoteController::class, 'myTrainerNote']);

    Route::get('/my-schedule',  [ScheduleMemberController::class, 'mySchedule']);

    Route::get('/my-schedule/{id}', [ScheduleMemberController::class, 'scheduleDetail']);


    Route::get('/title',       [MembersController::class, 'getScheduleTitles']);

    Route::get('/my-trainer',  [MembersController::class, 'getTrainer']);

    Route::get('/my-trainer/{id}',  [MembersController::class, 'getTrainerdetail']);


    Route::get('/package/{id_package}/trainers',[MembersController::class, 'getTrainerByPackage']); // lấy theo gói xem gói đó có bao nhiêu hlv

    Route::get('/packages', [MembersController::class, 'getPackage']); // lấy gói

    Route::get('/schedule/{id}', [MembersController::class, 'getScheduleDetail']); // lấy chi tiết đặt lịch trước khi thanh toán 

    Route::post('/create-order', [MembersController::class, 'createOrder']);
    
    Route::post('/check-promotion', [MembersController::class, 'checkPromotion']);

    Route::get('/orders/check-payment/{orderCode}', [MembersController::class, 'checkPayment']);

    // Huy Lich 
    Route::post('/cancel-schedule', [ScheduleMemberController::class, 'cancelSchedule']);

    Route::post('/change-schedule', [ScheduleMemberController::class, 'changeSchedule']);

    Route::get('/get-schedule/{id}', [ScheduleMemberController::class, 'getChangeSchedule']); // lấy giờ ngày tháng để đổi lịch 








    




});

// ============================================================
// TRAINER ROUTES (Cần đăng nhập với token trainer)
// ============================================================
Route::prefix('trainer')->middleware('trainerMiddleware')->group(function () {
    // Auth
    Route::post('/logout',       [TrainerController::class, 'logoutTrainer']);
    Route::post('/logout-all',   [TrainerController::class, 'logoutAllTrainer']);

    // Lịch tập của HLV
    // Route::get('/schedules',          [TrainerScheduleController::class, 'index']);
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




    Route::get('/goi/chi-nhanh',        [TrainerController::class, 'getGoiChiNhanh']);
    Route::post('/create-schedule',     [TrainerScheduleController::class, 'StoreSchedule']);
    Route::get('/schedules',            [TrainerScheduleController::class, 'getSchedules']);
    Route::post('/change-schedule',     [TrainerScheduleController::class, 'changeSchedule']);

    // lấy học viên 
    Route::get('/member-packages', [TrainerController::class, 'memberPackages']);

    Route::get('today-schedules',       [TrainerScheduleController::class, 'getTodaySchedules']);

    Route::post('change/attendances',   [AttendanceController::class, 'attendances']);

    // lấy lịch dạy từng học viên 
    Route::get('member-schedules',       [TrainerScheduleController::class, 'getScheduleMembers']);


    Route::get('income',       [TrainerController::class, 'income']);



    

    





});

// ============================================================
// ADMIN ROUTES (Cần đăng nhập với token admin)
// ============================================================
    Route::post('admin/login',  [AdminController::class, 'adminLogin']);
    Route::group(['prefix' => 'admin','middleware' => 'AdminMiddleware'], function () {
            Route::post('/logout', [AdminController::class, 'adminLogout']);
            Route::get('/check-token', [AdminController::class, 'checkTokenAdmin']);
            Route::get('/dashboard',       [AdminController::class, 'index']);
            Route::get('/member',       [AdminController::class, 'getMember']);
            Route::get('/trainner',       [AdminController::class, 'getTrainner']);
            Route::get('/package',       [AdminController::class, 'getPackage']);
            Route::post('store/packages',      [PackageController::class, 'storePackage']);


            Route::get('/reschedule', [AdminController::class, 'getReschedules']);

            Route::post('change/reschedule', [AdminController::class, 'reschedulesChange']);

            Route::get('/invoices', [AdminController::class, 'getInvoices']);
            Route::get('sum/invoices', [AdminController::class, 'statisticInvoice']);


          






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
