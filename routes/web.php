<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/latihan', [HomeController::class, 'Home']);

// API Routes for Courses
Route::prefix('api/courses')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::post('/', [CourseController::class, 'store']);
    Route::get('/{course}', [CourseController::class, 'show']);
    Route::put('/{course}', [CourseController::class, 'update']);
    Route::delete('/{course}', [CourseController::class, 'destroy']);
    
    // Enrollment routes
    Route::get('/{course}/enrolled-users', [CourseController::class, 'enrolledUsers']);
    Route::post('/{courseId}/enroll', [CourseController::class, 'enroll'])->middleware('auth');
    Route::post('/{courseId}/unenroll', [CourseController::class, 'unenroll'])->middleware('auth');
});
