<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationSettingController;


Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('tasks', TaskController::class);
    Route::patch('/tasks/{id}/complete', [TaskController::class, 'complete']);

    Route::get('/notification-settings', [NotificationSettingController::class, 'show']);

    Route::post('/notification-settings', [NotificationSettingController::class, 'store']);

});