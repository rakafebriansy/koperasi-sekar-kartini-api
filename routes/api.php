<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MemberGroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkAreaController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register-group-member', [AuthController::class, 'registerGroupMember']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('work-areas')->middleware(['role:admin'])->group(function () {
        Route::get('/', [WorkAreaController::class, 'index']);
        Route::post('/', [WorkAreaController::class, 'store']);
        Route::get('{id}', [WorkAreaController::class, 'show']);
        Route::patch('{id}', [WorkAreaController::class, 'update']);
        Route::delete('{id}', [WorkAreaController::class, 'destroy']);
    });

    Route::prefix('employees')->middleware(['role:admin,employee'])->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('{id}', [EmployeeController::class, 'show']);
        Route::patch('{id}', [EmployeeController::class, 'update']);
        Route::delete('{id}', [EmployeeController::class, 'destroy']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::put('verified/{id}', [UserController::class, 'updateVerified']);
        Route::put('active/{id}', [UserController::class, 'updateActive']);
    });

    Route::prefix('groups')->middleware(['role:employee'])->group(function () {
        Route::get('/', [MemberGroupController::class, 'index']);
        Route::post('/', [MemberGroupController::class, 'store']);
        Route::get('{id}', [MemberGroupController::class, 'show']);
        Route::patch('{id}', [MemberGroupController::class, 'update']);
        Route::delete('{id}', [MemberGroupController::class, 'destroy']);
        Route::patch('chairman/{id}', [MemberGroupController::class, 'updateChairman']);
        Route::patch('facilitator/{id}', [MemberGroupController::class, 'updateFacilitator']);
        Route::patch('treasurer/{id}', [MemberGroupController::class, 'updateTreasurer']);
    });

    Route::middleware(['role:employee'])->group(function () {
        Route::patch('users/{id}/group', [UserController::class, 'updateGroupId']);
    });
});

