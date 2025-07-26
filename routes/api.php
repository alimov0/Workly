<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\VerificationController;

use App\Http\Controllers\VacancyController;

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VacancyController as AdminVacancyController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

use App\Http\Controllers\Employer\VacancyController as EmployerVacancyController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;

use App\Http\Controllers\JobSeeker\VacancyController as JobSeekerVacancyController;
use App\Http\Controllers\JobSeeker\ApplicationController as JobSeekerApplicationController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1']);

Route::get('/vacancies', [VacancyController::class, 'index']);
Route::get('/vacancies/{vacancy:slug}', [VacancyController::class, 'show']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('jobseeker')->prefix('jobseeker')->group(function () {
        Route::get('/vacancies', [JobSeekerVacancyController::class, 'index']);
        Route::get('/vacancies/{slug}', [JobSeekerVacancyController::class, 'show']);
        Route::post('/vacancies/{id}/apply', [JobSeekerVacancyController::class, 'apply']);

        Route::get('/applications', [JobSeekerApplicationController::class, 'index']);
        Route::delete('/applications/{id}', [JobSeekerApplicationController::class, 'destroy']);
    });

    Route::middleware('employer')->prefix('employer')->group(function () {
        Route::get('/vacancy', [EmployerVacancyController::class, 'index']);
        Route::post('/vacancy', [EmployerVacancyController::class, 'store']);
        Route::put('/vacancy/{id}', [EmployerVacancyController::class, 'update']);
        Route::delete('/vacancy/{id}', [EmployerVacancyController::class, 'destroy']);

        Route::get('/vacancy/{id}/applications', [EmployerVacancyController::class, 'applications']);

        Route::get('/applications/{application}', [EmployerApplicationController::class, 'show']);
        Route::put('/applications/{application}/status', [EmployerApplicationController::class, 'updateStatus']);
        Route::get('/applications/{application}/download', [EmployerApplicationController::class, 'download']);
    });

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::apiResource('users', AdminUserController::class);
        Route::apiResource('vacancy', AdminVacancyController::class);
        Route::apiResource('categories', AdminCategoryController::class);

        Route::get('/applications', [AdminApplicationController::class, 'index']);
        Route::post('/notifications/read', [AdminNotificationController::class, 'markAsRead']);
    });
});
