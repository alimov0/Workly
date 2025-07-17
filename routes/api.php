<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\VerificationController;

use App\Http\Controllers\VacancyController;

// Job Seeker
use App\Http\Controllers\ApplicationController as JobSeekerApplicationController;
use App\Http\Controllers\JobSeeker\VacancyController as JobSeekerVacancyController;

// Employer
use App\Http\Controllers\Employer\VacancyController as EmployerVacancyController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;

// Admin
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VacancyController as AdminVacancyController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

// Get current user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth: register & login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Email verification
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1']);

// Public vacancies
Route::get('/vacancies', [VacancyController::class, 'index']);
Route::get('/vacancies/{vacancy:slug}', [VacancyController::class, 'show']);



/// ✅ AUTHENTICATED ROUTES (verified users)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    /// 👨‍💼 Job Seeker routes
    Route::prefix('job-seeker')->group(function () {
        Route::get('/vacancies', [JobSeekerVacancyController::class, 'index']);
        Route::get('/vacancies/{vacancy}', [JobSeekerVacancyController::class, 'show']);

        Route::get('/applications', [JobSeekerApplicationController::class, 'index']);
        Route::post('/vacancies/{vacancy}/apply', [JobSeekerApplicationController::class, 'store']);
        Route::delete('/applications/{application}', [JobSeekerApplicationController::class, 'destroy']);
    });

    /// 🧑‍💼 Employer routes
    Route::prefix('employer')->middleware('employer')->group(function () {
        // Vacancies
        Route::apiResource('vacancies', EmployerVacancyController::class)->except(['show']);
        Route::post('vacancies/{vacancy}/toggle-status', [EmployerVacancyController::class, 'toggleStatus']);
        Route::get('vacancies/{vacancy}/applications', [EmployerVacancyController::class, 'applications']);

        // Applications
        Route::get('applications/{application}', [EmployerApplicationController::class, 'show']);
        Route::put('applications/{application}/status', [EmployerApplicationController::class, 'updateStatus']);
        Route::get('applications/{application}/download', [EmployerApplicationController::class, 'download']);
    });

    /// 🛠️ Admin routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        // CRUD
        Route::apiResource('users', AdminUserController::class);
        Route::apiResource('vacancies', AdminVacancyController::class);
        Route::apiResource('categories', AdminCategoryController::class);

        // Others
        Route::get('/applications', [AdminApplicationController::class, 'index']);
        Route::post('/notifications/read', [AdminNotificationController::class, 'markAsRead']);
    });
});
