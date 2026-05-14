<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseEnrollmentController;
use App\Http\Controllers\Api\CourseTopicController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->scopeBindings()->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses/{course}/enroll', [CourseEnrollmentController::class, 'store']);
    Route::get('/users/me/courses', [CourseEnrollmentController::class, 'index']);

    Route::get('/courses/{course}/topics', [CourseTopicController::class, 'index']);
    Route::post('/courses/{course}/topics/{topic}/complete', [CourseTopicController::class, 'complete']);
});
