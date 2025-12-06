<?php

use App\Http\Controllers\AuthController;
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
        Route::get('{id}', [UserController::class, 'show']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
        Route::patch('{id}/group/{groupId}', [UserController::class, 'updateGroup']);
    });

    Route::get('/groups', [GroupController::class, 'index']);
    Route::prefix('groups')->middleware(['role:admin,employee'])->group(function () {
        Route::post('/', [GroupController::class, 'store']);
        Route::get('{id}', [GroupController::class, 'show']);
        Route::put('{id}', [GroupController::class, 'update']);
        Route::delete('{id}', [GroupController::class, 'destroy']);
        Route::patch('{id}/facilitator/{userId}', [GroupController::class, 'updateFacilitator']);
        Route::patch('{id}/chairman/{userId}', [GroupController::class, 'updateChairman']);
    });

    
    // Route::middleware(['role:admin'])->group(function () {
    //     Route::put('verified/{id}', [Userc::class, 'updateVerified']);
    //     Route::put('active/{id}', [Userc::class, 'updateActive']);
    // });

    // Route::middleware(['role:employee'])->group(function () {
    //     Route::patch('users/{id}/group', [Userc::class, 'updateGroupId']);
    // });
});

