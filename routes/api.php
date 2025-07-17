<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Email verification routes
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1']);

// Authenticated routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('employer')->middleware(['auth:sanctum', 'employer'])->group(function () {
    Route::apiResource('vacancies', Employer\VacancyController::class)->except(['show']);
    Route::get('vacancies/{vacancy}/applications', [Employer\VacancyController::class, 'applications']);
    Route::post('vacancies/{vacancy}/toggle-status', [Employer\VacancyController::class, 'toggleStatus']);
});
Route::prefix('employer')->middleware(['auth:sanctum', 'employer'])->group(function () {
    // ... boshqa route'lar
    
    Route::get('applications/{application}', [Employer\ApplicationController::class, 'show']);
    Route::put('applications/{application}/status', [Employer\ApplicationController::class, 'updateStatus']);
    Route::get('applications/{application}/download', [Employer\ApplicationController::class, 'download']);
});

Route::prefix('job-seeker')->middleware(['auth:sanctum', 'verified'])->group(function () {
    // Vakansiyalar
    Route::get('vacancies', [JobSeeker\VacancyController::class, 'index']);
    Route::get('vacancies/{vacancy}', [JobSeeker\VacancyController::class, 'show']);
    
    // Arizalar
    Route::get('applications', [JobSeeker\ApplicationController::class, 'index']);
    Route::post('vacancies/{vacancy}/apply', [JobSeeker\ApplicationController::class, 'store']);
    Route::delete('applications/{application}', [JobSeeker\ApplicationController::class, 'destroy']);
});