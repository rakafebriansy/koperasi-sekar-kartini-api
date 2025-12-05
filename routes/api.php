<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
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


Route::middleware('auth:sanctum')->group(function () {
    Route::get('refresh', [AuthController::class, 'refreshToken']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('work-areas')->middleware(['role:admin'])->group(function () {
        Route::get('/', [WorkAreaController::class, 'index']);
        Route::post('/', [WorkAreaController::class, 'store']);
        Route::get('{id}', [WorkAreaController::class, 'show']);
        Route::patch('{id}', [WorkAreaController::class, 'update']);
        Route::delete('{id}', [WorkAreaController::class, 'destroy']);
    });

    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::prefix('employees')->middleware(['role:admin'])->group(function () {
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('{id}', [EmployeeController::class, 'show']);
        Route::put('{id}', [EmployeeController::class, 'update']);
        Route::delete('{id}', [EmployeeController::class, 'destroy']);
    });

    // Route::middleware(['role:admin'])->group(function () {
    //     Route::put('verified/{id}', [Userc::class, 'updateVerified']);
    //     Route::put('active/{id}', [Userc::class, 'updateActive']);
    // });

    Route::prefix('groups')->middleware(['role:employee'])->group(function () {
        Route::get('/', [GroupController::class, 'index']);
        Route::post('/', [GroupController::class, 'store']);
        Route::get('{id}', [GroupController::class, 'show']);
        Route::patch('{id}', [GroupController::class, 'update']);
        Route::delete('{id}', [GroupController::class, 'destroy']);
        Route::patch('chairman/{id}', [GroupController::class, 'updateChairman']);
        Route::patch('facilitator/{id}', [GroupController::class, 'updateFacilitator']);
        Route::patch('treasurer/{id}', [GroupController::class, 'updateTreasurer']);
    });

    Route::middleware(['role:employee'])->group(function () {
        Route::patch('users/{id}/group', [Userc::class, 'updateGroupId']);
    });
});

