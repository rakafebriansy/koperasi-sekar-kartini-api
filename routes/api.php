<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Userc;
use App\Http\Controllers\WorkAreaController;
use App\Jobs\TestFcmNotificationJob;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'registerGroupMember']);

Route::get('/file/{path}', [FileController::class, 'show'])
->where('path', '.*');

Route::get('/download/{path}', [FileController::class, 'download'])
->where('path', '.*')
->name('download.file');

Route::get('/work-areas', [WorkAreaController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/refresh', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/member-growth', [ReportController::class, 'memberGrowth']);
    Route::get('/dashboard-stats', [ReportController::class, 'dashboardStats']);
    Route::get(
        '/savings-distribution-chart',
        [SavingsController::class, 'distribution']
    );
    Route::get(
        '/loan-distribution-chart',
        [LoanController::class, 'distribution']
    );
    
    Route::prefix('/work-areas')->middleware(['role:admin'])->group(function () {
        Route::post('/', [WorkAreaController::class, 'store']);
        Route::get('{id}', [WorkAreaController::class, 'show']);
        Route::patch('{id}', [WorkAreaController::class, 'update']);
        Route::delete('{id}', [WorkAreaController::class, 'destroy']);
    });
    
    Route::get('/users', [UserController::class, 'index']);
    Route::prefix('users')->middleware(['role:admin'])->group(function () {
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::patch('/{id}/groups/{groupId}', [UserController::class, 'updateGroup']);
        Route::patch('/{id}/activate', [UserController::class, 'activate']);
    });
    
    Route::get('/unlisted-members', [UserController::class, 'unlistedMembers']);
    Route::get('/inactive-members', [UserController::class, 'inactiveMembers']);
    
    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{groupId}', [GroupController::class, 'show']);
    Route::get('/groups/{groupId}/reports', [ReportController::class, 'index']);
    Route::prefix('groups')->middleware(['role:admin,employee'])->group(function () {
        Route::post('/', [GroupController::class, 'store']);
        Route::put('{groupId}', [GroupController::class, 'update']);
        Route::delete('{groupId}', [GroupController::class, 'destroy']);
        
        Route::patch('{groupId}/update-fund-amount', [GroupController::class, 'updateFundAmount']);
        Route::patch('{groupId}/facilitator/{userId}', [GroupController::class, 'updateFacilitator']);
        Route::patch('{groupId}/chairman/{userId}', [GroupController::class, 'updateChairman']);
        
        Route::prefix('/{groupId}/reports')->group(function () {
            Route::post('/', [ReportController::class, 'store']);
            Route::get('{reportId}', [ReportController::class, 'show']);
            Route::put('{reportId}', [ReportController::class, 'update']);
            Route::delete('{reportId}', [ReportController::class, 'destroy']);
        });
    });
    
    Route::prefix('meetings')->group(function () {
        Route::get('/', [MeetingController::class, 'index']);
        Route::get('/upcoming', [MeetingController::class, 'upcomingMeetings']);
        Route::post('/', [MeetingController::class, 'store']);
        Route::get('{id}', [MeetingController::class, 'show']);
        Route::put('{id}', [MeetingController::class, 'update']);
        Route::delete('{id}', [MeetingController::class, 'destroy']);
    });
    
    Route::get('/loans', [LoanController::class, 'index']);
    Route::get('/loans/sum-by-month', [LoanController::class, 'sumByMonth']);
    Route::prefix('loans')->middleware('role:admin,employee')->group(function () {
        Route::post('/', [LoanController::class, 'store']);
        Route::get('{id}', [LoanController::class, 'show']);
        Route::put('{id}', [LoanController::class, 'update']);
        Route::delete('{id}', [LoanController::class, 'destroy']);
    });
    
    Route::get('/savings', [SavingsController::class, 'index']);
    Route::get('/savings/sum-by-month', [SavingsController::class, 'sumByMonth']);
    Route::prefix('savings')->middleware('role:admin,employee')->group(function () {
        Route::post('/', [SavingsController::class, 'store']);
        Route::get('{id}', [SavingsController::class, 'show']);
        Route::put('{id}', [SavingsController::class, 'update']);
        Route::delete('{id}', [SavingsController::class, 'destroy']);
    });
    
    Route::get('/test-fcm', function () {
        TestFcmNotificationJob::dispatch(auth()->id());
        return response()->json(['status' => 'sent']);
    });
});

