<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Userc;
use App\Http\Controllers\WorkAreaController;
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
        Route::patch('/{id}/group/{groupId}', [UserController::class, 'updateGroup']);
        Route::patch('/{id}/activate', [UserController::class, 'activate']);
    });

    Route::get('/unlisted-members', [UserController::class, 'unlistedMembers']);
    Route::get('/inactive-members', [UserController::class, 'inactiveMembers']);

    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{id}', [GroupController::class, 'show']);
    Route::prefix('groups')->middleware(['role:admin,employee'])->group(function () {
        Route::post('/', [GroupController::class, 'store']);
        Route::put('{id}', [GroupController::class, 'update']);
        Route::delete('{id}', [GroupController::class, 'destroy']);
        Route::patch('{id}/update-fund-amount', [GroupController::class, 'updateFundAmount']);
        Route::patch('{id}/facilitator/{userId}', [GroupController::class, 'updateFacilitator']);
        Route::patch('{id}/chairman/{userId}', [GroupController::class, 'updateChairman']);
    });

    Route::prefix('meetings')->group(function () {
        Route::get('/', [MeetingController::class, 'index']);
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
});

